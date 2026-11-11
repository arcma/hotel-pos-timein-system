<?php
$menuItems = isset($nav_menu) && is_array($nav_menu) ? $nav_menu : array();
if (!$menuItems && isset($this->Settings_model)) { $decoded=json_decode((string)$this->Settings_model->get_setting('navMenu'),true); $menuItems=is_array($decoded)?$decoded:array(); }
?>
<header class="site-header">
 <a class="logo" href="<?=base_url()?>">ARSENIOS <span>SILAY</span></a>
 <nav class="main-nav" aria-label="Main navigation">
 <?php foreach($menuItems as $item): if(!is_array($item) || empty($item['enabled']) || trim((string)($item['label']??''))==='') continue; $link=trim((string)($item['link']??'')); ?>
 <a href="<?=html_escape($link)?>"><?=html_escape($item['label'])?></a>
 <?php endforeach; ?>
 </nav>
 <a class="book-button" href="<?=base_url('booknow')?>">Book Now</a>
 <button class="mobile-menu" type="button" aria-label="Open menu">☰</button>
</header>
