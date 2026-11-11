<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Hotel Website Template</title>
  <link rel="stylesheet" href="<?=base_url('assets/css/website.css')?>">
</head>
<body>
<?php
$cms = isset($cms_sections) && is_array($cms_sections) ? $cms_sections : array();
$section = function($key) use ($cms) { return isset($cms[$key]) ? $cms[$key] : array('status'=>0,'content'=>array()); };
$introData=$section('homeIntro'); $b1=$section('homeBlock1'); $b2=$section('homeBlock2'); $amen=$section('homeAmenities'); $offersData=$section('homeOffers'); $ctaData=$section('homeCTA');
$esc=function($v){return html_escape((string)$v);};
?>

<?php $this->load->view('website/partials/navigation'); ?>

<main>
  <!-- HERO / BOOKING -->
  <section class="hero" id="stay">
    <!--div class="hero-content">
      <p class="eyebrow">BOUTIQUE HOTEL • YOUR CITY</p>
      <h1>Stays worth<br>remembering.</h1>
      <p>Replace this text with your hotel's main message or value proposition.</p>
    </div-->

    <?php $this->load->view('website/banners'); ?>


    <!--div class="booking-box" id="booking">
      <div class="booking-tabs">
        <button class="tab active" data-tab="booking-form">Book a stay</button>
        <button class="tab" data-tab="manage-form">Manage reservation</button>
      </div>

      <form id="booking-form" class="booking-form">
        <label>
          Destination
          <input type="text" value="Hotel Name" placeholder="Destination">
        </label>

        <label>
          Check in
          <input id="checkin" type="date">
        </label>

        <label>
          Check out
          <input id="checkout" type="date">
        </label>

        <label class="guest-field">
          Rooms & Guests
          <button type="button" id="guest-button">1 Room · 2 Adults</button>
          <div class="guest-menu" id="guest-menu">
            <div><span>Rooms</span><div><button type="button" data-count="rooms" data-change="-1">−</button><b id="rooms">1</b><button type="button" data-count="rooms" data-change="1">+</button></div></div>
            <div><span>Adults</span><div><button type="button" data-count="adults" data-change="-1">−</button><b id="adults">2</b><button type="button" data-count="adults" data-change="1">+</button></div></div>
            <div><span>Children</span><div><button type="button" data-count="children" data-change="-1">−</button><b id="children">0</b><button type="button" data-count="children" data-change="1">+</button></div></div>
          </div>
        </label>

        <label>
          Rate
          <select>
            <option>Best Available</option>
            <option>Member Rate</option>
            <option>AAA / AARP</option>
          </select>
        </label>

        <button class="search-button" type="submit">Search →</button>
      </form>

      <form id="manage-form" class="manage-form hidden">
        <label>Confirmation number<input placeholder="Confirmation number"></label>
        <label>Last name<input placeholder="Last name"></label>
        <button class="search-button" type="submit">Find reservation →</button>
      </form>

      <p id="booking-message"></p>
    </div-->
  </section>

  <!-- INTRO -->
  <?php if(!empty($introData['status'])): $c=$introData['content']; ?>
  <section class="intro section"><p class="eyebrow"><?=$esc($c['top']??'WELCOME')?></p><h2><?=$esc($c['title']??'')?></h2><p><?=$esc($c['description']??'')?></p></section>
  <?php endif; ?>

  <!-- CONTENT BLOCK 1 -->
  <?php if(!empty($b1['status'])): $c=$b1['content']; ?>
  <section class="split-section section"><div class="placeholder-image image-one" role="img" aria-label="<?= $esc($c['title']??'') ?>" <?php if(!empty($c['image'])): ?>style="background-image:url('<?=base_url('assets/uploads/block1/'.rawurlencode(basename($c['image'])))?>');background-size:cover;background-position:center"<?php endif;?>></div><div class="content"><p class="eyebrow"><?=$esc($c['top']??'')?></p><h2><?=$esc($c['title']??'')?></h2><p><?=$esc($c['description']??'')?></p><?php if(!empty($c['link'])):?><a href="<?=html_escape($c['link'])?>" class="text-link">Discover more →</a><?php endif;?></div></section>
  <?php endif; ?>

  <!-- CONTENT BLOCK 2 -->
  <?php if(!empty($b2['status'])): $c=$b2['content']; ?>
  <section class="split-section reverse section"><div class="content"><p class="eyebrow"><?=$esc($c['top']??'')?></p><h2><?=$esc($c['title']??'')?></h2><p><?=$esc($c['description']??'')?></p><?php if(!empty($c['link'])):?><a href="<?=html_escape($c['link'])?>" class="text-link">Explore more →</a><?php endif;?></div><div class="placeholder-image image-two" role="img" aria-label="<?= $esc($c['title']??'') ?>" <?php if(!empty($c['image'])): ?>style="background-image:url('<?=base_url('assets/uploads/block2/'.rawurlencode(basename($c['image'])))?>');background-size:cover;background-position:center"<?php endif;?>></div></section>
  <?php endif; ?>

  <!-- AMENITIES -->
  <?php if(!empty($amen['status'])): $c=$amen['content']; ?>
  <section class="amenities section"><div class="section-heading"><p class="eyebrow">HOTEL AMENITIES</p><h2><?=$esc($c['title']??'Everything you need.')?></h2></div><div class="amenity-grid"><?php foreach(($c['items']??array()) as $i=>$item): ?><article><span><?=str_pad((string)($i+1),2,'0',STR_PAD_LEFT)?></span><h3><?=$esc($item['title']??'')?></h3><p><?=$esc($item['description']??'')?></p></article><?php endforeach;?></div></section>
  <?php endif; ?>

  <!-- OFFERS -->
  <?php if(!empty($offersData['status'])): $c=$offersData['content']; ?>
  <section class="offers section" id="offers"><div class="section-heading"><p class="eyebrow">SPECIAL OFFERS</p><h2><?=$esc($c['title']??'Something extra for your next stay.')?></h2></div><div class="offer-grid"><?php foreach(($c['items']??array()) as $item): ?><article class="offer-card"><h3><?=$esc($item['title']??'')?></h3><p><?=$esc($item['description']??'')?></p></article><?php endforeach;?></div></section>
  <?php endif; ?>

  <!-- CTA -->
  <?php if(!empty($ctaData['status'])): $c=$ctaData['content']; ?>
  <section class="cta section" id="about"><p class="eyebrow"><?=$esc($c['smalltitle']??'MAKE IT YOURS')?></p><h2><?=$esc($c['bigtitle']??'Ready for your next stay?')?></h2><a class="book-button" href="<?=html_escape($c['link']??base_url('booknow'))?>">Book your stay →</a></section>
  <?php endif; ?>
</main>

<?php $this->load->view('website/partials/footer'); ?>

<script src="<?=base_url('js/main.js')?>"></script>
</body>
</html>
