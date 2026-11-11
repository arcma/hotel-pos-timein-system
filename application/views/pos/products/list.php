<?php
	$action_td_width = 'style="width: 260px;"';
		// echo "<pre>";
		// print_r($products);
		// echo "</pre>";
?>
<style>
	.table_content{
		border: 1px solid #c2c2c2;
		background-color: #fff;
		border-radius: 10px;
		display: inline-block;
		padding: 10px 5%;
	}
	@media screen and (max-width: 800px) {
		.table_content{
			padding: 10px 5px;
		}
		.table_content h1{
			font-size: 25px;
		}
	}
	.disabled_td td{
		background-color: #deefff;
	}
	.disabled_td td:not(.no_blur) {
		opacity: .6;
	}
	.list_back{
		display: flex;
		justify-content: left;
	}
	.category_title{
		font-size: 20px;
		background-color: #D0D7DD;
		padding: 10px;
	}
	.soldout td{
		background-color: #f3c5bd;
	}
</style>
<div class="table_content container-fluid" >
	<h1 class="mt-3 mb-3">Products List</h1>
	
	<?php if (isset($_SESSION['error'])){ ?>
		<div class="container alert alert-danger mt-3">
			<?= $_SESSION['error'] ?>
		</div>
	<?php }else if (isset($_SESSION['success'])){ ?>
		<div class="container alert alert-success mt-3">
			<?= $_SESSION['success'] ?>
		</div>
	<?php } ?>
	<div class="list_back">
		<a href="<?=base_url('pos/products/add')?>" class="btn btn-primary btn-sm">
			<i class="fa-solid fa-plus"></i>
			Add product
		</a>
	</div>
	
	<?php foreach($product_list as $list){ ?>
	<div class="category_title mt-3"><?=$list['name']?></div>
	<table class="table table-striped">
	  <thead>
		<tr>
		  <th scope="col">#</th>
		  <th scope="col" >Name</th>
		  <th scope="col" class="text-center">Price</th>
		  <th scope="col" class="text-center">Sort order</th>
		  <th scope="col" class="text-center">Inventory</th>
		  <th scope="col" class="text-center">Minimum quantity </th>
		  <th scope="col">Status</th>
		  <th scope="col">Date last modified</th>
		  <th scope="col">Date created</th>
		  <th scope="col" <?=$action_td_width?>>Action</th>
		</tr>
	  </thead>
	  
	  <tbody>
		<?php 
			$counter = 0;
			foreach($list['products'] as $product){
				$counter++;
				$soldout = "";
				if($product->prodQty <= $product->prodMinQty){
					$soldout = "class='soldout'";
				}
		?>
			<tr <?php if($product->prodStatus != "1") echo "class='disabled_td'"; if($soldout){ echo $soldout; } ?>>
			  <td scope="row"><?=$counter?></td>
			  <td><?=$product->prodName?></td>
			  <td class="text-center">&#8369;<?=$product->prodPrice?></td>
			  <td class="text-center"><?=$product->prodSortOrder?></td>
			  <td class="text-center"><?=$product->prodQty?></td>
			  <td class="text-center"><?=$product->prodMinQty?></td>
			  
			  <td>
				<?php
					if($product->prodStatus == "1"){
						echo "Active";
					}else{
						echo "Disabled";
					}
				?>
			  </td>
			  <td><?=$product->prodDate_modified?></td>
			  <td><?=$product->prodDate_created?></td>
			  <td class="no_blur" <?=$action_td_width?>>
				<?php if($userdata && $userdata->urRoleID == 1 || $userdata->urRoleID == 1 ){  ?>
				<a href="<?=base_url('pos/products/edit/').$product->prodID?>" class="btn btn-primary btn-sm">
					<i class="fas fa-edit"></i>
					Edit
				</a>
				<?php } ?>
				
				<?php if($userdata->urRoleID == 2 ){  ?>
		
					<a href="<?=base_url('pos/products/increase_inventory/').$product->prodID?>" class="btn btn-primary btn-sm">
						<i class="fas fa-plus"></i>
						increase inventory
					</a>
				<?php } ?>
				
				<?php if($userdata && $userdata->urRoleID == 1){ ?>
					<?php if($product->prodStatus == "1"){ ?>
						<a href="<?=base_url('pos/products/disable/').$product->prodID?>" class="btn btn-warning btn-sm" onclick="return confirm('Confirm disable of <?=$product->prodName?>?')">
							<i class="fas fa-toggle-off"></i>
							Disable
						</a>
					<?php }else{ ?>
						<a href="<?=base_url('pos/products/enable/').$product->prodID?>" class="btn btn-success btn-sm">
						<i class="fas fa-toggle-on"></i>
							Enable
						</a>
					<?php } ?>
					<a href="<?=base_url('pos/products/delete/').$product->prodID?>" class="btn btn-danger btn-sm" onclick="return confirm('Confirm deletion of <?=$product->prodName?>?')">
						<i class="fa fa-trash" aria-hidden="true"></i>
						Delete
					</a>
				<?php } ?>
			  </td>

			</tr>
			
			<?php } ?>
	  </tbody>
	</table>
	<?php } ?>
</div>