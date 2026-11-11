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
		font-family: 'Calibri', sans-serif;
		font-weight:bold;
	}
 </style>

</head>
<?php 
	// echo "<pre>";
	// print_r($cart);
	// echo "</pre>";
	$table_name = "Unknown";
	foreach($cart as $item){
		$table_name = $item->tbName;
	}
?>
<body  onload="PrintDiv()" style="width: 155px;">
	<table id="divToPrint" style="width: 155px;">	
	<tr>
		<td>			
					<table style="width: 155px;">
						<tr>
							<td>
								<b>Order Details</b>
								<br>
							</td>
						</tr>
						<tr>
							<td align="right" colspan="2" style='font-size:13px; font-family: Calibri; text-align: center;' >
								<?=date("M d, Y | h:i a",strtotime('now'))?>
							</td>
						</tr>	
					
					<table style="width: 155px;">
						<tr>
							<td style='xfont-size:13px; font-family: Calibri;'>Table</td>
							<td style='xfont-size:13px; font-family: Calibri; text-align: right;'>
								<?=$table_name?>
							</td>
						</tr>
						<tr>
							<td style='xfont-size:13px; font-family: Calibri; '>item(s)</td>
							<td style='xfont-size:13px; font-family: Calibri; text-align: right;'><?=count($cart)?></td>
						</tr>
					</table>

					<table border="0" style="width: 155px;">							
						<?php 
							if(count($cart)){
								$total = 0;
								foreach($cart as  $item){
									// echo "<pre>";
									// print_r($item);
									// echo "</pre>";
								$total = $total + $item->ctSales;
						?>
							
								<tr>
									<td>
										<div style='font-size:13px; font-family: Calibri; margin-top: 0px;'>
											(x<?=$item->ctQty?>)  
										</div>
									</td>
									<td style='font-size:13px; font-family: Calibri; text-align: right;  margin-top: 5px;'><span >
										&#8369; <?=number_format($item->ctSales,2)?>
										</span>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<div style='font-size:13px; font-family: Calibri; xtext-align: center;'>
											<?=$item->prodName?>
										</d>
									</td>
								</tr>
							<?php
								}
							}else{ echo "Shopping Cart Is Empty"; } 
						?>		
									 
					</table>							
					-----------------------------

					<table class="table" style="width: 155px;">
						<tr> 
							<td><span style='font-size:13px; font-family: Calibri;'>Amount Due</span></td>
							<td align="right"><span style='font-size:13px; font-family: Calibri;'>&#8369; <?=number_format($total, 2)?></span></td>
						</tr>
					</table>
			
				-----------------------------
				<br />
				<p style='font-size:13px; font-family: Calibri;'><b>- NOT AN OFFICIAL RECEIPT -</b></p>
				-----------------------------
		</td>
	</tr>
	</table>
</body>

<?php
		//echo "<center><h3>Processing...</h3><img src='../images/loader/loader_3.gif'><center>";
		$url = base_url('pos/transactions/order/'.$table_id);
		echo "<meta http-equiv=\"refresh\" content=\"1;URL=$url\">";
	
?>