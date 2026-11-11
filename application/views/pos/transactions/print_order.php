<head>

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
 </style>

</head>
<?php 
	
	
?>
<body  onload="PrintDiv()" style="width: 155px;">
	<table id="divToPrint" style="width: 155px;">	
	<tr>
		<td>
			<table style="width: 155px;">
				<tr>
					<td align="center">
						<div style="font-family: cursive; font-size: 24px; margin-bottom: 3px;">Stone Haven</div>
					</td>
				</tr>
				<tr>
					<td align="center" style="width: 155px;">
						<div style="font-family: cursive; font-weight: 400; font-size: 12px; text-align: center; width: 155px;">
							Brgy Igmaya-an 6117, <br>
							Don Salvador <br>
							Benedicto Western <br>
							Visayas, Philippines
						</div>
					</td>
				</tr>
				<tr>
					<td align="center" style="width: 155px;">
						<div style="font-family: cursive; font-weight: 400; font-size: 12px; margin-top: 5px;">
							Tel# 0907 606 3295
						</div>
					</td>
				</tr>
				<tr> 
					<td align="right" colspan="2" style='font-size:12px; font-family: cursive; text-align: center;' >
					<?=date("M d, Y | h:i a",strtotime('now'))?>
					</td>
				</tr>
			</table>

			<table style="width: 155px;">
				<tr>
					<td style='font-size:12px; font-family: cursive;'>Table</td>
					<td style='font-size:12px; font-family: cursive; text-align: right;'>
						<?=$table_name?>
					</td>
				</tr>
				<tr>
					<td style='font-size:12px; font-family: cursive; '>item(s)</td>
					<td style='font-size:12px; font-family: cursive; text-align: right;'><?=count($cart)?></td>
				</tr>
			</table>

			<table border="0" style="width: 155px;">							
				<?php 
				foreach($cart as  $item){
						// echo "<pre>";
						// print_r($item);
						// echo "</pre>";
				?>
				
					<tr>
						<td>
							<span style='font-size:12px; font-family: cursive;'>
								(x<?=$item->ctQty?>)
							</span>
						</td>
						<td style='font-size:12px; font-family: cursive; text-align: right;'><span >
							&#8369; <?=number_format($item->ctPrice,2)?>
							</span>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<div style='font-size:12px; font-family: cursive;'>
								<?=$item->prodName?> 
							</div>
						
						</td>
					</tr>
				<?php } ?>
				
				<?php if($other_charges > 0 ){ ?>
					<tr>
						<td>
							<span style='font-size:12px; font-family: cursive;'>
								Other <br>charges
							</span>
						</td>
						<td style='font-size:12px; font-family: cursive; text-align: right;'><span >
							&#8369; <?=number_format($other_charges,2)?>
							</span>
						</td>
					</tr>
				<?php } ?>
			</table>		
			-----------------------------

			<table class="table" style="width: 155px;">
				<tr> 
					<td><span style='font-size:12px; font-family: cursive;'>Sales</span></td>
					<td align="right"><span style='font-size:12px; font-family: cursive;'>&#8369; <?=number_format($total_sales,2)?></span></td>
				</tr>
				<tr> 
					<td><span style='font-size:12px; font-family: cursive;'>Discount</span></td>
					<td align="right"><span style='font-size:12px; font-family: cursive;'>&#8369; <?=number_format($discount,2)?></span></td>
				</tr>
			</table>					
			-----------------------------

			<table class="table" style="width: 155px;">
				<tr> 
					<td><span style='font-size:12px; font-family: cursive;'>Amount Due</span></td>
					<td align="right"><span style='font-size:12px; font-family: cursive;'>&#8369; <?=number_format($total,2)?></span></td>
				</tr>
				<tr> 
					<td><span style='font-size:12px; font-family: cursive;'>Payment</span></td>
					<td align="right"><span style='font-size:12px; font-family: cursive;'>&#8369; <?=number_format($payment,2)?></span></td>
				</tr>
				
										
			</table>
			-----------------------------
			<table class="table" style="width: 155px;">
				<tr> 
					<td><span style='font-size:12px; font-family: cursive;'>Change</span></td>
					<td align="right"><span style='font-size:12px; font-family: cursive;'>&#8369; <?=number_format($change,2)?></span></td>
				</tr>
			</table>
			-----------------------------
			<table class="table" style="width: 155px;">
				<tr> 
					<td>
					<p style='font-size:10px; font-family: cursive; font-weight: 600; text-align: center;'>NOT AN OFFICIAL RECEIPT</p>
					</td>
				</tr>
			</table>
			
			-----------------------------

		</td>
	</tr>
	</table>
</body>

<?php
		$url = base_url('pos/transactions');
		echo "<meta http-equiv=\"refresh\" content=\"1;URL=$url\">";
	
?>