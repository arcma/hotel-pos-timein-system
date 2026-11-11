<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Print Order</title>
		<meta charset="UTF-8"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<style>
			*{
				font-size: 10px!important;
			}
			body{
				
				font-family: serif;
				margin: 0px;
				padding: 0px;
			}
		</style>
    </head>
	<body>
<script type="text/javascript"> 
      window.print();
</script>
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
	.bg_red td{
		background-color: #efc5ca;
	}
	.export_button{
		width: 200px;
		text-align: center;
	}
	th{
		font-weight: 600;
	}
</style>
<div class="xtable_content xcontainer-fluid"  style="width: 200px; ">
	<div class="row mt-5">
		<div style="text-align: center; font-weight: 600; margin-top: 5px;" >Print Order (kitchen)</div>
		<div style="text-align: center; margin-top: 2px;" ><?=strtoupper($order->tbName)?></div>
		<div style="text-align: center; margin-top: 2px;" >	<?= date("H:i:s A",strtotime($order->ctTimeOrder))?><div>
		<table class="table table-striped" style="width: 200px;" >
			<thead>
				<tr>
					<!--th scope="col" style="width: 50px; text-align: center;">#</th-->
					<th scope="col" style="text-align: left;">Name</th>
					<th scope="col" style="text-align: right;">Qty</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="2">
						--------------------------------
					</td>
				</tr>
				<tr>
					<td><?=$order->prodName?></td>
					<td align="right">x<?=$order->ctQty?></td>
				</tr>
				
				
				<tr>
					<td colspan="2">
						--------------------------------
					</td>
				</tr>

			</tbody>
	

		</table>
	</div>
</div>

<body>

</html>