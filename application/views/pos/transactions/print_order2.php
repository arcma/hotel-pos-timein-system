<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Print Order</title>
<script type="text/javascript">     
    function PrintDiv() {    
       // var divToPrint = document.getElementById('divToPrint');
       // var popupWin = window.open('', '_blank', 'width=auto,height=auto');
       // popupWin.document.open();
       // popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
        // popupWin.document.close();
		
		window.print();
            }
 </script>
 <style rel="stylesheet" type="text/css">
	.ctd {
		font-family: 'cursive', cursive;
		font-weight:bold;
	}
	*{
		font-size: 10px;
		font-family: serif;
	}
 </style>

</head>
<?php 
	
	
?>
<body  onload="PrintDiv()" style="width: 200px;">
	<table id="divToPrint" style="width: 200px;">	
	<tr>
		<!--tr>
			<td align="center" colspan="2">
				<div>Stone Haven</div>
			</td>
		</tr>
		<tr>
			<td align="center" colspan="2">
				Brgy Igmaya-an 6117, <br>
				Don Salvador <br>
				Benedicto Western <br>
				Visayas, Philippines
			</td>
		</tr>
		<tr>
			<td align="center" colspan="2">
				Tel# 0907 606 3295
			</td>
		</tr-->
		<tr> 
			<td align="center" colspan="2" >
			<?=date("M d, Y | h:i a",strtotime('now'))?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				  <br>
			</td>
		</tr>
		<tr>
			<td>Table</td>
			<td>
				<?=$table_name?>
			</td>
		</tr>
		<tr>
			<td>item(s)</td>
			<td><?=count($cart)?></td>
		</tr>
							
		<?php 
		foreach($cart as  $item){
				// echo "<pre>";
				// print_r($item);
				// echo "</pre>";
		?>
				
		<tr>
			<td>
				(x<?=$item->ctQty?>)
			</td>
			<td >
				&#8369; <?=number_format($item->ctPrice,2)?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<?=$item->prodName?> 
			</td>
		</tr>
		<?php } ?>
				
		<?php if($other_charges > 0 ){ ?>
			<tr>
				<td>
					Other <br>charges
				</td>
				<td>
					&#8369; <?=number_format($other_charges,2)?>
				</td>
			</tr>
		<?php } ?>
			<tr>
				<td colspan="2">
					--------------------------------
				</td>
			</tr>
			<tr> 
				<td>Sales</td>
				<td align="right">&#8369; <?=number_format($total_sales,2)?></td>
			</tr>
			<tr> 
				<td>>Discount</span></td>
				<td align="right">&#8369; <?=number_format($discount,2)?></td>
			</tr>
			<tr>
				<td colspan="2">
					--------------------------------
				</td>
			</tr>
			<tr> 
				<td>Amount Due</span></td>
				<td align="right">&#8369; <?=number_format($total,2)?></span></td>
			</tr>
			<tr> 
				<td>Payment</span></td>
				<td align="right">&#8369; <?=number_format($payment,2)?></span></td>
			</tr>
			<tr>
				<td colspan="2">
					--------------------------------
				</td>
			</tr>
			<tr> 
				<td>Change</span></td>
				<td align="right">&#8369; <?=number_format($change,2)?></span></td>
			</tr>
			<tr>
				<td colspan="2">
					--------------------------------
				</td>
			</tr>
			<tr> 
				<td align="right" colspan="2">
				- NOT AN OFFICIAL RECEIPT -
				</td>
			</tr>
			<tr>
				<td colspan="2">
					--------------------------------
				</td>
			</tr>
	</table>
</body>

<?php
		$url = base_url('pos/transactions');
		//echo "<meta http-equiv=\"refresh\" content=\"1;URL=$url\">";
	
?>
</html>