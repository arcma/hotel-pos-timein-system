<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Footer extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Settings_model');
        $this->load->model('Cms_pages_model');
    }

    public function index()
    {
        $stored = json_decode((string)$this->Settings_model->get_setting('footerData'), true);
        if (!is_array($stored)) $stored = array();
        $data['description'] = isset($stored['description']) ? (string)$stored['description'] : '';
        $data['items'] = isset($stored['links']) && is_array($stored['links']) ? array_values($stored['links']) : array();
        $data['pages'] = $this->Cms_pages_model->all();
        $data['title'] = 'footer';
        $data['flash_message'] = $this->session->flashdata('footer_message');
        $data['flash_type'] = $this->session->flashdata('footer_type');
        $this->load->view('templates/top', $data);
        $this->load->view('cms/footer', $data);
        $this->load->view('templates/bottom');
    }

    public function save()
    {
        if (strtoupper($this->input->method()) !== 'POST') show_404();
        $description = trim(strip_tags((string)$this->input->post('description')));
        $posted = $this->input->post('items');
        $existingStored = json_decode((string) $this->Settings_model->get_setting('footerData'), true);
        $legacyLinks = array();
        if (is_array($existingStored) && isset($existingStored['links']) && is_array($existingStored['links'])) {
            foreach ($existingStored['links'] as $existingItem) {
                if (is_array($existingItem) && !empty($existingItem['link'])) $legacyLinks[] = (string) $existingItem['link'];
            }
        }
        $validSlugs = array();
        foreach ($this->Cms_pages_model->all() as $page) $validSlugs[(string) $page->pageSlug] = true;
        $items = array();
        if (is_array($posted)) {
            foreach ($posted as $item) {
                if (!is_array($item)) continue;
                $label = trim(strip_tags((string)($item['label'] ?? '')));
                $link = trim((string)($item['link'] ?? ''));
                if ($label === '' && $link === '') continue;
                if (preg_match('~^/?pages/([a-zA-Z0-9_-]+)$~', $link, $match)) {
                    if (!isset($validSlugs[$match[1]])) continue;
                    $link = '/pages/' . $match[1];
                } elseif (!in_array($link, $legacyLinks, true)) {
                    continue;
                }
                $items[] = array(
                    'label' => $label,
                    'link' => $link,
                    'enabled' => isset($item['enabled']) && (string)$item['enabled'] === '1' ? 1 : 0
                );
            }
        }
        $payload = array('description' => $description, 'links' => $items);
        $saved = $this->Settings_model->set_setting('footerData', json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        $this->session->set_flashdata('footer_message', $saved ? 'Footer settings saved.' : 'Unable to save footer settings.');
        $this->session->set_flashdata('footer_type', $saved ? 'success' : 'danger');
        redirect('cms/footer');
    }
}
