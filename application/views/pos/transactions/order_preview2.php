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
		font-family: 'Calibri', sans-serif;
		font-weight:bold;
	}
	*{
		font-size: 10px;
		font-family: serif;
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
<body  onload="PrintDiv()" style="width: 200px;">
	<table id="divToPrint" style="width: 200px;">	
	<tr>

		<td align="center" colspan="2">
			<b style="font-size: 20px;">Order Details</b>
		</td>
	</tr>
	<tr>
		<td align="center" colspan="2" >
			<?=date("M d, Y | h:i a",strtotime('now'))?>
		</td>
	</tr>	
	<tr>
		<td style='x'>Table</td>
		<td style='x text-align: right;'>
			<?=$table_name?>
		</td>
	</tr>
	<tr>
		<td style='x '>item(s)</td>
		<td style='x text-align: right;'><?=count($cart)?></td>
	</tr>
						
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
						<div style=' margin-top: 0px;'>
							(x<?=$item->ctQty?>)  
						</div>
					</td>
					<td style=' text-align: right;  margin-top: 5px;'><span >
						&#8369; <?=number_format($item->ctSales,2)?>
						</span>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<div style=' xtext-align: center;'>
							<?=$item->prodName?>
						</d>
					</td>
				</tr>
			<?php
				}
			}else{ echo "Shopping Cart Is Empty"; } 
		?>		
		<tr>
			<td colspan="2">
				--------------------------------
			</td>
		</tr>
		<tr> 
			<td><span style=''>Amount Due</span></td>
			<td align="right"><span style=''>&#8369; <?=number_format($total, 2)?></span></td>
		</tr>
		<tr>
			<td colspan="2">
				--------------------------------
			</td>
		</tr>	
		<tr>
			<td align="center" style="font-weight: 600;" colspan="2">
				- NOT AN OFFICIAL RECEIPT -
			</td>
		</td>
		<tr>
			<td colspan="2">
				--------------------------------
			</td>
		</tr>
	</tr>
	</table>
	
	<?php
		//echo "<center><h3>Processing...</h3><img src='../images/loader/loader_3.gif'><center>";
		$url = base_url('pos/transactions/order/'.$table_id);
	//	echo "<meta http-equiv=\"refresh\" content=\"1;URL=$url\">";
	
	?>
</body>



</html>