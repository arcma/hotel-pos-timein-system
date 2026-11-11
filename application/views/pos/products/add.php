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
	<h1 class="mt-3">Add Product</h1>

	<div class="list_back mt-3">
		<a href="<?=base_url('pos/products')?>" class="btn btn-primary btn-sm">
			<i class="fa-solid fa-less-than"></i>&nbsp;
			Back to List
		</a>
	</div>
	<?php 
// echo "<pre>";
// print_r($categories);
// echo "</pre>";

?>
	<form class="mt-3" action="<?=base_url('pos/products/do_add')?>" method="POST">
		<div class="mb-3">
			<label for="" class="form-label">Category</label>
			<select name="category" required class="form-control">
				<option value="">Select category</option>
				<?php 
					foreach($categories as $category){
						echo '<option value="'.$category->catID.'">'.$category->catName.' </option>';
					}
				?>
			</select>
		</div>
		<div class="mb-3">
			<label for="input_name" class="form-label">Product name</label>
			<input type="text" class="form-control" id="input_name" name="name" placeholder="Enter product name" value="" required>
		</div>
		<div class="mb-3">
			<label for="input_price" class="form-label">Price</label>
			<input type="number" class="form-control" id="input_price" name="price" placeholder="Enter price" value="" required>
		</div>
		<div class="mb-3">
			<label for="input_sort_order" class="form-label">Sort order</label>
			<input type="number" class="form-control" id="input_sort_order" name="order" placeholder="Enter sort order" value="" required>
		</div>
		<div class="mb-3">
			<label for="input_inventory" class="form-label">Inventory</label>
			<input type="number" class="form-control" id="input_inventory" name="qty" placeholder="Enter inventory" value="" required>
		</div>
		<div class="mb-3">
			<label for="input_m_qty" class="form-label">Minimum quantity</label>
			<input type="number" class="form-control" id="input_m_qty" name="mqty" placeholder="Enter minimum quantity" value="" required>
		</div>

		
		<button type="submit" class="btn btn-primary">Save</button>
	</form>
</div>