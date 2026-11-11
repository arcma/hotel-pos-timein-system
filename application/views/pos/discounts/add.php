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
	<h1 class="mt-3">Add Discount</h1>

	<div class="list_back mt-3">
		<a href="<?=base_url('pos/discounts')?>" class="btn btn-primary btn-sm">
			<i class="fa-solid fa-less-than"></i>&nbsp;
			Back to List
		</a>
	</div>
	
	<form class="mt-3" action="<?=base_url('pos/discounts/do_add')?>" method="POST">
		<div class="mb-3">
			<label for="input_name" class="form-label">Name</label>
			<input type="text" class="form-control" id="input_name" name="name" placeholder="Enter discount name" value="" required>
		</div>
		<div class="mb-3">
			<label for="" class="form-label">Percent</label>
			<select name="percent" required class="form-control">
				<option value="">Select discount</option>
				<?php
					for($x = 1; $x<=100; $x++){
						echo '<option value="'.$x.'">'.$x.' %</option>';
					}
				?>
			</select>
		</div>

		
		<button type="submit" class="btn btn-primary">Save</button>
	</form>
</div>