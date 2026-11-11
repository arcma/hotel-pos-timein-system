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
			padding: 10px;
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
	.table_grid{
	
	}
	.table_item{
		padding: 5% 10px;
		text-align: center;
		background-color: #d0e4f7;
		position: relative;
		border-radius: 20px;
		box-shadow: 5px 8px #c2c2c2;
		transition: all .3s ease-out;
		min-height: 150px;
		display: flex;
		justify-content: center;
		flex-direction: column;
	}
	.table_item:hover{
		cursor: pointer;
		background-color: #C3C9E5;
	}
	.table_item.active{
		background-color: #cdeb8b;
	}
	.table_item.active:hover{
		cursor: normal;
		background-color: inhirit;
	}
	
	.table_status{
		position: absolute;
		top: 0px;
		left: 0px;
		font-size: 12px;
		padding: 5px;
		border: 1px solid #878787;
		color: #000;
		background-color: #ccc;
		font-weight: 600;
		border-top-left-radius: 20px;
		border-bottom-right-radius: 20px;
		width: 40%;
	}
	.table_status.active{
		background-color: #F1DA36;
	}
	
	.table_number{
		font-size: 30px;
		font-weight: 600;
	}
	.table_name{
		font-size: 20px;
		line-height: 15px;
		font-weight: 500;
	}
	.table_amount{
		margin-top: 2px;
		font-size: 15px;
	}
	.table_list_count{
		position: absolute;
		bottom: 10px;
		right: 10px;
	}
</style>
<?php 
	// echo "<pre>";
	// print_r($tables);
	// echo "</pre>";

?>
<div class="table_content container-fluid" >
	<h1 class="mt-2">Transactions</h1>
	
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
		// print_r($cart[3]);
		// echo "</pre>";
	?>
	
	
	<?php
		$table_categories = array(1 => 'Restaurant', 2 => 'Cafe Shop', 3 => 'Hotel');
	?>
	<?php foreach ($table_categories as $category_id => $category_label): ?>
		<div class="table_category_section mt-4">
			<h2 class="h4 mb-2"><?=html_escape($category_label)?></h2>
			<hr class="mt-0 mb-3">
			<div class="table_grid row">
			<?php foreach ($tables as $table): ?>
				<?php if ((int)$table->tbCategory !== (int)$category_id) continue; ?>
				<?php
					$order_count = 0;
					$total_price = 0;
					if (isset($cart[$table->tbID])) {
						$order_count = count($cart[$table->tbID]);
						foreach ($cart[$table->tbID] as $cart_item) $total_price += $cart_item->ctSales;
					}
					$total_price = number_format($total_price, 2);
					$active = $order_count > 0 ? 'active' : '';
					$active_text = $order_count > 0 ? 'Occupied' : 'Vacant';
				?>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<a href="<?=base_url('pos/transactions/order/'.$table->tbID)?>">
						<div class="table_item mt-4 <?=$active?>">
							<div class="table_status <?=$active?>"><?=$active_text?></div>
							<div class="table_name"><?=html_escape($table->tbName)?></div>
							<div class="table_amount">&#8369; <?=$total_price?></div>
							<div class="table_list_count"><i class="fa-solid fa-list">&nbsp;&nbsp;<?=$order_count?></i></div>
						</div>
					</a>
				</div>
			<?php endforeach; ?>
			</div>
		</div>
	<?php endforeach; ?>
	
</div>

<script>


var counter_limit = 1000;
var counter = 0;
var delay = 2000; // 2sec
var checkStatus = setInterval(() => {
	$.post("<?=base_url('pos/ajax/checkTransactionItemStatus')?>", function(result){
		<?php
		if(isset($_GET['test'])){
		?>
			clearInterval(checkStatus);
			console.log(result +"==<?=$cartStatus?>");
			console.log('check: test');
		<?php }else{ ?>
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
		
		<?php } ?>
	});
	
}, delay );
</script>