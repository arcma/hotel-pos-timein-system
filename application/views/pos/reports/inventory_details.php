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
	.report_link{
		display: block;
	}
	.bg_red td{
		background-color: #efc5ca;
	}
</style>
<div class="table_content container-fluid" >
	<div class="row">
		<div class="col-4">
			<a href="<?=base_url('pos/reports') ?>" class="btn btn-light">
				<i class="fa-solid fa-arrow-left"></i>&nbsp;
				Back to reports list
			</a>
		</div>
		<div class="col-4">
			<h1 class="text-center">
				Inventory Report
			</h1>
			<h4 class="text-center">
				<?=$date?>
			</h4>
		</div>
	</div>
	
	<?php if (isset($_SESSION['error'])){ ?>
		<div class="container alert alert-danger mt-3">
			<?= $_SESSION['error'] ?>
		</div>
	<?php }else if (isset($_SESSION['success'])){ ?>
		<div class="container alert alert-success mt-3">
			<?= $_SESSION['success'] ?>
		</div>
	<?php } ?>

	<div class="row mt-5">
	
		<table class="table table-striped">
			<thead>
				<tr>
					<th scope="col" style="width: 50px;">#</th>
					<th scope="col" >Name</th>
					<th scope="col" style="text-align: center;">Inventory</th>
					<th scope="col" style="text-align: center;">Min Quantity</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					$last_cat = "";
					$counter = 0;
					
					foreach($products as $product){ 
					$counter++;
					$bg_red = $product->prodQty <= $product->prodMinQty? "bg_red" : "";
				?>
				
				<?php if($product->catName != $last_cat){ 
					$last_cat = $product->catName; ?>
					<tr>
						<td colspan="4"><b><?=$product->catName?></b></td>
					</tr>
				<?php } ?>
					<tr class="<?=$bg_red?>">
						<td><?=$counter?></td>
						<td><?=$product->prodName?></td>
						<td align="center"><?=number_format($product->prodQty)?></td>
						<td align="center"><?=number_format($product->prodMinQty)?></td>
					</tr>
				
					<?php } ?>
			</tbody>
	

		</table>
	</div>
</div>

<script type="text/javascript">
	$(function () {
		$('.datetimepicker').daterangepicker();
	});
</script>