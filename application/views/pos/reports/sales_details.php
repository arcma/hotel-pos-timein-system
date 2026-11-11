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
				Sales Report
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
	<?php
	// echo "<pre>";
	// print_r($orders);
	// echo "</pre>";
	?>

		<table class="table table-striped mt-5">
			  <thead>
				<tr>
				  <th scope="col" class="text-center" style="width: 50px;">#</th>
				  <th scope="col">Customer</th>
				  <th scope="col">Table</th>
				  <th scope="col">Casher</th>
				  <th scope="col">Items Count</th>
				  <th scope="col">Price</th>
				  <th scope="col">Discount</th>
				  <th scope="col">Sales</th>
				  <th scope="col">Transaction Date</th>
				  <th scope="col">Cart View</th>
				</tr>
			  </thead>
				<tbody>
					<?php 
					$counter = 0;
					$total_sales = 0;
					$total_discount = 0;
					$total_count = 0;
					$total_price = 0;
					foreach($orders as $order){ 
						$counter++;
						$cart_items = json_decode($order->odrJsonCartItems);
						$total_sales = $total_sales + $order->odrSales;
						$total_discount = $total_discount + $order->odrDiscount;
						$total_price = $total_discount + $order->odrPrice;
						$total_count = $total_count + count($cart_items);
					?>
					<tr>
						<td><?=$counter?></td>
						<td><?=$order->odrCustomer?></td>
						<td><?=$order->tbName?></td>
						<td><?=$order->urName?> <?=$order->urLname?></td>
						<td><?=count($cart_items)?> Items</td>
						<td>&#8369; <?=number_format($order->odrPrice, 2)?></td>
						<td>&#8369; <?=number_format($order->odrDiscount, 2)?></td>
						<td>&#8369; <?=number_format($order->odrSales, 2)?></td>
						<td><?=$order->odrDate_created?></td>
						<!--td><?=date("d/m/Y", strtotime($order->urDate_created))?></td-->
						<td>
							<button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#cart_<?=$order->odrID?>">
							<i class="fa-solid fa-eye"></i>
							View</button>
						</td>
					</tr>
					
					<?php } ?>
					<tr>
						<td></td>
						<td colspan="3">Total</td>
						<td><?=$total_count?> Items</td>
						<td>&#8369; <?=number_format($total_price, 2)?></td>
						<td>&#8369; <?=number_format($total_discount, 2)?></td>
						<td>&#8369; <?=number_format($total_sales, 2)?></td>
						<td></td>
						<td></td>
					</tr>
				</tbody>
		</table>

</div>

<?php 
	foreach($orders as $order){ 
	$cart_items = json_decode($order->odrJsonCartItems);
	// $table = 
	 // echo "<pre>";
	// print_r($order);
	// echo "</pre>";
?>
<!-- Modal -->
<div class="modal fade" id="cart_<?=$order->odrID?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?=$order->tbName?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
		<table class="table table-striped">
		  <thead>
			<tr>
			  <th scope="col" class="text-center" style="width: 50px;">#</th>
			  <th scope="col">Product</th>
			  <th scope="col">Price</th>
			  <th scope="col">Qty</th>
			  <th scope="col">Price Total</th>
			  <th scope="col">Discount</th>
			  <th scope="col">Sales</th>
			  <th scope="col">Waiter</th>
			  <th scope="col">Order Date</th>
			</tr>
		  </thead>
			<tbody>
				<?php 
				$cart_count = 0;
				$cart_sales = 0;
				foreach($cart_items as $cart_item){
					$cart_count++;
					$cart_sales = $cart_sales + $cart_item->ctSales;
					?>
				<tr>
					<td><?=$cart_count?></td>
					<td><?=$cart_item->prodName?></td>
					<td>&#8369; <?=number_format($cart_item->prodPrice, 2)?></td>
					<td><?=$cart_item->ctQty?></td>
					<td>&#8369; <?=number_format($cart_item->ctPrice, 2)?></td>
					<td><?=$cart_item->disPercent != ""? number_format($cart_item->disPercent)."%" : ""?></td>
					<td>&#8369; <?=number_format($cart_item->ctSales, 2)?></td>
					<td><?=$cart_item->urName?> <?=$cart_item->urLname?></td>
					<td><?=$cart_item->ctDate_created?></td>
				
				</tr>
				<?php } ?>
				<tr>
					<td></td>
					<td colspan="5" align="left">Total</td>
					<td>&#8369; <?=number_format($cart_sales, 2)?></td>
					<td></td>
					<td></td>
				</tr>
			</tbody>
		</table>
        <?php
		
		// echo "<pre>";
	// print_r($cart_items);
	// echo "</pre>";
		?>
      </div>
    </div>
  </div>
</div>
<?php } ?>


<script type="text/javascript">
	$(function () {
		$('.datetimepicker').daterangepicker();
	});
</script>