<?php
	$action_td_width = 'style="width: 260px;"';
		// echo "<pre>";
		// print_r($categories);
		// echo "</pre>";
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
	<h1 class="mt-3">Reports</h1>
	
	<?php if (isset($_SESSION['error'])){ ?>
		<div class="container alert alert-danger mt-3">
			<?= $_SESSION['error'] ?>
		</div>
	<?php }else if (isset($_SESSION['success'])){ ?>
		<div class="container alert alert-success mt-3">
			<?= $_SESSION['success'] ?>
		</div>
	<?php } ?>
	

	<div class="row">
		<div class="col-md-6 mt-5">
			<form action="<?=base_url('pos/reports')?>" method="get">
			<div class="input-group mt-3">
				
					<span class="input-group-text">Number days:</span>
					<select name="days" class="form-select xform-control-sm" onchange="this.form.submit()">
						<option value="1" <?php if(!isset($_GET['days'])){ echo "selected"; }?>>1</option>
						<option value="5" <?php if(isset($_GET['days']) && $_GET['days'] == '5'){ echo "selected"; }?>>5</option>
						<option value="10" <?php if(isset($_GET['days']) && $_GET['days'] == '10'){ echo "selected"; }?>>10</option>
						<option value="15" <?php if(isset($_GET['days']) && $_GET['days'] == '15'){ echo "selected"; }?>>15</option>
						<option value="20" <?php if(isset($_GET['days']) && $_GET['days'] == '20'){ echo "selected"; }?>>20</option>

					</select>
				
			</div>
			</form>
			<canvas id="myChart" class="mt-3"></canvas>
		</div>
		<div class="col-md-6 mt-5">
			<div class="row">
				<div class="col-md-12 mt-3">
					<hr>
					<h3>Daily Reports</h3>
					
				</div>
				<div class="col-md-12 col-sm-12  mt-3">
					<div class="row">
						<div class="col-4">
							<form action="<?=base_url('pos/reports/inventory_details')?>" method="get">
								<button type="submit" class="btn btn-primary">Inventory Details</button>
							</form>
						</div>
						<div class="col-4">
							<form action="<?=base_url('pos/reports/daily_sales_report')?>" method="get">
								<button type="submit" class="btn btn-primary">Daily Sales Report</button>
							</form>
						</div>
						<!--div class="col-4">
							<form action="<?=base_url('pos/reports/inventory_details')?>" method="get">
								<button type="submit" class="btn btn-primary">Inventory Details</button>
							</form>
						</div-->
					</div>
				</div>
				
				<div class="col-md-12 mt-5">
					<hr>
					<h3> Reports</h3>
					
				</div>
				<div class="col-md-12 col-sm-6 mt-3">
					<h3>Sales Details</h3>
					<form action="<?=base_url('pos/reports/sales_details')?>" method="get">
						<div class="mb-3 col-lg-10 col-md-12">
							<input type="text" class="form-control datetimepicker" name="date" placeholder="Enter last name" value="" required>
						</div>
						<button type="submit" class="btn btn-primary">Search</button>
					</form>
				</div>
				<div class="col-md-12 col-sm-6  mt-3">
					<h3>Product Details</h3>
					<form action="<?=base_url('pos/reports/product_details')?>" method="get">
						<div class="mb-3 col-lg-10 col-md-12">
							<input type="text" class="form-control datetimepicker" name="date" placeholder="Enter last name" value="" required>
						</div>
						<button type="submit" class="btn btn-primary">Search</button>
					</form>
				</div>
				
				<!--div class="col-md-2 col-sm-6  mt-3">
					<h3>Logs</h3>
					<form action="<?=base_url('pos/reports/logs')?>" method="get">
						<div class="mb-3 col-lg-10 col-md-12 ">
							<input type="text" class="form-control datetimepicker" name="date" placeholder="Enter last name" value="" required>
						</div>
						<button type="submit" class="btn btn-primary">Search</button>
					</form>
				</div-->
			</div>
	
		</div>
	</div>

	
</div>

<?php 
// echo "<pre>";
// print_r($chart);
// echo "</pre>";
if(count($chart)){
	$cart_x = array();
	$cart_y = array();
	foreach($chart as $chart_date => $chart_sales){
		$cart_x[] = '"'.$chart_date.'"';
		$cart_y[] = $chart_sales;
	}
}
?>



<script type="text/javascript">
	$(function () {
		$('.datetimepicker').daterangepicker();
	});
	
	const ctx = document.getElementById('myChart');

	new Chart(ctx, {
		type: 'bar',
		data: {
		labels: [<?=implode(',',$cart_x)?>],
		// labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
		datasets: [{
		label: 'Sales',
		// data: [12, 19, 3, 5, 2, 3],
		data: [<?=implode(',',$cart_y)?>],
		borderWidth: 1
		}]
		},
		options: {
			scales: {
				y: {
					beginAtZero: true
				},
				 yAxes: [{
					ticks: {
						// Include a dollar sign in the ticks
						callback: function(value, index, values) {
							return '$' + value.toFixed(decimals);
						}
					}
				}]
			}
		}
	});
</script>

<script>
	$(document).ready(function(){
		$(".test_print").click(function(){
			window.print();
		});
	});
</script>