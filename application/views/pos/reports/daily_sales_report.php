<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.14.0/jquery-ui.js"></script>

<?php
	$action_td_width = 'style="width: 260px;"';
		// echo "<pre>";
		// print_r($categories);
		// echo "</pre>";
		
		$discount = 0;
		$total = 0;
		$items = array();
		foreach($orders as $order){
			$order_data = array();
			$order_data['name'] = $order->prodName;
			$order_data['discount'] = $order->prPrice - $order->prSales;
			$order_data['qty'] = $order->prQty;
			$order_data['sales'] = $order->prSales;
			
			$items[$order->prodID][] = $order_data;
		}
		
		// echo "<pre>";
		// // print_r($orders);
		// print_r($items);
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
	.export_button{
		width: 200px;
		text-align: center;
	}
</style>
<div class="table_content container-fluid" >
	<div class="row">
		<div class="col-12">
			<a href="<?=base_url('pos/reports') ?>" class="btn btn-light">
				<i class="fa-solid fa-arrow-left"></i>&nbsp;
				Back to reports list
			</a>
		</div>
		<div class="col-12">
			<h1 class="text-center">
				Daily sales report
			</h1>
			<h4 class="text-center">
				<input type="text" id="date_report" value="<?=date('Y/m/d', strtotime($date))?>">
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

	<div class="row mt-2">
		<div class="mt-2 text-center">
			<a class="btn btn-primary export_button" href="<?=base_url('pos/reports/print_daily_sales_report?date='.$date)?>">Print</a>
		</div>
		<table class="table table-striped" style="width: 400px; margin: 20px auto;">
			<thead>
				<tr>
					<th scope="col" style="width: 50px;">#</th>
					<th scope="col" >Name</th>
					<th scope="col" style="text-align: center;">Qty</th>
					<th scope="col" style="text-align: right;">Sales</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					$last_cat = "";
					$counter = 0;
					$total_discount = 0;
					$total_sales = 0;
					foreach($items as $item){ 
						$counter++;
						
						$item_name = "no name";
						$item_qty = 0;
						$item_sales = 0;
						$item_discount = 0;
						foreach($item as $item_data ){
							$item_name = $item_data['name'];
							$item_qty = $item_qty + $item_data['qty'];
							$item_sales = $item_sales + $item_data['sales'];
							$item_discount = $item_discount + $item_data['discount'];
						}
						$total_discount = $total_discount + $item_discount;
						$total_sales = $total_sales + $item_sales;
					?>
						</tr>
							<td><?=$counter?></td>
							<td><?=$item_name?></td>
							<td style="text-align: center;"><?=$item_qty?></td>
							<td style="text-align: right;">&#8369;<?=number_format($item_sales,2)?></td>
						</tr>
					
					<?php } ?>
				
					<tr>
						<td colspan="3">Item total:</td>
						<td style="text-align: right;" >&#8369;<?=number_format($total_sales,2)?></td>
					</tr>
					<tr>
						<td colspan="3">Discount: </td>
						<td style="text-align: right;">&#8369;<?=number_format($total_discount,2)?></td>
					</tr>
					<tr>
						<td colspan="3">Total Sales:</td>
						<td style="text-align: right;">&#8369;<?=number_format($total_sales - $total_discount,2)?></td>
					</tr>

			</tbody>
	

		</table>
	</div>
</div>

<script type="text/javascript">
	$(function () {
		$('#date_report').datepicker({
			dateFormat: "yy/mm/dd",
			onSelect: function(dateText) {
				dateText = dateText.replace(/\//g, "-");
				console.log(dateText);
				window.location.href = "<?=base_url('pos/reports/daily_sales_report?date=')?>"+dateText;
			}
			
		});
	});

</script>