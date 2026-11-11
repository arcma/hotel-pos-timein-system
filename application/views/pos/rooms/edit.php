<style>
	.table_content{
		border: 1px solid #c2c2c2;
		background-color: #fff;
		border-radius: 10px;
		display: inline-block;
		padding: 10px 5%;
	}
	.list_back{
		display: flex;
		justify-content: right;
	}
</style>
<div class="table_content container-fluid" >
	<h1 class="mt-3">Edit Room</h1>

	<div class="list_back mt-3">
		<a href="<?=base_url('pos/rooms')?>" class="btn btn-primary btn-sm">
			<i class="fa-solid fa-less-than"></i>&nbsp;
			Back to List
		</a>
	</div>
	
	<form class="mt-3" action="<?=base_url('pos/rooms/do_edit/'.$room->rmID)?>" method="POST">
		<div class="mb-3">
			<label for="input_title" class="form-label">Title</label>
			<input type="text" class="form-control" id="input_title" name="title" placeholder="Enter room name" value="<?=$room->rmTitle?>" required>
		</div>
		<div class="mb-3">
			<label for="input_desc" class="form-label">Description</label>
			<input type="text" class="form-control" id="input_desc" name="desc" placeholder="Enter room descriptions" value="<?=$room->rmDesc?>" required>
		</div>
		<div class="mb-3">
			<label for="input_info" class="form-label">Information</label>
			<input type="text" class="form-control" id="input_info" name="info" placeholder="Enter room information" value="<?=$room->rmInfo?>" required>
		</div>
		<div class="mb-3">
			<label for="input_price" class="form-label">Price</label>
			<input type="number" class="form-control" id="input_price" name="price" placeholder="Enter room price" value="<?=html_escape($room->rmPrice)?>" required>
		</div>
		<div class="mb-3">
			<label for="input_name" class="form-label">Capacity</label>
			<select name="capacity" required class="form-control">
				<?php
					for($x = 1; $x<=20; $x++){
						$selected = $room->rmCapacity == $x? "selected" : "";
						echo '<option value="'.$x.'" '.$selected.'>'.$x.'</option>';
					}
				?>
			</select>
		</div>
		<div class="mb-3">
			<label for="" class="form-label">Status</label>
			<select name="status" required class="form-control">
				<option value="1" <?=$room->rmStatus == 1 ? "selected" : "" ?> >Active</option>
				<option value="0" <?=$room->rmStatus == 0 ? "selected" : "" ?>>Disable</option>
			</select>
		</div>

		

        <div class="mb-3">
            <label class="form-label" for="room-images">Images</label>
            <div class="input-group">
                <input type="file" id="room-images" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp" multiple>
                <span class="input-group-btn"><button type="button" id="add-room-images" class="btn btn-info">Add image</button></span>
            </div>
            <small class="text-muted">Select multiple JPG, PNG, GIF, or WEBP files (maximum 8 MB each).</small>
            <div id="room-image-saving" style="display:none;margin-top:8px"><i class="fa fa-spinner fa-spin"></i> Saving image...</div>
            <div id="room-image-message" role="alert" style="margin-top:8px"></div>
            <div id="room-image-list" class="room-image-list">
            <?php if (!empty($room_images)): foreach ($room_images as $image): ?>
                <div class="room-image-item" id="room-image-<?= (int)$image->rmiID ?>" data-image-id="<?= (int)$image->rmiID ?>">
                    <span class="room-image-name"><?= html_escape(basename($image->rmiPath)) ?></span>
                    <span class="label <?= (int)$image->rmiStatus ? 'label-success' : 'label-default' ?> image-status-label"><?= (int)$image->rmiStatus ? 'Enabled' : 'Disabled' ?></span>
                    <button type="button" class="btn btn-xs <?= (int)$image->rmiStatus ? 'btn-warning' : 'btn-success' ?> toggle-room-image" data-status="<?= (int)$image->rmiStatus ?>"><?= (int)$image->rmiStatus ? 'Disable' : 'Enable' ?></button>
                    <button type="button" class="btn btn-xs btn-danger delete-room-image">Delete</button>
                </div>
            <?php endforeach; endif; ?>
            </div>
        </div>
		<button type="submit" class="btn btn-primary">Update</button>
	</form>
</div>
<style>
.room-image-list{margin-top:12px}.room-image-item{display:flex;align-items:center;gap:9px;flex-wrap:wrap;padding:9px 10px;border:1px solid #ddd;border-radius:5px;margin-bottom:7px;background:#fafafa}.room-image-name{flex:1;min-width:180px;overflow-wrap:anywhere}.room-image-item .btn{margin:0}#room-image-message .alert{margin-bottom:8px}
</style>
<script>
(function($){
    var roomId = <?= (int)$room->rmID ?>;
    var uploadUrl = <?= json_encode(site_url('pos/rooms/upload_images/'.$room->rmID)) ?>;
    var statusUrl = <?= json_encode(site_url('pos/rooms/image_status')) ?>;
    var deleteUrl = <?= json_encode(site_url('pos/rooms/delete_image')) ?>;
    function message(ok, text){ $('#room-image-message').html('<div class="alert alert-'+(ok?'success':'danger')+'">'+ $('<div>').text(text).html() +'</div>'); }
    function addImageRow(img){
        var $row=$('<div class="room-image-item"></div>').attr('id','room-image-'+img.id).attr('data-image-id',img.id);
        $('<span class="room-image-name"></span>').text(img.name).appendTo($row);
        $('<span class="label label-success image-status-label">Enabled</span>').appendTo($row);
        $('<button type="button" class="btn btn-xs btn-warning toggle-room-image" data-status="1">Disable</button>').appendTo($row);
        $('<button type="button" class="btn btn-xs btn-danger delete-room-image">Delete</button>').appendTo($row);
        $('#room-image-list').prepend($row);
    }
    $('#add-room-images').on('click',function(){
        var input=$('#room-images')[0]; if(!input.files || !input.files.length){message(false,'Please select one or more images first.');return;}
        var data=new FormData(); for(var i=0;i<input.files.length;i++) data.append('images[]',input.files[i]);
        var $button=$(this); $button.prop('disabled',true); $('#room-images').prop('disabled',true); $('#room-image-saving').show(); $('#room-image-message').empty();
        $.ajax({url:uploadUrl,type:'POST',data:data,processData:false,contentType:false,dataType:'json'})
        .done(function(res){ if(res.images){res.images.forEach(addImageRow);} message(!!res.success,res.message||'Upload complete.'); })
        .fail(function(xhr){var text='Upload failed. Please try again.';if(xhr.responseJSON&&xhr.responseJSON.message)text=xhr.responseJSON.message;message(false,text);})
        .always(function(){ $button.prop('disabled',false); $('#room-images').prop('disabled',false).val(''); $('#room-image-saving').hide(); });
    });
    $('#room-image-list').on('click','.toggle-room-image',function(){
        var $btn=$(this),$row=$btn.closest('.room-image-item'),id=$row.data('image-id'),newStatus=Number($btn.attr('data-status'))===1?0:1;
        $btn.prop('disabled',true);
        $.ajax({url:statusUrl+'/'+id,type:'POST',data:{status:newStatus},dataType:'json'}).done(function(res){
            if(!res.success){message(false,res.message||'Unable to update image.');return;}
            $btn.attr('data-status',newStatus).toggleClass('btn-warning',newStatus===1).toggleClass('btn-success',newStatus===0).text(newStatus?'Disable':'Enable');
            $row.find('.image-status-label').toggleClass('label-success',newStatus===1).toggleClass('label-default',newStatus===0).text(newStatus?'Enabled':'Disabled');
            message(true,res.message);
        }).fail(function(){message(false,'Unable to update image.');}).always(function(){$btn.prop('disabled',false);});
    });
    $('#room-image-list').on('click','.delete-room-image',function(){
        var $btn=$(this),$row=$btn.closest('.room-image-item'),id=$row.data('image-id');
        if(!window.confirm('Delete this image permanently?'))return;
        $btn.prop('disabled',true);
        $.ajax({url:deleteUrl+'/'+id,type:'POST',dataType:'json'}).done(function(res){if(res.success){$row.remove();message(true,res.message);}else{message(false,res.message||'Unable to delete image.');}}).fail(function(){message(false,'Unable to delete image.');}).always(function(){if($row.length)$btn.prop('disabled',false);});
    });
})(jQuery);
</script>
