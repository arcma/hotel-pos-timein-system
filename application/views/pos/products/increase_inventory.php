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
	<h1 class="mt-3">Casher Edit Product</h1>

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
	<form class="mt-3" action="<?=base_url('pos/products/do_increase_inventory/'.$product->prodID)?>" method="POST">
		<!--div class="mb-3">
			<label for="" class="form-label">Category</label>
			<?php 
				$cate_name = "";
				foreach($categories as $category){
					if($product->prodCategory == $category->catID){ $cate_name = $category->catName; }
				}
			?>
			<input type="text" class="form-control" value="<?=$cate_name?>" readonly disabled >
		</div-->
		<div class="mb-3">
			<label for="input_name" class="form-label">Product name</label>
			<input type="text" class="form-control" value="<?=$product->prodName?>" readonly disabled >
		</div>
		<!--div class="mb-3">
			<label for="input_price" class="form-label">Price</label>
			<input type="text" class="form-control" value="<?=$product->prodPrice?>" readonly disabled >
		</div>
		<div class="mb-3">
			<label for="input_sort_order" class="form-label">Sort order</label>
			<input type="number" class="form-control" id="input_sort_order" name="order" placeholder="Enter sort order" value="<?=$product->prodSortOrder?>" required>
		</div-->

		<div class="mb-3">
			<label for="input_inventory" class="form-label">Inventory</label>
			<div class="input-group">
				<div class="input-group-prepend">
					<span class="input-group-text" id="inputGroupPrepend2"><?=$product->prodQty?></span>
				</div>
				<input type="number" class="form-control" id="input_inventory" name="qty" placeholder="Add to inventory" value="" required>
			</div>
		
		</div>
		<!--div class="mb-3">
			<label for="input_m_qty" class="form-label">Minimum quantity</label>
			<input type="text" class="form-control" value="<?=$product->prodMinQty?>" readonly disabled >
		</div-->

		
		<button type="submit" class="btn btn-primary">Add inventory</button>
	</form>
</div>