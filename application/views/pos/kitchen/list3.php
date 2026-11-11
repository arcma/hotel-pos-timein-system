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
		border: 1px solid #c2c2c2;
		margin-top: 10px;
		text-align: center;
		padding: 20px 10px;
		border-radius: 10px;
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
	.order_pending{
		background-color: #FF6D6D!important;
	}
	.order_queue{
		background-color: #d6dbbf!important;
	}
	.order_inprogress{
		background-color: #FFCFA0!important;
	}
	.order_served{
		background-color: #97F230!important;
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

	
	<form action="<?=base_url('pos/settings/save_settings')?>" method="post">
	
	<div class="row">
		<?php 
			$counter = 0;
			foreach($products as $product){
			$counter++;
			
			// echo "<pre>";
			// print_r($product);
			// echo "</pre>";

			$status_class = "";
			$status_link = "";
			if($product->ctStatus == 1){ 
				$status_class = "order_pending";
				$status_link = base_url('pos/kitchen/update_cart_inprogress/'.$product->ctID);
			}else if($product->ctStatus == 2){ 
				$status_class = "order_inprogress";
				$status_link = base_url('pos/kitchen/update_cart_deliver/'.$product->ctID);
			}else if($product->ctStatus == 3){ 
				$status_class = "order_served";
				$status_link = "";
			}
		?>
		<div class="col-md-4">
			<?php if($status_link != ""){
				echo '<a href="'.$status_link.'">';
			}?>
				<div class="kitchen_item <?=$status_class?>">
					<div class="ki_name"><?=$product->prodName?></div>
					<div class="ki_time"><?=$product->ctTimeOrder? date('H:i A',strtotime($product->ctTimeOrder)) : "N/A"?></div>
					<div class="ki_status">Qty <?=$product->ctQty?></div>
					<div class="ki_status">
						<?php 
						if($product->ctStatus == 1){
							echo "Pending";
						}else if($product->ctStatus == 2){
							echo "In Progress";
						}else if($product->ctStatus == 3){
							echo "Ready to serve";
						}
						?>
					</div>
					
				
					<div class="ki_table"><?=$product->tbName?></div>
				</div>
			<?php if($status_link != ""){
				echo "</a>";
			}?>
		</div>
			<?php } ?>
	</div>
	
	
	
	<table class="table table-striped" style="display: none;">
		<thead>
			<tr>
				<th scope="col">#</th>
				<th scope="col" >Table</th>
				<th scope="col" >Product</th>
				<th scope="col" >Time Order</th>
				<th scope="col" >Time Serve</th>
				<th scope="col" >Status</th>
				<th scope="col" >Action</th>
			</tr>
		</thead>
		<tbody>
		<?php 
			$counter = 0;
			foreach($products as $product){
			$counter++;
		?>
		<tr>
			<td><?=$counter?></td>
			<td><?=$product->tbName?></td>
			<td><?=$product->prodName?></td>
			<td><?=$product->ctTimeOrder? date('H:i A',strtotime($product->ctTimeOrder)) : "N/A"?></td>
			<td><?=$product->ctTimeServe? date('H:i A',strtotime($product->ctTimeServe)) : "N/A"?></td>
			<td>
				<?php 
				if($product->ctStatus == 1){
					echo "Pending";
				}else if($product->ctStatus == 2){
					echo "In Progress";
				}else if($product->ctStatus == 3){
					echo "Ready to serve";
				}
				?>
			</td>
			<td>
				<?php if($product->ctStatus == 1){ ?>
					<a href="<?=base_url('pos/kitchen/update_cart_inprogress/'.$product->ctID)?>" class="btn btn-primary btn-sm">
						<i class="fa fa-paper-plane" aria-hidden="true"></i>
						In Progress
					</a>
				<?php }else if($product->ctStatus == 2){ ?>
					<a href="<?=base_url('pos/kitchen/update_cart_deliver/'.$product->ctID)?>" class="btn btn-primary btn-sm">
						<i class="fa fa-paper-plane" aria-hidden="true"></i>
						Done
					</a>
				<?php }else if($product->ctStatus == 3){ ?>
					<!--a href="<?=base_url('pos/kitchen/deliver_cart/'.$product->ctID)?>" class="btn btn-primary btn-sm">
						<i class="fa fa-paper-plane" aria-hidden="true"></i>
						In Progress
					</a-->
				<?php } ?>
			</td>
		</tr>
			<?php } ?>
		</tbody>
	</table>
	</form>
	
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