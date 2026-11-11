<?php
	$action_td_width = 'style="width: 250px;"';
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
	#clock{
		text-align: center;
		font-size: 20px;
	}
	
	.kitchen_item{
		border-left: 1px solid #c2c2c2;
		border-right: 1px solid #c2c2c2;
		margin-top: 10px;
		text-align: center;
		xpadding: 20px 10px;
		xborder-radius: 10px;
		font-size: 20px;
		
	}
	.ki_name{
		font-size: 30px;
	}
	.xkitchen_item:hover{
		cursor: pointer;
		background-color: #4F85BB !important;
		color: #fff;
	}
	.order_pending td{
		background-color: #FF6D6D!important;
	}
	.order_queue td{
		background-color: #d6dbbf!important;
	}
	.order_inprogress td{
		background-color: #FFCFA0!important;
	}
	.order_served td{
		background-color: #97F230!important;
	}
	.btn{
		font-size: inherit;
	}
	.kitchen_card_top{
		display: flex;
		justify-content: space-between;
		width: 100%;
		background-color: #7db9e8; 
		padding: 5px 10px; 
		margin: 0px;
	}
</style>
<?php 
// echo "<pre>";
// print_r($products);
// echo "</pre>";


?>
<div class="table_content container-fluid" >
	<h1 class="mt-3">Kitchen Queue</h1>
	
	<div class="mt-3 mb-3" id="clock">00:00:00</div>
	
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
		<?php 
			$tables = array();
			foreach($products as $product){
				// $table_data[$product->tbID][] = $product->tbName;
				$tables[$product->tbID][] = $product;
				
			}

			foreach($tables as $table){
		?>
			<div class="col-md-6">
				<div class="kitchen_item">
					<?php 
						$table_name = "";
						$table_id = 0;
						foreach($table as $table_data){  
							$table_name = $table_data->tbName;
							$table_id = $table_data->ctTable;
						}
						
					?>
					<?php if(!isset($_GET['test'])){ ?>
						
						<?php
						// echo "<pre>";
						// print_r($table);
						// echo "</pre>";
						?>
						<div class="kitchen_card_top">
							<h3 style=""><?=$table_name?></h3>
						</div>
					<?php }else{ ?>
					<h3 style="background-color: #7db9e8; padding: 5px; margin: 0px;"><?=$table_name?></h3>
					<?php } ?>
			
					
					<table style="width: 100%" class="table table-striped">
						<tr>
							<td>Time Order</td>
							<td style="text-align: left;">Name</td>
							<td>Qty</td>
							<td>Waiter</td>
							<td>Status</td>
						</tr>
						
						<?php
							foreach($table as $table_data){ 
							
							if($table_data->ctStatus == 1){
								$table_data_status = "Pending";
							}else if($table_data->ctStatus == 2){
								$table_data_status = "In Progress";
							}else if($table_data->ctStatus == 3){
								$table_data_status = "Ready to serve";
							}
							
							$status_class = "";
							$status_link = "";
							if($table_data->ctStatus == 1){ 
								$status_class = "order_pending";
								$status_link = "<a class='btn btn-primary btn-sm' href='".base_url('pos/kitchen/update_cart_inprogress/'.$table_data->ctID)."'>Pending</a>";
							}else if($table_data->ctStatus == 2){ 
								$status_class = "order_inprogress";
								//$status_link = base_url('pos/kitchen/update_cart_deliver/'.$table_data->ctID);
								$status_link = "<a class='btn btn-warning btn-sm' href='".base_url('pos/kitchen/update_cart_deliver/'.$table_data->ctID)."'>In Progress</a>";
							}else if($table_data->ctStatus == 3){ 
								$status_class = "order_served";
								$status_link = "Ready to serve";
							}
							
							
						?>
						

						<tr class="x<?=$status_class?>">
							<td ><?=date('g:i a', strtotime($table_data->ctTimeOrder))?></td>
							<td style="text-align: left;"><?=$table_data->prodName?></td>
							<td><?=$table_data->ctQty?></td>
							<td><?=$table_data->urName?></td>
							
							<td>
								<?=$status_link?>
							</td>
		
						</tr>

						
						<?php } ?>
					</table>
				</div>
			</div>
			
		<?php } ?>
		<?php /*
		<table class="table table-striped">
			<thead>
				<tr>
					<th scope="col">#</th>
					<th scope="col">Item</th>
					<th scope="col">Qty</th>
					<th scope="col">Table</th>
					<th scope="col">Time</th>
					<th scope="col">Status</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$counter = 0;
				foreach($products as $product){ 
				$counter++;
					$status_link = "";
					if($product->ctStatus == 1){ 
						$status_class = "order_pending";
						$status_link = "<a class='btn btn-primary' href='".base_url('pos/kitchen/update_cart_inprogress/'.$product->ctID)."'>Pending</a>";
					}else if($product->ctStatus == 2){ 
						$status_class = "order_inprogress";
						//$status_link = base_url('pos/kitchen/update_cart_deliver/'.$table_data->ctID);
						$status_link = "<a class='btn btn-warning' href='".base_url('pos/kitchen/update_cart_deliver/'.$product->ctID)."'>In Progress</a>";
					}else if($product->ctStatus == 3){ 
						$status_class = "order_served";
						$status_link = "Ready to serve";
					}
				
				?>
				<tr>
					<td><?=$counter?></td>
					<td><?=$product->prodName?></td>
					<td><?=$product->ctQty?></td>
					<td><?=$product->tbName?></td>

					<td><?=date('g:i a', strtotime($product->ctTimeOrder))?></td>
					
					<td><?=$status_link?></td>
				</tr>	
				<?php } ?>
			</tbody>
		</table>
		*/ ?>
	
	
	
	
	</div>
</div>


<script>
const clock = document.getElementById('clock');
function updateClock(){
	let date = new Date();
    clock.innerHTML = date.toLocaleTimeString('en-US', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: true
    });
}


updateClock();
setInterval(() => {
    updateClock();
}, 1000);

var counter_limit = 1000;
var counter = 0;
var delay = 2000; // 2sec
var checkStatus = setInterval(() => {
	$.post("<?=base_url('pos/ajax/checkCartStatusKitchenOnline')?>", function(result){
		counter++;
		console.log(result +"==<?=$cartStatus?>");
		if(parseInt(result) != parseInt("<?=$cartStatus?>")){
			console.log('check: yes');
			clearInterval(checkStatus);
			location.reload();
		}else{
			console.log('check: no');
		}
		console.log(counter);
		if(counter >= counter_limit ){
			clearInterval(checkStatus);
			location.reload();
		}
	});
	
}, delay );


</script>