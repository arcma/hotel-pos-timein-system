<?php $e=function($v){return html_escape((string)$v);}; $items=isset($items)&&is_array($items)?$items:array(); ?>
<style>
.nav-editor{padding:18px}.nav-editor-card{background:#fff;border:1px solid #d8dee4;border-radius:10px;padding:18px;margin-top:18px}
.nav-editor table{width:100%;border-collapse:collapse}.nav-editor th,.nav-editor td{padding:10px;border-bottom:1px solid #e5e7eb;text-align:left;vertical-align:middle}
.nav-editor input[type=text]{width:100%;padding:9px;border:1px solid #ced4da;border-radius:5px}
.nav-row-actions{display:flex;gap:8px;align-items:center;white-space:nowrap}.nav-enabled{display:inline-flex;align-items:center;gap:5px}
@media(max-width:700px){.nav-editor table,.nav-editor tbody,.nav-editor tr,.nav-editor td{display:block;width:100%}.nav-editor thead{display:none}.nav-editor tr{border:1px solid #ddd;margin:12px 0;padding:8px}.nav-editor td{border:0;padding:6px}.nav-editor td:before{content:attr(data-label);display:block;font-weight:700;margin-bottom:4px}}
</style>
<div class="table_content container-fluid nav-editor">
 <h1 class="mt-3 mb-2">Navigations</h1>
 <?php if(!empty($flash_message)): ?><div class="alert alert-<?= $e($flash_type?:'info') ?>"><?= $e($flash_message) ?></div><?php endif; ?>
 <form method="post" action="<?=base_url('cms/navigations/save')?>" id="nav-form">
  <div class="nav-editor-card">
   <div class="d-flex justify-content-between align-items-center mb-3"><h4 class="mb-0">Menu items</h4><button type="button" class="btn btn-outline-primary" id="add-nav-item"><i class="fa fa-plus"></i> Add item</button></div>
   <table><thead><tr><th style="width:35%">Name</th><th style="width:40%">Page</th><th>Actions</th></tr></thead><tbody id="nav-items">
   <?php foreach($items as $i=>$item): $item=is_array($item)?$item:array(); ?>
    <tr class="nav-item-row">
     <td data-label="Name"><input type="text" name="items[<?=$i?>][label]" value="<?=$e($item['label']??'')?>" placeholder="Menu label"></td>
     <td data-label="Page">
      <select name="items[<?=$i?>][link]" class="form-control page-link-select">
       <option value="">Select page</option>
       <?php $currentLink = (string)($item['link'] ?? ''); $knownPageLinks = array(); foreach ($pages as $page): $pageLink = '/pages/' . $page->pageSlug; $knownPageLinks[] = $pageLink; ?>
        <option value="<?=$e($pageLink)?>" <?=$currentLink === $pageLink ? 'selected' : ''?>><?=$e($pageLink)?></option>
       <?php endforeach; ?>
       <?php if ($currentLink !== '' && !in_array($currentLink, $knownPageLinks, true)): ?>
        <option value="<?=$e($currentLink)?>" selected>Current link: <?=$e($currentLink)?></option>
       <?php endif; ?>
      </select>
     </td>
     <td data-label="Actions"><div class="nav-row-actions"><label class="nav-enabled"><input type="checkbox" name="items[<?=$i?>][enabled]" value="1" <?=!empty($item['enabled'])?'checked':''?>> Enable</label><button type="button" class="btn btn-sm btn-outline-danger delete-nav-item">Delete</button></div></td>
    </tr>
   <?php endforeach; ?>
   </tbody></table>
   <div class="text-end mt-3"><button type="submit" class="btn btn-primary">Save navigation</button></div>
  </div>
 </form>
</div>
<script>
(function(){
 const body=document.getElementById('nav-items'),add=document.getElementById('add-nav-item');
 function renumber(){[...body.querySelectorAll('tr')].forEach((row,i)=>row.querySelectorAll('input, select').forEach(input=>{input.name=input.name.replace(/items\[\d+\]/,'items['+i+']');}));}
 const pageOptions = <?= json_encode(array_map(function($page){ return array('value' => '/pages/' . $page->pageSlug, 'label' => '/pages/' . $page->pageSlug); }, $pages), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;
 function addRow(){const i=body.querySelectorAll('tr').length,row=document.createElement('tr');row.className='nav-item-row';row.innerHTML='<td data-label="Name"><input type="text" name="items['+i+'][label]" placeholder="Menu label"></td><td data-label="Page"><select class="form-control page-link-select" name="items['+i+'][link]"><option value="">Select page</option>'+pageOptions.map(p=>'<option value="'+String(p.value).replace(/&/g,'&amp;').replace(/"/g,'&quot;')+'">'+String(p.label).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')+'</option>').join('')+'</select></td><td data-label="Actions"><div class="nav-row-actions"><label class="nav-enabled"><input type="checkbox" name="items['+i+'][enabled]" value="1" checked> Enable</label><button type="button" class="btn btn-sm btn-outline-danger delete-nav-item">Delete</button></div></td>';body.appendChild(row);}
 add.addEventListener('click',addRow);
 body.addEventListener('click',e=>{if(e.target.classList.contains('delete-nav-item')){e.target.closest('tr').remove();renumber();}});
})();
</script>
