<?php
$footerData = json_decode((string)$this->Settings_model->get_setting('footerData'), true);
if (!is_array($footerData)) $footerData = array();
$footerDescription = isset($footerData['description']) && trim((string)$footerData['description']) !== ''
    ? (string)$footerData['description']
    : 'Welcome to Arsenio\'s Silay. Contact us for room availability and reservations.';
$footerLinks = isset($footerData['links']) && is_array($footerData['links']) ? $footerData['links'] : array();
?>
<footer class="site-footer">
 <div class="footer-top">
  <div><a class="logo" href="<?=base_url()?>">ARSENIOS <span>SILAY</span></a><p><?=nl2br(html_escape($footerDescription))?></p></div>
  <div class="footer-links">
   <?php foreach ($footerLinks as $item): if (!is_array($item) || empty($item['enabled']) || trim((string)($item['label'] ?? '')) === '' || trim((string)($item['link'] ?? '')) === '') continue; ?>
    <a href="<?=html_escape((string)$item['link'])?>"><?=html_escape((string)$item['label'])?></a>
   <?php endforeach; ?>
  </div>
 </div>
 <div class="footer-bottom"><span>© <?=date('Y')?> Arsenio's Silay</span><span>All rights reserved</span></div>
</footer>
