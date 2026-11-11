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
	.orders_panel{
		text-align: left;
		font-size: 25px;
		font-weight: 600;
	}
	.cart_total{
		font-weight: 600;
	}
	.cart_total .cart_total_td{
		text-align: right;
		color: #FF2B2B;
	}
	[readonly]{
		background-color: #F9F9F9;
	}
	.payment_panel input[type='number']{

	}
	.payment_panel input[type='text']{
		padding-right: 28px;
	}
	.text-bold{
		font-weight: 600;
	}
	.text-green{
		color: #299a0b;
	}
	.text-red{
		color: #FF2B2B;
	}
	
</style>



<div class="table_content container-fluid" >
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
	$counter = 0;
	$cart_total = 0;

	foreach($cart as $cart_data){
		$counter++;
		$cart_total += $cart_data->ctSales;
	}
	
	// echo "<pre>";
	// print_r($cart);
	// echo "</pre>";
?>
	
	<div class="text-start">
		
	</div>
	<div class="row">
		<div class="col-4">
			<a href="<?=base_url('pos/transactions/order/').$table_id ?>" class="btn btn-light">
			<i class="fa-solid fa-arrow-left"></i>&nbsp;
			Back to cart
		</a>
		</div>
		<div class="col-4">
			<h1 class="text-center">
			<?=$table->tbName?>
			</h1>
		</div>
	</div>
	

	<div class="row">
		<div class="col-md-6 payment_panel">
			<div class="orders_panel mt-3">Payment</div>
			<form action="<?=base_url('pos/transactions/do_checkout/'.$table_id)?>" method="post">
				<div class="input-group mt-3">
					<span class="input-group-text">Table</span>
					<input type="text" class="form-control text-end" value="<?=$table->tbName?>"   readonly>
				</div>
				<div class="input-group mt-2">
					<span class="input-group-text">Customer Name</span>
					<input type="text" name="customer_name" class="form-control text-end" value="" placeholder="Enter Name">
				</div>
				<div class="input-group mt-2">
					<span class="input-group-text">Payment Date</span>
					<input type="datetime-local" class="form-control text-end datetimepicker" name="payment_date" value="<?=date('Y-m-d H:i:s')?>" >
				</div>
				
				<div class="input-group mt-2">
					<span class="input-group-text">Amount Due</span>
					<input type="text" class="form-control text-end amount_due" value="<?=number_format($cart_total, 2)?>" readonly>
				</div>
				<div class="input-group mt-2">
					<span class="input-group-text">Other Charges</span>
					<input type="text" name="other_charges" class="form-control text-end other_charges numberOnly" value="" placeholder="0.00">
				</div>
				<div class="input-group mt-2">
					<span class="input-group-text">Additional Discount</span>
					<input type="text" name="add_discount" class="form-control text-end add_discount numberOnly" value=""  placeholder="0.00">
				</div>
				<div class="input-group mt-2">
					<span class="input-group-text">Total Amount Due</span>
					<input type="text" class="form-control text-end xtext-bold text-red total_amount_due" value="<?=number_format($cart_total, 2)?>" readonly>
				</div>
				<div class="input-group mt-2">
					<span class="input-group-text">Payment</span>
					<input type="text" name="payment" class="form-control text-end payment numberOnly" value="" autofocus="" placeholder="0.00" required>
				</div>
				<div class="input-group mt-2">
					<span class="input-group-text">Change</span>
					<input type="text" name="change" class="form-control text-end change text-red" value="<?=number_format($cart_total, 2)?>" placeholder="0.00" readonly >
				</div>

				<div class="text-end mt-3">
					<button type="submit" disabled class="btn btn-success submit_button">
						<i class="fas fa-shopping-cart"></i>
						Proceed
					</button>
					
					<?php if($order_id){ ?>
					<a href="<?=base_url('pos/transactions/print_order/'.$order_id)?>" class="btn btn-warning submit_button">
						<i class="fas fa-print"></i>
						Reprint
					</a>
					<?php } ?>
				</div>
			</form>
		</div>
		<div class="col-md-6">
			<div class="orders_panel mt-3">Cart preview</div>
			<table class="table table-bordered mt-3">
			  <thead>
				<tr>
				  <th scope="col" class="text-center" style="width: 50px;">#</th>
				  <th scope="col">Product</th>
				  <th scope="col" class="text-center" style="width: 120px;">Sales</th>
				</tr>
			  </thead>
			  <tbody>
				<?php
					$counter = 0;
					foreach($cart as $cart_data){
					$counter++;
				?>
				<tr>
				  <th scope="row" class="text-center"><?=$counter?></th>
				  <td><?=$cart_data->prodName?></td>
				  <td class="text-end">&#8369;<?=number_format($cart_data->ctSales, 2)?></td>
				</tr>
				
					<?php } ?>
				<tr class="cart_total">
					<td></td>
					<td>Total</td>
					<td class="cart_total_td" >&#8369;<?=number_format($cart_total, 2)?></td>
				</tr>
			  </tbody>
			</table>
		</div>
	</div>
</div>
<div class="test"></div>

<script>

$(document).ready(function(){
	$(".numberOnly").keyup(function(){
		// var val = $(this).val().replace(/[^0-9\,]/, '');
		// var conv = val.toLocaleString('en-US', { minimumFractionDigits: 2 });
		// $(this).val(val);
	}).blur(function(){
		
		var val = $(this).val().replace(/[^0-9.\,]/, '');
		val = val > 0? parseInt(val) : 0;
		var conv = "";
		if(val > 0){
			conv = val.toLocaleString('en-US', { minimumFractionDigits: 2 });
			$(this).val(conv);
		}
	}).focus(function(){
		var val = $(this).val().replace(/\,/g,'');
		
		// .replace(/ |,|\.|/g, "")
		
		// val = val > 0? parseInt(val) : 0;
		$(this).val(val);
		// var payment = $(".payment").val().replace(/\,/g,'');
		// payment = payment > 0? parseInt(payment) : 0;
	});
	
	$(".other_charges, .add_discount").blur(function(){
		var other_charges = $(".other_charges").val().replace(/\,/g,'');
		var add_discount = $(".add_discount").val().replace(/\,/g,'');
		var amount_due = $(".amount_due").val().replace(/\,/g,'');

		other_charges = other_charges > 0? parseInt(other_charges) : 0;
		add_discount = add_discount > 0? parseInt(add_discount) : 0;
		amount_due = amount_due > 0? parseInt(amount_due) : 0;

		// console.log(other_charges);
		// console.log(add_discount);
		// console.log(amount_due);

		$(".payment").val("");
		$(".change").removeClass("text-green").addClass('text-red');
		
		
		var total = other_charges + amount_due - add_discount;
		total = total.toLocaleString('en-US', { minimumFractionDigits: 2 });
		$(".total_amount_due").val(total);
		$(".change").val(total);
		
		$(".submit_button").attr("disabled",true);
	});
	
	$(document).on('keyup', '.payment' ,function(){
		updateChange();
	}).on('blur', '.payment' ,function(){
		updateChange();
	});
	
	function updateChange(){
		var total_amount_due = $(".total_amount_due").val().replace(/\,/g,'');
		total_amount_due = total_amount_due > 0? parseInt(total_amount_due) : 0;
		
		var payment = $(".payment").val().replace(/\,/g,'');
		payment = payment > 0? parseInt(payment) : 0;
		
		if($(".payment").val() == ""){
			total_amount_due = total_amount_due.toLocaleString('en-US', { minimumFractionDigits: 2 });
			$(".change").val(total_amount_due).removeClass("text-green").addClass('text-red');
			$(".submit_button").attr("disabled",true);
		}else{
			if(payment < total_amount_due){
				payment = total_amount_due - payment;
				payment = payment.toLocaleString('en-US', { minimumFractionDigits: 2 });
				$(".change").val(payment).removeClass("text-green").addClass('text-red');
				$(".submit_button").attr("disabled",true);
			}else{
				payment = payment - total_amount_due;
				payment = payment.toLocaleString('en-US', { minimumFractionDigits: 2 });
				$(".change").val(payment).addClass("text-green").removeClass('text-red');
				$(".submit_button").attr("disabled",false);
			}
		}
	}
});


</script>

<script>
      $(document).ready(function() {
			// $('.datetimepicker').datetimepicker();
			//$('.datetimepicker').datetimepicker({
				//format: 'DD-MM-YYYY LT'
			//});
      });
</script>