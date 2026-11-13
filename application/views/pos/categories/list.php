<?php
	$action_td_width = 'style="width: 260px;"';
		// echo "<pre>";
		// print_r($categories);
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
</style>
<div class="table_content container-fluid" >
	<h1 class="mt-3">Categories List</h1>
	
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
		<a href="<?=base_url('pos/categories/add')?>" class="btn btn-primary btn-sm">
			<i class="fa-solid fa-plus"></i>
			Add Category
		</a>
	</div>
	<table class="table table-striped mt-3">
	  <thead>
		<tr>
		  <th scope="col">#</th>
		  <th scope="col" >Name</th>
		  <th scope="col" class="text-center">Printer</th>
		  <th scope="col" class="text-center">Sort order</th>
		  <th scope="col">Status</th>
		  <th scope="col">Date last modified</th>
		  <th scope="col">Date created</th>
		  <th scope="col" <?=$action_td_width?>>Action</th>
		</tr>
	  </thead>
	  
	  <tbody>
		<?php 
			$counter = 0;
			foreach($categories as $category){
				$counter++;
				
		?>
			<tr <?php if($category->catStatus != "1") echo "class='disabled_td'"; ?>>
			  <td scope="row"><?=$counter?></td>
			  <td><?=$category->catName?></td>
              <td class="text-center">
                <?php
                    $printerLabels = array(0 => 'None', 1 => 'Kitchen', 2 => 'Bar', 3 => 'Pantry');
                    $printerValue = isset($category->catPrinter) ? (int) $category->catPrinter : 0;
                    echo isset($printerLabels[$printerValue]) ? $printerLabels[$printerValue] : 'None';
                ?>
              </td>
			  <td class="text-center"><?=$category->catSortOrder?></td>
			  <td>
				<?php
					if($category->catStatus == "1"){
						echo "Active";
					}else{
						echo "Disabled";
					}
				?>
			  </td>
			  <td><?=$category->catDate_modified?></td>
			  <td><?=$category->catDate_created?></td>
			  <td class="no_blur" <?=$action_td_width?>>
				<a href="<?=base_url('pos/categories/edit/').$category->catID?>" class="btn btn-primary btn-sm">
					<i class="fas fa-edit"></i>
					Edit
				</a>
				<?php if($category->catStatus == "1"){ ?>
					<a href="<?=base_url('pos/categories/disable/').$category->catID?>" class="btn btn-warning btn-sm" onclick="return confirm('Confirm disable of <?=$category->catName?>?')">
						<i class="fas fa-toggle-off"></i>
						Disable
					</a>
				<?php }else{ ?>
					<a href="<?=base_url('pos/categories/enable/').$category->catID?>" class="btn btn-success btn-sm">
					<i class="fas fa-toggle-on"></i>
						Enable
					</a>
				<?php } ?>
				<a href="<?=base_url('pos/categories/delete/').$category->catID?>" class="btn btn-danger btn-sm" onclick="return confirm('Confirm deletion of <?=$category->catName?>?')">
					<i class="fa fa-trash" aria-hidden="true"></i>
					Delete
				</a>
			  </td>

			</tr>
			
			<?php } ?>
	  </tbody>
	</table>
</div>