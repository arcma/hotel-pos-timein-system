<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller
{
    private $section_names = array(
        'homeBanner', 'homeIntro', 'homeBlock1', 'homeBlock2',
        'homeAmenities', 'homeOffers', 'homeCTA'
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Cms_homepage_model', 'homepage_model');
        $this->load->library('upload');
    }

    public function index()
    {
        $data['title'] = 'home';
        $data['sections'] = $this->homepage_model->get_all_sections();
        $data['flash_message'] = $this->session->flashdata('cms_message');
        $data['flash_type'] = $this->session->flashdata('cms_message_type');

        $this->load->view('templates/top', $data);
        $this->load->view('cms/home', $data);
        $this->load->view('templates/bottom');
    }

    public function save_section()
    {
        if (strtoupper($this->input->method()) !== 'POST') {
            show_404();
        }

        $name = $this->input->post('homeName', true);
        if (!in_array($name, $this->section_names, true)) {
            show_error('Invalid homepage section.', 400);
        }

        $current = $this->homepage_model->get_section($name);
        $content = array();
        $status = (int) $this->input->post('homeStatus');
        $status = $status === 1 ? 1 : 0;

        if ($name === 'homeBanner') {
            // The banner section itself is always enabled. Individual slides have their own status.
            $status = 1;
            $posted_slides = $this->input->post('slides');
            $posted_slides = is_array($posted_slides) ? $posted_slides : array();
            $old_content = $current ? json_decode($current->homeContent, true) : array();
            $old_slides = isset($old_content['slides']) && is_array($old_content['slides']) ? $old_content['slides'] : array();
            $content['slides'] = array();

            foreach ($posted_slides as $index => $slide) {
                if (!is_array($slide)) continue;
                $old_image = isset($slide['existing_image']) ? basename($slide['existing_image']) : '';
                $image = $old_image;
                $field = 'slide_image_' . $index;
                if (isset($_FILES[$field]) && !empty($_FILES[$field]['name'])) {
                    $uploaded = $this->upload_home_image($field, 'homebanners');
                    if ($uploaded === false) {
                        $this->session->set_flashdata('cms_message', !empty($this->upload_error_message) ? $this->upload_error_message : $this->upload->display_errors('', ''));
                        $this->session->set_flashdata('cms_message_type', 'danger');
                        redirect('cms/home');
                        return;
                    }
                    $image = $uploaded;
                }
                $content['slides'][] = array(
                    'eyebrow' => isset($slide['eyebrow']) ? trim($slide['eyebrow']) : '',
                    'number' => isset($slide['number']) ? trim($slide['number']) : '',
                    'h1' => isset($slide['h1']) ? trim($slide['h1']) : '',
                    'description' => isset($slide['description']) ? trim($slide['description']) : '',
                    'image' => $image,
                    'status' => isset($slide['status']) && (string) $slide['status'] === '1' ? 1 : 0
                );
            }
        } else {
            $posted = $this->input->post('content');
            $posted = is_array($posted) ? $posted : array();

            if ($name === 'homeIntro') {
                $content = array(
                    'top' => $this->posted_value($posted, 'top'),
                    'title' => $this->posted_value($posted, 'title'),
                    'description' => $this->posted_value($posted, 'description')
                );
            } elseif ($name === 'homeBlock1' || $name === 'homeBlock2') {
                $folder = $name === 'homeBlock1' ? 'block1' : 'block2';
                $old_content = $current ? json_decode($current->homeContent, true) : array();
                $image = isset($old_content['image']) ? basename($old_content['image']) : '';
                $field = $name . '_image';
                if (isset($_FILES[$field]) && !empty($_FILES[$field]['name'])) {
                    $uploaded = $this->upload_home_image($field, $folder);
                    if ($uploaded === false) {
                        $this->session->set_flashdata('cms_message', !empty($this->upload_error_message) ? $this->upload_error_message : $this->upload->display_errors('', ''));
                        $this->session->set_flashdata('cms_message_type', 'danger');
                        redirect('cms/home');
                        return;
                    }
                    $image = $uploaded;
                }
                $content = array(
                    'top' => $this->posted_value($posted, 'top'),
                    'title' => $this->posted_value($posted, 'title'),
                    'description' => $this->posted_value($posted, 'description'),
                    'image' => $image
                );
            } elseif ($name === 'homeAmenities' || $name === 'homeOffers') {
                $count = $name === 'homeAmenities' ? 4 : 2;
                $content = array('title' => $this->posted_value($posted, 'title'), 'items' => array());
                for ($i = 0; $i < $count; $i++) {
                    $item = isset($posted['items'][$i]) && is_array($posted['items'][$i]) ? $posted['items'][$i] : array();
                    $content['items'][] = array(
                        'title' => isset($item['title']) ? trim($item['title']) : '',
                        'description' => isset($item['description']) ? trim($item['description']) : ''
                    );
                }
            } elseif ($name === 'homeCTA') {
                $content = array(
                    'smalltitle' => $this->posted_value($posted, 'smalltitle'),
                    'bigtitle' => $this->posted_value($posted, 'bigtitle'),
                    'link' => $this->posted_value($posted, 'link')
                );
            }
        }

        $saved = $this->homepage_model->save_section($name, $content, $status);
        $this->session->set_flashdata('cms_message', $saved ? 'Homepage section saved successfully.' : 'Unable to save this section. Please check the database and try again.');
        $this->session->set_flashdata('cms_message_type', $saved ? 'success' : 'danger');
        redirect('cms/home');
    }


    /** Save the two homepage content blocks from the combined Top/Bottom tab editor. */
    public function save_content_blocks()
    {
        if (strtoupper($this->input->method()) !== 'POST') show_404();
        $blocks = $this->input->post('blocks');
        $blocks = is_array($blocks) ? $blocks : array();
        $all_saved = true;
        foreach (array('homeBlock1' => 'block1', 'homeBlock2' => 'block2') as $name => $folder) {
            $posted = isset($blocks[$name]) && is_array($blocks[$name]) ? $blocks[$name] : array();
            $current = $this->homepage_model->get_section($name);
            $old = $current ? json_decode($current->homeContent, true) : array();
            if (!is_array($old)) $old = array();
            $image = isset($old['image']) ? basename($old['image']) : '';
            $field = $name . '_image';
            if (isset($_FILES[$field]) && !empty($_FILES[$field]['name'])) {
                $uploaded = $this->upload_home_image($field, $folder);
                if ($uploaded === false) {
                    $this->session->set_flashdata('cms_message', !empty($this->upload_error_message) ? $this->upload_error_message : $this->upload->display_errors('', ''));
                    $this->session->set_flashdata('cms_message_type', 'danger');
                    redirect('cms/home'); return;
                }
                $image = $uploaded;
            }
            $content = array(
                'top' => $this->posted_value($posted, 'top'),
                'title' => $this->posted_value($posted, 'title'),
                'description' => $this->posted_value($posted, 'description'),
                'link' => $this->posted_value($posted, 'link'),
                'image' => $image
            );
            $status = isset($posted['status']) && (string)$posted['status'] === '1' ? 1 : 0;
            if (!$this->homepage_model->save_section($name, $content, $status)) $all_saved = false;
        }
        $this->session->set_flashdata('cms_message', $all_saved ? 'Content blocks saved successfully.' : 'One or more content blocks could not be saved.');
        $this->session->set_flashdata('cms_message_type', $all_saved ? 'success' : 'danger');
        redirect('cms/home');
    }

    private function posted_value($data, $key)
    {
        return isset($data[$key]) && is_scalar($data[$key]) ? trim((string) $data[$key]) : '';
    }

    private function upload_home_image($field, $folder)
    {
        $relative_dir = 'assets/uploads/' . $folder . '/';
        $absolute_dir = FCPATH . $relative_dir;
        if (!is_dir($absolute_dir) && !mkdir($absolute_dir, 0755, true) && !is_dir($absolute_dir)) {
            $this->upload_error_message = 'Unable to create the image upload directory.';
            return false;
        }
        $config = array(
            'upload_path' => $absolute_dir,
            'allowed_types' => 'jpg|jpeg|png|webp',
            'max_size' => 8192,
            'encrypt_name' => true,
            'remove_spaces' => true,
            'detect_mime' => true,
            'mod_mime_fix' => true
        );
        $this->upload->initialize($config, true);
        if (!$this->upload->do_upload($field)) {
            $this->upload_error_message = $this->upload->display_errors('', '');
            return false;
        }
        $upload = $this->upload->data();
        return $upload['file_name'];
    }
}
