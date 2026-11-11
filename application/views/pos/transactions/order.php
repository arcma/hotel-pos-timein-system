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
	.category_options{
		disply: block;
		border: 1px solid red;
		padding: 10px;
		margin: 5px;
	}
	.products_panel{
		text-align: left;
		font-size: 25px;
		font-weight: 600;
	}
	.orders_panel{
		text-align: left;
		font-size: 25px;
		font-weight: 600;
	}
	.product_list_item{
		border: 1px solid #000;
		padding: 10px;
		text-align: center;
		background-color: #d0e4f7;
		color: #000;
		font-weight: 600;
		border-radius: 5px;
	}
	.product_list_item:hover{
		cursor: pointer;
		background-color: #C3C9E5;
	}
	.product_list_item.soldout{
		background-color: #FF6D6D;
	}
	.cart_total{
		font-size: 20px;
		font-weight: 600;
		padding: 0px 5%;
	}
	.order_pending td{
		background-color: #FCF8D1!important;
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
	
</style>
<div class="table_content container-fluid" >
<?php 
if(isset($_GET['test'])){
	echo "<pre>";
	print_r($cart);
	echo "</pre>";
}

?>

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
		<div class="col-4">
			<a href="<?=base_url('pos/transactions') ?>" class="btn btn-light">
				<i class="fa-solid fa-arrow-left"></i>&nbsp;
				Back to table list
			</a>
		</div>
		<div class="col-4">
			<h1 class="text-center">
			<?=$table->tbName?>
			</h1>
		</div>
	</div>
	

	<div class="row">
		<div class="col-md-12">
			<div class="orders_panel mt-3">Cart</div>
			<table class="table xtable-striped">
			  <thead>
				<tr>
				  <th scope="col">#</th>
				  <th scope="col">Product</th>
				  <th scope="col">Price</th>
				  <th scope="col">Qty</th>
				  <th scope="col">Discount</th>
				  <th scope="col">Sales</th>
				  <th scope="col">Waiter</th>
				  <!--th scope="col">Date Ordered</th-->
				  <th scope="col" style="width: 110px;">Action</th>
				</tr>
			  </thead>
			  <tbody>
				<?php
					$counter = 0;
					$cart_total = 0;
					$order_kitchen_active = "disabled";
					$checkout_active = "";
					
					foreach($cart as $cart_data){
					$counter++;
					$cart_total += $cart_data->ctSales; 
					
					
						//$checkout_active = "disabled";
					
					$served =  "";
					if($cart_data->ctStatus == 0){
						$order_kitchen_active = "";
						$served =  "order_pending";
					}else if($cart_data->ctStatus == 1){
						$served =  "order_queue";
						
					}else if($cart_data->ctStatus == 2){
						
						$served =  "order_inprogress";
					}else if($cart_data->ctStatus == 3){
					
						$served =  "order_served";
					}else{
					
					}
						
					
					
				?>
				<tr class="<?=$served?>">
				  <td scope="row"><?=$counter?></td>
				  <td><?=$cart_data->prodName?></td>
				  <td>&#8369;<?=number_format($cart_data->prodPrice, 2)?></td>
				  <td><?=$cart_data->ctQty?></td>
				  <td>
					<form action="<?=base_url('pos/transactions/add_discount/'.$cart_data->ctID)?>" method="post">
						<select name="discount" class="form-control form-control-sm" onchange="this.form.submit()">
							<option value="0">None</option>
							<?php 
							foreach($discounts as $discount){ 
								$selected = $cart_data->ctDiscount == $discount->disID? "selected" : "";
								echo '<option value="'.$discount->disID.'" '.$selected.'>'.$discount->disName.' ('.$discount->disPercent.'%)</option>';
							}
							?>
						</select>
					</form>
				  </td>
				  <td>&#8369;<?=number_format($cart_data->ctSales, 2)?></td>
				  <td><?=$cart_data->urName?></td>
				  <!--td><?=$cart_data->ctDate_created?></td-->
				  <td>
					
					
					<?php if($this->session->Userdata('login')->urRoleID == 1 ){ ?>
						<a href="<?=base_url('pos/transactions/remove_cart/').$table_id."/".$cart_data->ctID ?>" class="btn btn-danger btn-sm" onclick="return confirm('Confirm removal from cart?')">
							<i class="fa fa-trash" aria-hidden="true"></i>
							Remove
						</a>
						<a href="<?=base_url('pos/transactions/deliver_order/').$table_id."/".$cart_data->ctID ?>" class="btn btn-primary btn-sm" xonclick="return confirm('Confirm removal from cart?')">
							<i class="fa fa-paper-plane" aria-hidden="true"></i>
							Served
						</a>
					<?php }else{ ?>
						<?php if($cart_data->ctStatus == 0 && $this->session->Userdata('login')->urRoleID == 2 ){ ?>
						<a href="<?=base_url('pos/transactions/remove_cart/').$table_id."/".$cart_data->ctID ?>" class="btn btn-danger btn-sm" onclick="return confirm('Confirm removal from cart?')">
							<i class="fa fa-trash" aria-hidden="true"></i>
							Remove
						</a>
						<?php } ?>
						
							
						<?php if($cart_data->ctStatus == 3){  ?>
						<a href="<?=base_url('pos/transactions/deliver_order/').$table_id."/".$cart_data->ctID ?>" class="btn btn-primary btn-sm" xonclick="return confirm('Confirm removal from cart?')">
							<i class="fa fa-paper-plane" aria-hidden="true"></i>
							Served
						</a>
						<?php } ?>
						
						<?php if($this->session->Userdata('login')->urRoleID == 3 && $cart_data->ctStatus == 0){ ?>
						<a href="<?=base_url('pos/transactions/remove_cart/').$table_id."/".$cart_data->ctID ?>" class="btn btn-danger btn-sm" onclick="return confirm('Confirm removal from cart?')">
							<i class="fa fa-trash" aria-hidden="true"></i>
							Remove
						</a>
					
						<?php } ?>
					<?php } ?>
				  </td>
				</tr>
				
					<?php } ?>
				<tr class="cart_total">
					<td></td>
					<td>Total</td>
					<td colspan="3"></td>
					<td>&#8369;<?=number_format($cart_total, 2)?></td>
					<td></td>
				</tr>
			  </tbody>
			</table>
			<div class="row cart_options">
				<div class="col-8">
					<div class="row">
						<?php if($this->session->Userdata('login')->urRoleID != 2){ ?>
						<div class="col-md-6 col-sm-4">
							<form action="<?=base_url('pos/transactions/change_table/'.$table_id)?>" method="post" id="change_table_form">
								<select name="table_change" class="form-select table_change">
								  <option selected value="">Change Table</option>
									<?php 
										foreach($tables as $table){
											if(!in_array($table->tbID, $occupied_table)){
									?>
										<option value="<?=$table->tbID?>"><?=$table->tbName?></option>
									
									<?php } ?>
									<?php } ?>
								</select>
							</form>
						</div>
						<?php } ?>
						<div class="col-md-6 col-sm-8">

							<?php if($this->session->Userdata('login')->urRoleID != 3 ){ ?>
								<a href="<?=base_url('pos/transactions/print_preview/').$table_id ?>" class="btn btn-primary" xtarget="_blank">
									<i class="fa fa-print" aria-hidden="true"></i>
									Order preview
								</a>
							<?php } ?>
						
							
							<?php //if($this->session->Userdata('login')->urRoleID != 2){ ?>
							<a href="<?=base_url('pos/transactions/order_kitchen/').$table_id ?>" class="btn btn-primary <?=$order_kitchen_active?>">
								<i class="fa-solid fa-receipt"></i>
								Order Kitchen
							</a>
							<?php //} ?>
						</div>
					</div>
				</div>
				<div class="col-4 text-end">
					<?php if($this->session->Userdata('login')->urRoleID != 3 ){ ?>
					<a href="<?=base_url('pos/transactions/checkout/').$table_id ?>" class="btn btn-warning <?=$checkout_active?>">
						<i class="fas fa-shopping-cart"></i>
						Checkout
					</a>
					<?php } ?>
				</div>
			</div>
		</div>
		
		
		
		
		<div class="col-md-12">
			<div class="products_panel mt-3">Choose Order</div>
			<div class="accordion mt-3" id="products_accordion">
				<?php foreach($product_list as $catID => $list){ ?>
					<div class="accordion-item">
						<h2 class="accordion-header" id="heading<?=$catID?>">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?=$catID?>" aria-expanded="false" aria-controls="collapse<?=$catID?>">
								<?=$list['name']?>
							</button>
						</h2>
						<div id="collapse<?=$catID?>" class="accordion-collapse collapse" aria-labelledby="heading<?=$catID?>" data-bs-parent="#products_accordion">
							<div class="accordion-body row">
								
							<?php foreach($list['products'] as $product){ ?>
							<?php
								// echo "<pre>";
								// print_r($product);
								// echo "</pre>";
								$soldout = "";
								if($product->prodQty <= $product->prodMinQty){
									$soldout = "soldout";
								}
							?>

							<div class="col-md-3 mt-2">
								<div class="product_list_item <?=$soldout?>" xdata-bs-toggle="modal" xdata-bs-target="#product_order">
									<input type="hidden" class="product_data" value='<?=json_encode($product)?>' />
									<?=$product->prodName?>
								</div>
							</div>
							<?php } ?>

							</div>
						</div>
					</div>
				<?php } ?>
			</div>

			
		</div>
		
	</div>
</div>


<!-- Button trigger modal -->

<style>
	.product_info{
		font-size: 25px;
	}
	.prod_name{
		font-weight: 600;
	}
	.order_qty{

	}
	.order_qty option{
		
	}
	.product_info_row{
		font-size: 18px;
		display: flex;
		justify-content: space-between;
		padding: 0px 5px;
	}
</style>
<!-- Modal -->
<div class="modal fade" id="product_order" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">My Order </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
	  <form action="<?=base_url('pos/transactions/add_cart/'.$table_id)?>" method="post">
		  <div class="modal-body">
				<div class="product_info row">
					<div class="col-12 prod_name"></div>
					<div class="col-6 text-end">
						
					</div>
				</div>
				<hr>
				<div class="product_info_row">
					<span>Available Qty</span>
					<div><span class="prod_qty"></span>/<span class="prod_mqty"></span></div>
				</div>
				<div class="product_info_row">
					<span>Price</span>
					<div>&#8369; <span class="cart_price"></span></div>
					<input type="hidden" class="cart_price_hidden">
				</div>

				<div class="input-group mt-2">
					<span class="input-group-text">Quantity</span>
					<select class="cart_qty form-select" name="qty" required></select>
                </div>
				<hr>
				<div class="product_info_row">
					<span>Total</span>
					<div style="width: 150px; text-align: right;">&#8369; <span class="cart_modal_total">00.00</span></div>
				</div>

					<input type="hidden" name="table" value="<?=$table_id?>" required /> 
					<input type="hidden" name="product" value="" id="cart_product" required /> 
			  </div>
		  <div class="modal-footer">
			<!--button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button-->
			<button type="submit" class="btn btn-primary">Add to cart</button>
		  </div>
	  </form>
    </div>
  </div>
</div>


<script>
	$(document).ready(function(){
		var myModal = new bootstrap.Modal(document.getElementById("product_order"), {});
		$(".product_list_item").click(function(){
			var product_data = $(this).find(".product_data").val();
			var product = JSON.parse(product_data);
			console.log(product);
			$('#cart_product').val(product.prodID);
			myModal.show();
			var price = parseInt(product.prodPrice);
			$(".cart_price").html(price.toLocaleString("en", { minimumFractionDigits: 2 }));
			$(".cart_price_hidden").val(price);
			$(".prod_name").html(product.prodName);
			$(".prod_qty").html(product.prodQty);
			$(".prod_mqty").html(product.prodMinQty);
			
			// var avl = parseInt(product.prodQty) - parseInt(product.prodMinQty);
			var avl = parseInt(product.prodQty);
			console.log(avl);
			var options = '<option value="">0</option>';
			avl = avl >= 250? 250 : avl;
			for(var count = 1; count <= avl; count++){
				options +='<option value="'+count+'">'+ count +'</option>';
			}
			$(".cart_qty").html(options);

		});
		
		$("#product_order").on("change",".cart_qty", function(){
			var price = parseInt($(".cart_price_hidden").val());
			var qty = parseInt($(".cart_qty").val());
			
			var total = price * qty;
			total = total.toLocaleString("en", { minimumFractionDigits: 2 });
			$(".cart_modal_total").html(total);
			
		});
		
		$(".table_change").change(function(){
			var table_id = $(this).val();
			var table_label = $(this).val();
			if(!confirm("Confirm to changing of table")) {
				$(this).val("");
				return false;
			}
			this.form.submit();
		});
		
		var counter_limit = 1000;
		var counter = 0;
		var delay = 2000; // 2sec
		var checkStatus = setInterval(() => {
			$.post("<?=base_url('pos/ajax/checkCartStatus')?>", {tableID: "<?=$table_id?>"}, function(result){
				counter++;
				console.log(result +"==<?=$cartStatus?>");
				if(result != "<?=$cartStatus?>"){
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
		
		
		
		
		
		
	});
</script>