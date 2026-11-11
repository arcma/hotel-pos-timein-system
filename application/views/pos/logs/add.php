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
	<h1 class="mt-3">Add Category</h1>

	<div class="list_back mt-3">
		<a href="<?=base_url('pos/categories')?>" class="btn btn-primary btn-sm">
			<i class="fa-solid fa-less-than"></i>&nbsp;
			Back to List
		</a>
	</div>
	
	<form class="mt-3" action="<?=base_url('pos/categories/do_add')?>" method="POST">
		<div class="mb-3">
			<label for="input_name" class="form-label">Name</label>
			<input type="text" class="form-control" id="input_name" name="name" placeholder="Enter category name" value="" required>
		</div>
		<div class="mb-3">
			<label for="input_sort_order" class="form-label">Sort order</label>
			<input type="number" class="form-control" id="input_sort_order" name="order" placeholder="Enter sort order" value="" required>
		</div>

		
		<button type="submit" class="btn btn-primary">Save</button>
	</form>
</div>