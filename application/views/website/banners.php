<style>
    .hotel-banner {
        position: relative;
        min-height: min(780px, 100vh);
        overflow: hidden;
        color: #fff;
        isolation: isolate;
    }

    .hotel-slide,
    .hotel-shade,
    .hotel-grid {
        position: absolute;
        inset: 0;
    }

    .hotel-slide {
        opacity: 0;
        background: center / cover no-repeat;
        transform: scale(1.06);
        transition: opacity .9s ease, transform 6s ease;
    }

    .hotel-slide.hotel-active {
        opacity: 1;
        transform: scale(1);
    }

    .hotel-shade {
        z-index: 1;
        background: linear-gradient(
            90deg,
            rgba(0,0,0,.76),
            rgba(0,0,0,.18) 70%,
            rgba(0,0,0,.03)
        );
    }

    .hotel-grid {
        z-index: 2;
        pointer-events: none;
        opacity: .15;
        background-image:
            linear-gradient(rgba(255,255,255,.65) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,.65) 1px, transparent 1px);
        background-size: 5vw 5vw;
        mask-image: linear-gradient(90deg, #000, transparent 80%);
    }

    .hotel-progress {
        position: absolute;
        z-index: 5;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: rgba(255,255,255,.3);
    }

    .hotel-progress span {
        display: block;
        width: 0;
        height: 100%;
        background: #fff;
    }

    .hotel-content {
        position: relative;
        z-index: 3;
        display: flex;
        min-height: min(780px, 100vh);
        align-items: end;
        padding: 8vw 7vw 11vw;
    }

    .hotel-caption {
        display: none;
        max-width: 760px;
        animation: hotel-rise .7s .12s both;
    }

    .hotel-caption.hotel-active {
        display: block;
    }

    .hotel-eyebrow {
        margin: 0 0 12px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .22em;
    }

    .hotel-number {
        margin: 0 0 18px;
        font-size: clamp(90px, 15vw, 220px);
        font-weight: 700;
        letter-spacing: -.09em;
        line-height: .7;
        color: rgba(255,255,255,.27);
    }

    .hotel-banner h1 {
        margin: 0;
        font: 400 clamp(44px, 7vw, 105px)/.92 Georgia, serif;
    }

    .hotel-caption > p:last-child {
        max-width: 430px;
        margin: 22px 0 0;
        font-size: 17px;
        line-height: 1.6;
    }

    .hotel-controls {
        position: absolute;
        z-index: 4;
        right: 7vw;
        bottom: 10vw;
        display: flex;
        gap: 8px;
    }

    .hotel-controls button {
        width: 52px;
        height: 52px;
        border: 1px solid rgba(255,255,255,.8);
        background: rgba(0,0,0,.16);
        color: #fff;
        font-size: 24px;
        cursor: pointer;
        transition: .2s;
    }

    .hotel-controls button:hover,
    .hotel-controls button:focus-visible {
        background: #fff;
        color: #111;
    }

    @keyframes hotel-progress {
        to {
            width: 100%;
        }
    }

    @keyframes hotel-rise {
        from {
            opacity: 0;
            transform: translateY(24px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 700px) {
        .hotel-banner,
        .hotel-content {
            min-height: 640px;
        }

        .hotel-content {
            padding: 70px 8vw 130px;
        }

        .hotel-grid {
            background-size: 12vw 12vw;
        }

        .hotel-controls {
            right: 8vw;
            bottom: 40px;
        }
    }
</style>

<?php
$cmsSlides = isset($cms_sections['homeBanner']['content']['slides']) && is_array($cms_sections['homeBanner']['content']['slides']) ? $cms_sections['homeBanner']['content']['slides'] : array();
$cmsSlides = array_values(array_filter($cmsSlides, function($slide){ return is_array($slide) && (!isset($slide['status']) || (int)$slide['status'] === 1); }));
if (!$cmsSlides) $cmsSlides = array(array('eyebrow'=>'BOUTIQUE HOTEL • SILAY','number'=>'01','h1'=>'Stays worth remembering.','description'=>'Thoughtful spaces, warm service, and a stay designed around you.','image'=>''));
?>
<section class="hotel-banner" aria-label="Featured experiences">
 <div class="hotel-progress" aria-hidden="true"><span></span></div>
 <?php foreach($cmsSlides as $i=>$slide): $image=!empty($slide['image']) ? base_url('assets/uploads/homebanners/'.rawurlencode(basename($slide['image']))) : ''; ?>
 <div class="hotel-slide <?= $i===0?'hotel-active':'' ?>" style="<?= $image ? 'background-image:url('.html_escape($image).')' : 'background:linear-gradient(120deg,#283c36,#9a8068)' ?>"></div>
 <?php endforeach; ?>
 <div class="hotel-shade" aria-hidden="true"></div><div class="hotel-grid" aria-hidden="true"></div>
 <div class="hotel-content">
 <?php foreach($cmsSlides as $i=>$slide): ?>
  <article class="hotel-caption <?= $i===0?'hotel-active':'' ?>">
   <?php if(!empty($slide['eyebrow'])): ?><p class="hotel-eyebrow"><?=html_escape($slide['eyebrow'])?></p><?php endif; ?>
   <?php if(!empty($slide['number'])): ?><p class="hotel-number"><?=html_escape($slide['number'])?></p><?php endif; ?>
   <h1><?=nl2br(html_escape(isset($slide['h1'])?$slide['h1']:''))?></h1>
   <?php if(!empty($slide['description'])): ?><p><?=nl2br(html_escape($slide['description']))?></p><?php endif; ?>
  </article>
 <?php endforeach; ?>
 </div>
 <?php if(count($cmsSlides)>1): ?><div class="hotel-controls"><button type="button" data-hotel-move="-1" aria-label="Previous slide">←</button><button type="button" data-hotel-move="1" aria-label="Next slide">→</button></div><?php endif; ?>
</section>
<script>
(function(){
 const root=document.querySelector('.hotel-banner'); if(!root)return;
 const slides=[...root.querySelectorAll('.hotel-slide')], captions=[...root.querySelectorAll('.hotel-caption')], bar=root.querySelector('.hotel-progress span');
 if(slides.length<2)return;
 const duration=6000; let current=0,timer;
 function reset(){bar.style.animation='none';void bar.offsetWidth;bar.style.animation='hotel-progress '+duration+'ms linear forwards';}
 function show(i){current=(i+slides.length)%slides.length;slides.forEach((x,n)=>x.classList.toggle('hotel-active',n===current));captions.forEach((x,n)=>x.classList.toggle('hotel-active',n===current));reset();}
 function play(){clearInterval(timer);timer=setInterval(()=>show(current+1),duration);}
 root.querySelectorAll('[data-hotel-move]').forEach(b=>b.addEventListener('click',()=>{show(current+Number(b.dataset.hotelMove));play();}));
 show(0);play();
})();
</script>
