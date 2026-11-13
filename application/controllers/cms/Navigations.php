<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Navigations extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Settings_model');
        $this->load->model('Cms_pages_model');
    }

    public function index()
    {
        $items = json_decode((string)$this->Settings_model->get_setting('navMenu'), true);
        $data['items'] = is_array($items) ? array_values($items) : array();
        $data['pages'] = $this->Cms_pages_model->all();
        $data['title'] = 'navigations';
        $data['flash_message'] = $this->session->flashdata('nav_message');
        $data['flash_type'] = $this->session->flashdata('nav_type');
        $this->load->view('templates/top', $data);
        $this->load->view('cms/navigations', $data);
        $this->load->view('templates/bottom');
    }

    public function save()
    {
        if (strtoupper($this->input->method()) !== 'POST') show_404();
        $posted = $this->input->post('items');
        $existingItems = json_decode((string) $this->Settings_model->get_setting('navMenu'), true);
        $legacyLinks = array();
        if (is_array($existingItems)) {
            foreach ($existingItems as $existingItem) {
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
        $saved = $this->Settings_model->set_setting('navMenu', json_encode($items, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        $this->session->set_flashdata('nav_message', $saved ? 'Navigation menu saved.' : 'Unable to save navigation menu.');
        $this->session->set_flashdata('nav_type', $saved ? 'success' : 'danger');
        redirect('cms/navigations');
    }
}
