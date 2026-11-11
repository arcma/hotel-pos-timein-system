<?php
/*
* Pagination Config
*/
$config['full_tag_open'] = '<nav class="d-flex justify-content-center"><ul class="pagination">';
$config['full_tag_close'] = '</ul></nav>';
$config['first_link'] = false;
$config['last_link'] = false;
$config['first_tag_open'] = '<li class="page-item">';
$config['first_tag_close'] = '</li>';
$config['prev_link'] = '&laquo';
$config['prev_tag_open'] = '<li class="page-item">';
$config['prev_tag_close'] = '</li>';
$config['next_link'] = '&raquo';
$config['next_tag_open'] = '<li class="page-item">';
$config['next_tag_close'] = '</li>';
$config['last_tag_open'] = '<li class="page-item">';
$config['last_tag_close'] = '</li>';
$config['cur_tag_open'] = '<li class="page-item"><a class="page-link active" href="#">';
$config['cur_tag_close'] = '</a></li>';
$config['num_tag_open'] = '<li class="page-item">';
$config['num_tag_close'] = '</li>';
$config['attributes'] = [
    'class' => 'page-link'
];
$config['use_page_numbers'] = true;
$config['page_query_string'] = true;
$config['query_string_segment'] = 'page';