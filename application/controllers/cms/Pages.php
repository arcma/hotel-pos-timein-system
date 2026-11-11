<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Pages extends MY_Controller {
    public function __construct(){ parent::__construct(); $this->load->model('Cms_pages_model','pages_model'); }
    public function index(){ $data['title']='Website Pages';$data['pages']=$this->pages_model->all();$data['message']=$this->session->flashdata('cms_pages_message');$this->load->view('templates/top',$data);$this->load->view('cms/pages/list',$data);$this->load->view('templates/bottom'); }
    public function add(){ $data['title']='Add Page';$data['page']=null;$data['error']='';$this->load->view('templates/top',$data);$this->load->view('cms/pages/form',$data);$this->load->view('templates/bottom'); }
    public function edit($id=0){ $page=$this->pages_model->get($id);if(!$page)show_404();$data['title']='Edit Page';$data['page']=$page;$data['error']='';$this->load->view('templates/top',$data);$this->load->view('cms/pages/form',$data);$this->load->view('templates/bottom'); }
    public function save($id=0){ if(strtoupper($this->input->method())!=='POST')show_404();$title=trim((string)$this->input->post('pageTitle',true));$slug=strtolower(trim((string)$this->input->post('pageSlug',true)));$slug=trim(preg_replace('/[^a-z0-9-]+/','-', $slug),'-');$status=$this->input->post('pageStatus')?1:0;$content=$this->input->post('pageContent',false);$css=$this->input->post('pageCSS',false);$js=$this->input->post('pageJS',false);
        if($title===''||$slug===''){$this->session->set_flashdata('cms_pages_message','Title and URL are required.');redirect($id?'cms/pages/edit/'.$id:'cms/pages/add');return;}
        if($this->pages_model->slug_exists($slug,$id)){$this->session->set_flashdata('cms_pages_message','That URL is already in use.');redirect($id?'cms/pages/edit/'.$id:'cms/pages/add');return;}
        $ok=$this->pages_model->save(array('pageTitle'=>$title,'pageSlug'=>$slug,'pageContent'=>(string)$content,'pageCSS'=>(string)$css,'pageJS'=>(string)$js,'pageStatus'=>$status),$id);
        $this->session->set_flashdata('cms_pages_message',$ok?'Page saved successfully.':'Unable to save page. Check that the tbl_pages migration has been imported.');redirect('cms/pages');
    }
    public function delete($id=0){if(strtoupper($this->input->method())!=='POST')show_404();$ok=$this->pages_model->delete($id);$this->session->set_flashdata('cms_pages_message',$ok?'Page deleted.':'Unable to delete page.');redirect('cms/pages');}
}
