<?php
$sections = isset($sections) && is_array($sections) ? $sections : array();
$getSection = function ($name, $defaultContent = array(), $defaultStatus = 1) use ($sections) {
    $row = isset($sections[$name]) ? $sections[$name] : null;
    $decoded = $row ? json_decode($row->homeContent, true) : null;
    return array(
        'content' => is_array($decoded) ? $decoded : $defaultContent,
        'status' => $row ? (int) $row->homeStatus : $defaultStatus,
        'modified' => $row && !empty($row->homeLastModified) ? $row->homeLastModified : 'Not saved yet'
    );
};
$e = function ($value) { return html_escape((string) $value); };
$banner = $getSection('homeBanner', array('slides' => array()), 1);
$slides = isset($banner['content']['slides']) && is_array($banner['content']['slides']) ? $banner['content']['slides'] : array();
if (!$slides) $slides = array(array('eyebrow'=>'','number'=>'','h1'=>'','description'=>'','image'=>'','status'=>1));
$intro = $getSection('homeIntro', array('top'=>'','title'=>'','description'=>''));
$block1 = $getSection('homeBlock1', array('top'=>'','title'=>'','description'=>'','image'=>''));
$block2 = $getSection('homeBlock2', array('top'=>'','title'=>'','description'=>'','image'=>''));
$amenities = $getSection('homeAmenities', array('title'=>'','items'=>array()));
$offers = $getSection('homeOffers', array('title'=>'','items'=>array()));
$cta = $getSection('homeCTA', array('smalltitle'=>'','bigtitle'=>'','link'=>''));
?>
<style>
.cms-home { padding: 18px; }
.cms-section { background:#fff; border:1px solid #d8dee4; border-radius:10px; margin:18px 0; overflow:hidden; }
.cms-section__header { background:#f4f6f8; padding:14px 18px; border-bottom:1px solid #d8dee4; display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap; }
.cms-section__header h2 { margin:0; font-size:20px; }
.cms-section__body { padding:18px; }
.cms-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:14px; }
.cms-field { margin-bottom:14px; }
.cms-field label { display:block; font-weight:600; margin-bottom:6px; }
.cms-field input[type=text], .cms-field input[type=url], .cms-field textarea { width:100%; border:1px solid #ced4da; border-radius:5px; padding:9px 10px; }
.cms-field textarea { min-height:105px; resize:vertical; }
.cms-slide { border:1px solid #d8dee4; border-radius:0 8px 8px 8px; padding:15px; margin:0 0 14px; background:#fbfcfd; }
.cms-slide-tabs { display:flex; gap:5px; flex-wrap:wrap; border-bottom:1px solid #d8dee4; margin:4px 0 0; }
.cms-slide-tab { border:1px solid #d8dee4; border-bottom:0; background:#f4f6f8; color:#333; border-radius:6px 6px 0 0; padding:9px 14px; cursor:pointer; }
.cms-slide-tab.active { background:#fbfcfd; font-weight:700; border-color:#0d6efd; color:#0d6efd; }
.cms-slide[hidden] { display:none !important; }
.cms-slide__top { display:flex; justify-content:space-between; align-items:center; gap:10px; margin-bottom:12px; }
.cms-slide__top h3 { font-size:17px; margin:0; }
.cms-preview { display:block; max-width:260px; max-height:120px; margin-top:8px; border-radius:5px; object-fit:cover; }
.cms-status { display:inline-flex; align-items:center; gap:7px; font-weight:600; }
.cms-meta { font-size:12px; color:#687078; }
.cms-item { border:1px solid #e0e4e8; border-radius:7px; padding:12px; margin:12px 0; }
.cms-actions { display:flex; justify-content:flex-end; margin-top:14px; gap:8px; }
@media(max-width:760px) { .cms-grid { grid-template-columns:1fr; } .cms-home { padding:8px; } .cms-section__body { padding:12px; } }
</style>

<div class="table_content container-fluid cms-home">
    <h1 class="mt-3 mb-2">Homepage CMS</h1>
    

    <?php if (!empty($flash_message)): ?>
        <div class="alert alert-<?= $e(isset($flash_type) ? $flash_type : 'info') ?> alert-dismissible fade show" role="alert">
            <?= $e($flash_message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- BANNER SLIDES -->
    <section class="cms-section">
        <div class="cms-section__header">
            <div><h2>Banner Slides <small class="text-muted">(homeBanner)</small></h2><div class="cms-meta">Section always enabled · Last modified: <?= $e($banner['modified']) ?></div></div>
            <button type="button" class="btn btn-outline-primary btn-sm" id="add-home-slide"><i class="fa fa-plus"></i> New slide</button>
        </div>
        <div class="cms-section__body">
            <form method="post" action="<?= base_url('cms/home/save_section') ?>" enctype="multipart/form-data">
                <input type="hidden" name="homeName" value="homeBanner">
                <div id="home-slide-tabs" class="cms-slide-tabs" role="tablist" aria-label="Banner slides">
                <?php foreach ($slides as $i => $slide): ?>
                    <button type="button" class="cms-slide-tab <?= $i === 0 ? 'active' : '' ?>" data-slide-tab="<?= (int)$i ?>" role="tab" aria-selected="<?= $i === 0 ? 'true' : 'false' ?>">Slide <?= (int)$i + 1 ?></button>
                <?php endforeach; ?>
                </div>
                <div id="home-slides">
                <?php foreach ($slides as $i => $slide): $slide = is_array($slide) ? $slide : array(); ?>
                    <div class="cms-slide" data-slide data-index="<?= (int)$i ?>" role="tabpanel" <?= $i === 0 ? '' : 'hidden' ?>>
                        <div class="cms-slide__top"><h3>Slide <span class="slide-number"><?= (int)$i + 1 ?></span> content</h3><button type="button" class="btn btn-outline-danger btn-sm remove-slide">Delete slide</button></div>
                        <div class="cms-grid">
                            <div class="cms-field"><label>Banner image</label><input class="form-control" type="file" name="slide_image_<?= (int)$i ?>" data-field="image" accept=".jpg,.jpeg,.png,.webp"><input type="hidden" data-field="existing_image" name="slides[<?= (int)$i ?>][existing_image]" value="<?= $e(isset($slide['image']) ? $slide['image'] : '') ?>">
                                <?php if (!empty($slide['image'])): ?><img class="cms-preview" src="<?= base_url('assets/uploads/homebanners/' . rawurlencode(basename($slide['image']))) ?>" alt="Current banner image"><div class="cms-meta">Current image: <?= $e($slide['image']) ?></div><?php endif; ?>
                            </div>
                            <div class="cms-field"><label>Eyebrow</label><input type="text" name="slides[<?= (int)$i ?>][eyebrow]" value="<?= $e(isset($slide['eyebrow']) ? $slide['eyebrow'] : '') ?>"></div>
                            <div class="cms-field"><label>Number</label><input type="text" name="slides[<?= (int)$i ?>][number]" value="<?= $e(isset($slide['number']) ? $slide['number'] : '') ?>"></div>
                            <div class="cms-field"><label>H1</label><input type="text" name="slides[<?= (int)$i ?>][h1]" value="<?= $e(isset($slide['h1']) ? $slide['h1'] : '') ?>"></div>
                        </div>
                        <div class="cms-field"><label>Description</label><textarea name="slides[<?= (int)$i ?>][description]"><?= $e(isset($slide['description']) ? $slide['description'] : '') ?></textarea></div>
                        <div class="cms-field"><label class="cms-status"><input type="checkbox" name="slides[<?= (int)$i ?>][status]" value="1" <?= !isset($slide['status']) || (int)$slide['status'] === 1 ? 'checked' : '' ?>> Enable this slide</label></div>
                    </div>
                <?php endforeach; ?>
                </div>
                <div class="cms-actions"><button type="submit" class="btn btn-primary">Save banner slides</button></div>
            </form>
        </div>
    </section>

    <!-- INTRO -->
    <section class="cms-section">
        <div class="cms-section__header"><div><h2>Introduction <small class="text-muted">(homeIntro)</small></h2><div class="cms-meta">Last modified: <?= $e($intro['modified']) ?></div></div></div>
        <div class="cms-section__body"><form method="post" action="<?= base_url('cms/home/save_section') ?>">
            <input type="hidden" name="homeName" value="homeIntro">
            <div class="cms-grid">
                <div class="cms-field"><label>Top</label><input type="text" name="content[top]" value="<?= $e(isset($intro['content']['top']) ? $intro['content']['top'] : '') ?>"></div>
                <div class="cms-field"><label>Title</label><input type="text" name="content[title]" value="<?= $e(isset($intro['content']['title']) ? $intro['content']['title'] : '') ?>"></div>
            </div>
            <div class="cms-field"><label>Description</label><textarea name="content[description]"><?= $e(isset($intro['content']['description']) ? $intro['content']['description'] : '') ?></textarea></div>
            <label class="cms-status"><input type="checkbox" name="homeStatus" value="1" <?= $intro['status'] ? 'checked' : '' ?>> Enable section</label>
            <div class="cms-actions"><button class="btn btn-primary" type="submit">Save introduction</button></div>
        </form></div>
    </section>

    <?php $blockOne = $block1['content']; $blockTwo = $block2['content']; ?>
    <section class="cms-section">
        <div class="cms-section__header"><div><h2>Content Blocks</h2><div class="cms-meta">Edit both homepage content blocks in one place.</div></div></div>
        <div class="cms-section__body">
            <form method="post" enctype="multipart/form-data" action="<?= base_url('cms/home/save_content_blocks') ?>" id="content-blocks-form">
                <div class="cms-slide-tabs" role="tablist" aria-label="Homepage content blocks">
                    <button type="button" class="cms-slide-tab active" data-block-tab="homeBlock1" role="tab" aria-selected="true">Top</button>
                    <button type="button" class="cms-slide-tab" data-block-tab="homeBlock2" role="tab" aria-selected="false">Bottom</button>
                </div>
                <?php foreach (array('homeBlock1' => array('data'=>$blockOne,'status'=>$block1['status'],'folder'=>'block1'), 'homeBlock2' => array('data'=>$blockTwo,'status'=>$block2['status'],'folder'=>'block2')) as $blockName => $blockData): $content = $blockData['data']; ?>
                <div class="cms-slide" data-block-panel="<?= $e($blockName) ?>" role="tabpanel" <?= $blockName === 'homeBlock1' ? '' : 'hidden' ?>>
                    <div class="cms-grid">
                        <div class="cms-field"><label>Section label</label><input type="text" name="blocks[<?= $e($blockName) ?>][top]" value="<?= $e(isset($content['top']) ? $content['top'] : '') ?>" placeholder="e.g. THE HOTEL"></div>
                        <div class="cms-field"><label>Title</label><input type="text" name="blocks[<?= $e($blockName) ?>][title]" value="<?= $e(isset($content['title']) ? $content['title'] : '') ?>"></div>
                    </div>
                    <div class="cms-field"><label>Description</label><textarea name="blocks[<?= $e($blockName) ?>][description]"><?= $e(isset($content['description']) ? $content['description'] : '') ?></textarea></div>
                    <div class="cms-grid">
                        <div class="cms-field"><label>Link</label><input type="text" name="blocks[<?= $e($blockName) ?>][link]" value="<?= $e(isset($content['link']) ? $content['link'] : '') ?>" placeholder="/rooms or https://example.com"></div>
                        <div class="cms-field"><label>Image</label><input class="form-control" type="file" name="<?= $e($blockName) ?>_image" accept=".jpg,.jpeg,.png,.webp">
                            <?php if (!empty($content['image'])): ?><img class="cms-preview" src="<?= base_url('assets/uploads/' . $blockData['folder'] . '/' . rawurlencode(basename($content['image']))) ?>" alt="Current section image"><?php endif; ?>
                        </div>
                    </div>
                    <label class="cms-status"><input type="checkbox" name="blocks[<?= $e($blockName) ?>][status]" value="1" <?= $blockData['status'] ? 'checked' : '' ?>> Enable this block</label>
                </div>
                <?php endforeach; ?>
                <div class="cms-actions"><button class="btn btn-primary" type="submit">Save content blocks</button></div>
            </form>
        </div>
    </section>

    <!-- AMENITIES: one tab per item -->
    <?php $amenityContent = $amenities['content']; $amenityItems = isset($amenityContent['items']) && is_array($amenityContent['items']) ? array_values($amenityContent['items']) : array(); if (!$amenityItems) $amenityItems = array(array('title'=>'','description'=>''), array('title'=>'','description'=>''), array('title'=>'','description'=>''), array('title'=>'','description'=>'')); ?>
    <section class="cms-section">
        <div class="cms-section__header">
            <div><h2>Amenities <small class="text-muted">(homeAmenities)</small></h2><div class="cms-meta">Last modified: <?= $e($amenities['modified']) ?></div></div>
            <button type="button" class="btn btn-outline-primary btn-sm" id="add-amenity"><i class="fa fa-plus"></i> New amenity</button>
        </div>
        <div class="cms-section__body">
            <form method="post" action="<?= base_url('cms/home/save_section') ?>" id="amenities-form">
                <input type="hidden" name="homeName" value="homeAmenities">
                <div class="cms-field"><label>Section title</label><input type="text" name="content[title]" value="<?= $e(isset($amenityContent['title']) ? $amenityContent['title'] : '') ?>"></div>
                <div id="amenity-tabs" class="cms-slide-tabs" role="tablist" aria-label="Amenities">
                    <?php foreach ($amenityItems as $i => $item): ?><button type="button" class="cms-slide-tab <?= $i === 0 ? 'active' : '' ?>" data-amenity-tab="<?= (int)$i ?>" role="tab" aria-selected="<?= $i === 0 ? 'true' : 'false' ?>">Amenity <?= (int)$i + 1 ?></button><?php endforeach; ?>
                </div>
                <div id="amenity-items">
                    <?php foreach ($amenityItems as $i => $item): $item = is_array($item) ? $item : array(); ?>
                    <div class="cms-slide" data-amenity data-index="<?= (int)$i ?>" role="tabpanel" <?= $i === 0 ? '' : 'hidden' ?>>
                        <div class="cms-slide__top"><h3>Amenity <span class="amenity-number"><?= (int)$i + 1 ?></span> content</h3><button type="button" class="btn btn-outline-danger btn-sm remove-amenity">Delete amenity</button></div>
                        <div class="cms-grid">
                            <div class="cms-field"><label>Title</label><input type="text" data-amenity-field="title" name="content[items][<?= (int)$i ?>][title]" value="<?= $e(isset($item['title']) ? $item['title'] : '') ?>"></div>
                            <div class="cms-field"><label>Description</label><textarea data-amenity-field="description" name="content[items][<?= (int)$i ?>][description]"><?= $e(isset($item['description']) ? $item['description'] : '') ?></textarea></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <label class="cms-status"><input type="checkbox" name="homeStatus" value="1" <?= $amenities['status'] ? 'checked' : '' ?>> Enable section</label>
                <div class="cms-actions"><button class="btn btn-primary" type="submit">Save Amenities</button></div>
            </form>
        </div>
    </section>

    <!-- OFFERS (kept in its existing layout) -->
    <?php $group = array('name'=>'homeOffers','label'=>'Offers','data'=>$offers,'count'=>2); $content = $group['data']['content']; $items = isset($content['items']) && is_array($content['items']) ? $content['items'] : array(); ?>
    <section class="cms-section">
        <div class="cms-section__header"><div><h2>Offers <small class="text-muted">(homeOffers)</small></h2><div class="cms-meta">Last modified: <?= $e($group['data']['modified']) ?></div></div></div>
        <div class="cms-section__body"><form method="post" action="<?= base_url('cms/home/save_section') ?>">
            <input type="hidden" name="homeName" value="homeOffers">
            <div class="cms-field"><label>Section title</label><input type="text" name="content[title]" value="<?= $e(isset($content['title']) ? $content['title'] : '') ?>"></div>
            <?php for ($i=0; $i<$group['count']; $i++): $item = isset($items[$i]) && is_array($items[$i]) ? $items[$i] : array(); ?>
            <div class="cms-item"><strong>Offers item <?= $i+1 ?></strong><div class="cms-grid mt-2">
                <div class="cms-field"><label>Title</label><input type="text" name="content[items][<?= $i ?>][title]" value="<?= $e(isset($item['title']) ? $item['title'] : '') ?>"></div>
                <div class="cms-field"><label>Description</label><textarea name="content[items][<?= $i ?>][description]"><?= $e(isset($item['description']) ? $item['description'] : '') ?></textarea></div>
            </div></div>
            <?php endfor; ?>
            <label class="cms-status"><input type="checkbox" name="homeStatus" value="1" <?= $group['data']['status'] ? 'checked' : '' ?>> Enable section</label>
            <div class="cms-actions"><button class="btn btn-primary" type="submit">Save Offers</button></div>
        </form></div>
    </section>

    <!-- CTA -->
    <section class="cms-section">
        <div class="cms-section__header"><div><h2>Call to Action <small class="text-muted">(homeCTA)</small></h2><div class="cms-meta">Last modified: <?= $e($cta['modified']) ?></div></div></div>
        <div class="cms-section__body"><form method="post" action="<?= base_url('cms/home/save_section') ?>">
            <input type="hidden" name="homeName" value="homeCTA">
            <div class="cms-grid">
                <div class="cms-field"><label>Small title</label><input type="text" name="content[smalltitle]" value="<?= $e(isset($cta['content']['smalltitle']) ? $cta['content']['smalltitle'] : '') ?>"></div>
                <div class="cms-field"><label>Big title</label><input type="text" name="content[bigtitle]" value="<?= $e(isset($cta['content']['bigtitle']) ? $cta['content']['bigtitle'] : '') ?>"></div>
            </div>
            <div class="cms-field"><label>CTA link</label><input type="text" name="content[link]" value="<?= $e(isset($cta['content']['link']) ? $cta['content']['link'] : '') ?>" placeholder="/contact or https://example.com"></div>
            <label class="cms-status"><input type="checkbox" name="homeStatus" value="1" <?= $cta['status'] ? 'checked' : '' ?>> Enable section</label>
            <div class="cms-actions"><button class="btn btn-primary" type="submit">Save CTA</button></div>
        </form></div>
    </section>
</div>

<template id="amenity-template">
    <div class="cms-slide" data-amenity role="tabpanel">
        <div class="cms-slide__top"><h3>Amenity <span class="amenity-number"></span> content</h3><button type="button" class="btn btn-outline-danger btn-sm remove-amenity">Delete amenity</button></div>
        <div class="cms-grid">
            <div class="cms-field"><label>Title</label><input type="text" data-amenity-field="title"></div>
            <div class="cms-field"><label>Description</label><textarea data-amenity-field="description"></textarea></div>
        </div>
    </div>
</template>
<script>
(function () {
    var container = document.getElementById('amenity-items');
    var tabs = document.getElementById('amenity-tabs');
    var addButton = document.getElementById('add-amenity');
    var template = document.getElementById('amenity-template');
    if (!container || !tabs || !addButton || !template) return;
    function activate(index) {
        var items = Array.prototype.slice.call(container.querySelectorAll('[data-amenity]'));
        var buttons = Array.prototype.slice.call(tabs.querySelectorAll('[data-amenity-tab]'));
        items.forEach(function (item, i) { item.hidden = i !== index; item.setAttribute('aria-hidden', i === index ? 'false' : 'true'); });
        buttons.forEach(function (button, i) { var active = i === index; button.classList.toggle('active', active); button.setAttribute('aria-selected', active ? 'true' : 'false'); button.tabIndex = active ? 0 : -1; });
    }
    function renumber(activeIndex) {
        var items = Array.prototype.slice.call(container.querySelectorAll('[data-amenity]'));
        tabs.innerHTML = '';
        items.forEach(function (item, index) {
            item.setAttribute('data-index', index);
            item.querySelector('.amenity-number').textContent = index + 1;
            item.querySelectorAll('[data-amenity-field]').forEach(function (field) { field.name = 'content[items][' + index + '][' + field.getAttribute('data-amenity-field') + ']'; });
            var tab = document.createElement('button'); tab.type = 'button'; tab.className = 'cms-slide-tab'; tab.setAttribute('data-amenity-tab', index); tab.setAttribute('role', 'tab'); tab.textContent = 'Amenity ' + (index + 1); tabs.appendChild(tab);
        });
        if (!items.length) { var empty = document.createElement('div'); empty.className = 'cms-meta'; empty.textContent = 'No amenities yet. Click “New amenity” to add one.'; tabs.appendChild(empty); return; }
        if (activeIndex < 0 || activeIndex >= items.length) activeIndex = items.length - 1;
        activate(activeIndex);
    }
    addButton.addEventListener('click', function () { container.appendChild(template.content.cloneNode(true)); renumber(container.querySelectorAll('[data-amenity]').length - 1); });
    tabs.addEventListener('click', function (event) { var tab = event.target.closest('[data-amenity-tab]'); if (tab) activate(parseInt(tab.getAttribute('data-amenity-tab'), 10)); });
    container.addEventListener('click', function (event) {
        var button = event.target.closest('.remove-amenity'); if (!button) return;
        var items = Array.prototype.slice.call(container.querySelectorAll('[data-amenity]')); var current = button.closest('[data-amenity]'); var index = items.indexOf(current);
        if (!window.confirm('Delete this amenity? It will be removed from the tabs and saved when you click Save Amenities.')) return;
        current.remove(); renumber(Math.min(index, items.length - 2));
    });
    renumber(0);
})();
</script>

<template id="home-slide-template">
    <div class="cms-slide" data-slide role="tabpanel">
        <div class="cms-slide__top"><h3>Slide <span class="slide-number"></span> content</h3><button type="button" class="btn btn-outline-danger btn-sm remove-slide">Delete slide</button></div>
        <div class="cms-grid">
            <div class="cms-field"><label>Banner image</label><input class="form-control" type="file" data-field="image" accept=".jpg,.jpeg,.png,.webp"><input type="hidden" data-field="existing_image" value=""></div>
            <div class="cms-field"><label>Eyebrow</label><input type="text" data-field="eyebrow"></div>
            <div class="cms-field"><label>Number</label><input type="text" data-field="number"></div>
            <div class="cms-field"><label>H1</label><input type="text" data-field="h1"></div>
        </div>
        <div class="cms-field"><label>Description</label><textarea data-field="description"></textarea></div>
        <div class="cms-field"><label class="cms-status"><input type="checkbox" data-field="status" value="1" checked> Enable this slide</label></div>
    </div>
</template>
<script>
(function () {
    var container = document.getElementById('home-slides');
    var tabs = document.getElementById('home-slide-tabs');
    var addButton = document.getElementById('add-home-slide');
    var template = document.getElementById('home-slide-template');
    if (!container || !tabs || !addButton || !template) return;

    function activateSlide(index) {
        var slides = Array.prototype.slice.call(container.querySelectorAll('[data-slide]'));
        var tabButtons = Array.prototype.slice.call(tabs.querySelectorAll('[data-slide-tab]'));
        slides.forEach(function (slide, i) {
            slide.hidden = i !== index;
            slide.setAttribute('aria-hidden', i === index ? 'false' : 'true');
        });
        tabButtons.forEach(function (tab, i) {
            var active = i === index;
            tab.classList.toggle('active', active);
            tab.setAttribute('aria-selected', active ? 'true' : 'false');
            tab.tabIndex = active ? 0 : -1;
        });
    }

    function renumberSlides(activeIndex) {
        var slides = Array.prototype.slice.call(container.querySelectorAll('[data-slide]'));
        tabs.innerHTML = '';
        slides.forEach(function (slide, index) {
            slide.setAttribute('data-index', index);
            slide.querySelector('.slide-number').textContent = index + 1;
            slide.querySelectorAll('[data-field]').forEach(function (field) {
                var key = field.getAttribute('data-field');
                field.name = key === 'image' ? 'slide_image_' + index : 'slides[' + index + '][' + key + ']';
            });
            var tab = document.createElement('button');
            tab.type = 'button';
            tab.className = 'cms-slide-tab';
            tab.setAttribute('data-slide-tab', index);
            tab.setAttribute('role', 'tab');
            tab.textContent = 'Slide ' + (index + 1);
            tabs.appendChild(tab);
        });
        if (!slides.length) return;
        if (activeIndex < 0 || activeIndex >= slides.length) activeIndex = slides.length - 1;
        activateSlide(activeIndex);
    }

    addButton.addEventListener('click', function () {
        var fragment = template.content.cloneNode(true);
        container.appendChild(fragment);
        renumberSlides(container.querySelectorAll('[data-slide]').length - 1);
    });

    tabs.addEventListener('click', function (event) {
        var tab = event.target.closest('[data-slide-tab]');
        if (!tab) return;
        activateSlide(parseInt(tab.getAttribute('data-slide-tab'), 10));
    });

    container.addEventListener('click', function (event) {
        var button = event.target.closest('.remove-slide');
        if (!button) return;
        var slides = Array.prototype.slice.call(container.querySelectorAll('[data-slide]'));
        var current = button.closest('[data-slide]');
        var currentIndex = slides.indexOf(current);
        if (slides.length <= 1) {
            alert('At least one banner slide is required. You can disable it if it should not display.');
            return;
        }
        if (!window.confirm('Delete this banner slide? It will be removed from the tabs and saved when you click Save banner slides.')) return;
        current.remove();
        renumberSlides(Math.min(currentIndex, slides.length - 2));
    });

    renumberSlides(0);
})();
</script>

<script>
(function(){
 var tabs=document.querySelectorAll('[data-block-tab]');
 var panels=document.querySelectorAll('[data-block-panel]');
 tabs.forEach(function(tab){tab.addEventListener('click',function(){
   tabs.forEach(function(t){t.classList.remove('active');t.setAttribute('aria-selected','false');});
   panels.forEach(function(p){p.hidden=true;});
   tab.classList.add('active');tab.setAttribute('aria-selected','true');
   var panel=document.querySelector('[data-block-panel="'+tab.getAttribute('data-block-tab')+'"]');if(panel)panel.hidden=false;
 });});
})();
</script>
