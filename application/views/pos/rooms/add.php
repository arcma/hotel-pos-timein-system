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
	<h1 class="mt-3">Add Room</h1>

	<div class="list_back mt-3">
		<a href="<?=base_url('pos/rooms')?>" class="btn btn-primary btn-sm">
			<i class="fa-solid fa-less-than"></i>&nbsp;
			Back to List
		</a>
	</div>
	
	<form class="mt-3" action="<?=base_url('pos/rooms/do_add')?>" method="POST">
		<div class="mb-3">
			<label for="input_title" class="form-label">Title</label>
			<input type="text" class="form-control" id="input_title" name="title" placeholder="Enter room name" value="" required>
		</div>
		<div class="mb-3">
			<label for="input_desc" class="form-label">Description</label>
			<input type="text" class="form-control" id="input_desc" name="desc" placeholder="Enter room descriptions" value="" required>
		</div>
		<div class="mb-3">
			<label for="input_info" class="form-label">Information</label>
			<input type="text" class="form-control" id="input_info" name="info" placeholder="Enter room information" value="" required>
		</div>
		<div class="mb-3">
			<label for="input_price" class="form-label">Price</label>
			<input type="number" class="form-control" id="input_price" name="price" placeholder="Enter room price" value="" required>
		</div>
		<div class="mb-3">
			<label for="input_name" class="form-label">Capacity</label>
			<select name="capacity" required class="form-control">
				<?php
					for($x = 1; $x<=20; $x++){
						echo '<option value="'.$x.'">'.$x.'</option>';
					}
				?>
			</select>
		</div>
		<div class="mb-3">
			<label for="" class="form-label">Status</label>
			<select name="status" required class="form-control">
				
				<option value="1" selected>Active</option>
				<option value="0">Inactive</option>
			</select>
		</div>

		
        <div class="mb-3">
            <label class="form-label" for="room-images">Images</label>
            <div class="input-group">
                <input type="file" id="room-images" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp" multiple disabled>
                <span class="input-group-btn"><button type="button" id="add-room-images" class="btn btn-info" disabled>Add image</button></span>
            </div>
            <small class="text-muted">Save the room first before uploading images.</small>
            <div id="room-image-saving" style="display:none;margin-top:8px"><i class="fa fa-spinner fa-spin"></i> Saving image...</div>
            <div id="room-image-message" role="alert" style="margin-top:8px"></div>
            <div id="room-image-list" class="room-image-list"></div>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
	</form>
</div>
<style>.room-image-list{margin-top:12px}.room-image-item{display:flex;align-items:center;gap:9px;flex-wrap:wrap;padding:9px 10px;border:1px solid #ddd;border-radius:5px;margin-bottom:7px;background:#fafafa}.room-image-name{flex:1;min-width:180px;overflow-wrap:anywhere}</style>
