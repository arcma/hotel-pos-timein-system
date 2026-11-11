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
	<h1 class="mt-3">Edit Product</h1>

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
	<form class="mt-3" action="<?=base_url('pos/products/do_edit/'.$product->prodID)?>" method="POST">
		<div class="mb-3">
			<label for="" class="form-label">Category</label>
			<?php if($userdata->urRoleID == 2){ ?>
				<?php 
					$cat_value = "";
					$cat_name = "";
					foreach($categories as $category){
						if($product->prodCategory == $category->catID){
							$cat_value = $category->catID;
							$cat_name = $category->catName;
						}
					}
				?>
					<input type="hidden"name="category" value="<?=$cat_value?>">
					<input type="text" class="form-control" value="<?=$cat_name?>" readonly>
			<?php }else{ ?>
			<select name="category" required class="form-control" required >
				<option value="">Select category</option>
				<?php 
					foreach($categories as $category){
						$selected = ($product->prodCategory == $category->catID)? "selected" : "";
						echo '<option value="'.$category->catID.'" '.$selected.'>'.$category->catName.' </option>';
					}
				?>
			</select>
			<?php } ?>
			
		</div>
		<div class="mb-3">
			<label for="input_name" class="form-label">Product name</label>
			<input type="text" class="form-control" id="input_name" name="name" placeholder="Enter product name" value="<?=$product->prodName?>" required <?php if($userdata->urRoleID == 2){ echo 'readonly'; } ?>>
		</div>
		<div class="mb-3">
			<label for="input_price" class="form-label">Price</label>
			<input type="number" class="form-control" id="input_price" name="price" placeholder="Enter price" value="<?=$product->prodPrice?>" required <?php if($userdata->urRoleID == 2){ echo 'readonly'; } ?>>
		</div>
		<div class="mb-3">
			<label for="input_sort_order" class="form-label">Sort order</label>
			<input type="number" class="form-control" id="input_sort_order" name="order" placeholder="Enter sort order" value="<?=$product->prodSortOrder?>" required >
		</div>
		<div class="mb-3">
			<label for="input_inventory" class="form-label">Inventory</label>
			<input type="number" class="form-control" id="input_inventory" name="qty" placeholder="Enter inventory" value="<?=$product->prodQty?>" required>
		</div>
		<div class="mb-3">
			<label for="input_m_qty" class="form-label">Minimum quantity</label>
			<input type="number" class="form-control" id="input_m_qty" name="mqty" placeholder="Enter minimum quantity" value="<?=$product->prodMinQty?>" required>
		</div>

		
		<button type="submit" class="btn btn-primary">Update</button>
	</form>
</div>