<?php $e=function($v){return html_escape((string)$v);}; $items=isset($items)&&is_array($items)?$items:array(); ?>
<style>
.footer-editor{padding:18px}.footer-editor-card{background:#fff;border:1px solid #d8dee4;border-radius:10px;padding:18px;margin-top:18px}
.footer-editor table{width:100%;border-collapse:collapse}.footer-editor th,.footer-editor td{padding:10px;border-bottom:1px solid #e5e7eb;text-align:left;vertical-align:middle}
.footer-editor input[type=text],.footer-editor textarea{width:100%;padding:9px;border:1px solid #ced4da;border-radius:5px}.footer-row-actions{display:flex;gap:8px;align-items:center;white-space:nowrap}.footer-enabled{display:inline-flex;align-items:center;gap:5px}
@media(max-width:700px){.footer-editor table,.footer-editor tbody,.footer-editor tr,.footer-editor td{display:block;width:100%}.footer-editor thead{display:none}.footer-editor tr{border:1px solid #ddd;margin:12px 0;padding:8px}.footer-editor td{border:0;padding:6px}.footer-editor td:before{content:attr(data-label);display:block;font-weight:700;margin-bottom:4px}}
</style>
<div class="table_content container-fluid footer-editor">
 <h1 class="mt-3 mb-2">Footer</h1>
 <?php if(!empty($flash_message)): ?><div class="alert alert-<?= $e($flash_type?:'info') ?>"><?= $e($flash_message) ?></div><?php endif; ?>
 <form method="post" action="<?=base_url('cms/footer/save')?>" id="footer-form">
  <div class="footer-editor-card">
   <div class="mb-3"><label for="footer-description"><strong>Footer description</strong></label><textarea id="footer-description" name="description" rows="3" placeholder="Enter a short description for your footer"><?= $e($description ?? '') ?></textarea></div>
   <div class="d-flex justify-content-between align-items-center mb-3"><h4 class="mb-0">Footer links</h4><button type="button" class="btn btn-outline-primary" id="add-footer-item"><i class="fa fa-plus"></i> Add item</button></div>
   <table><thead><tr><th style="width:35%">Name</th><th style="width:40%">Link</th><th>Actions</th></tr></thead><tbody id="footer-items">
   <?php foreach($items as $i=>$item): $item=is_array($item)?$item:array(); ?>
    <tr class="footer-item-row">
     <td data-label="Name"><input type="text" name="items[<?=$i?>][label]" value="<?=$e($item['label']??'')?>" placeholder="Footer link label"></td>
     <td data-label="Link"><input type="text" name="items[<?=$i?>][link]" value="<?=$e($item['link']??'')?>" placeholder="/rooms or https://example.com"></td>
     <td data-label="Actions"><div class="footer-row-actions"><label class="footer-enabled"><input type="checkbox" name="items[<?=$i?>][enabled]" value="1" <?=!empty($item['enabled'])?'checked':''?>> Enable</label><button type="button" class="btn btn-sm btn-outline-danger delete-footer-item">Delete</button></div></td>
    </tr>
   <?php endforeach; ?>
   </tbody></table>
   <div class="text-end mt-3"><button type="submit" class="btn btn-primary">Save footer</button></div>
  </div>
 </form>
</div>
<script>
(function(){
 const body=document.getElementById('footer-items'),add=document.getElementById('add-footer-item');
 function renumber(){[...body.querySelectorAll('tr')].forEach((row,i)=>row.querySelectorAll('input').forEach(input=>{input.name=input.name.replace(/items\[\d+\]/,'items['+i+']');}));}
 function addRow(){const i=body.querySelectorAll('tr').length,row=document.createElement('tr');row.className='footer-item-row';row.innerHTML='<td data-label="Name"><input type="text" name="items['+i+'][label]" placeholder="Footer link label"></td><td data-label="Link"><input type="text" name="items['+i+'][link]" placeholder="/rooms or https://example.com"></td><td data-label="Actions"><div class="footer-row-actions"><label class="footer-enabled"><input type="checkbox" name="items['+i+'][enabled]" value="1" checked> Enable</label><button type="button" class="btn btn-sm btn-outline-danger delete-footer-item">Delete</button></div></td>';body.appendChild(row);}
 add.addEventListener('click',addRow);
 body.addEventListener('click',e=>{if(e.target.classList.contains('delete-footer-item')){e.target.closest('tr').remove();renumber();}});
})();
</script>
