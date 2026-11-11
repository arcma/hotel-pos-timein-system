<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Daily sales report</title>
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
<?php
	$action_td_width = 'style="width: 260px;"';
		// echo "<pre>";
		// print_r($categories);
		// echo "</pre>";
		
		$discount = 0;
		$total = 0;
		$items = array();
		foreach($orders as $order){
			$order_data = array();
			$order_data['name'] = $order->prodName;
			$order_data['discount'] = $order->prPrice - $order->prSales;
			$order_data['qty'] = $order->prQty;
			$order_data['sales'] = $order->prSales;
			
			$items[$order->prodID][] = $order_data;
		}
		
		// echo "<pre>";
		// // print_r($orders);
		// print_r($items);
		// echo "</pre>";
?>
<!--script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script-->

<script type="text/javascript"> 

// $(document).ready(function($) {
  // var ua = navigator.userAgent.toLowerCase();
  // var isAndroid = ua.indexOf("android") > -1; //&& ua.indexOf("mobile");

  // $('button.print').click(function(e) {
    // e.preventDefault();
    // if (isAndroid) {
		// alert('go 2');
      // // https://developers.google.com/cloud-print/docs/gadget
      // var gadget = new cloudprint.Gadget();
      // gadget.setPrintDocument("url", $('title').html(), window.location.href, "utf-8");
      // gadget.openPrintDialog();
	  
	   // window.print();
	  
    // } else {
      window.print();
    // }
    // return false;
  // });
// });

//window.print();
/*
export const printPdf = (() => {
  const isFirefox = /Gecko\/\d/.test(navigator.userAgent);
  const isAndroid = /Android/.test(navigator.userAgent);
  const firefoxDelay = 1000;
  let iframe;

  return function (url) {
    if (iframe) {
      iframe.parentNode.removeChild(iframe);
    }

    iframe = document.createElement("iframe");
    iframe.style.cssText =
      "width: 1px; height: 100px; position: fixed; left: 0; top: 0; opacity: 0; border-width: 0; margin: 0; padding: 0";

    const xhr = new XMLHttpRequest();
    try {
      xhr.responseType = "arraybuffer";
    } catch (e) {
      window.open(url, "_blank");
      return;
    }

    xhr.addEventListener("load", function () {
      if (xhr.status === 200 || xhr.status === 201) {
        const pdfBlob = new Blob([xhr.response], { type: "application/pdf" });
        const iframeUrl = URL.createObjectURL(pdfBlob);
        iframe.src = iframeUrl;

        iframe.addEventListener("load", function () {
          const openPrintPreview = () => {
            try {
              iframe.focus();
              iframe.contentWindow.print();
            } catch (error) {
              console.error("Print preview failed: " + error, error);
            } finally {
              URL.revokeObjectURL(iframeUrl);
            }
          };

          if (isFirefox) {
            window.setTimeout(openPrintPreview, firefoxDelay);
          } else if (isAndroid) {
            const newWindow = window.open("", "_blank");
            if (newWindow) {
              newWindow.document.write('print nisa');
              newWindow.document.close();
            } else {
              alert("Please allow popups for this website");
            }
          } else {
            openPrintPreview();
          }
        });

        document.body.appendChild(iframe);
      }
    });

    xhr.open("GET", url, true);
    xhr.send();
  };
})();
*/


	
</script>

<?php 
	$url = base_url('pos/reports/daily_sales_report');
	//echo "<meta http-equiv='refresh' content='1;URL={$url}'>";
?>
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
	<div class="row">
		<div class="col-4">
			<h1 style="text-align: center;">
				Daily sales report
			</h1>
			<div style="text-align: center;">
				<?=date('d/m/Y', strtotime($date))?>
			</div>
		</div>
	</div>


	<div class="row mt-5">
		<table class="table table-striped" style="width: 200px;" >
			<thead>
				<tr>
					<!--th scope="col" style="width: 50px; text-align: center;">#</th-->
					<th scope="col" style="text-align: left;">Name</th>
					<th scope="col" style="text-align: right;">Sales</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="2">
						--------------------------------
					</td>
				</tr>
				<?php 
					$last_cat = "";
					$counter = 0;
					$total_discount = 0;
					$total_sales = 0;
					foreach($items as $item){ 
						$counter++;
						
						$item_name = "no name";
						$item_qty = 0;
						$item_sales = 0;
						$item_discount = 0;
						foreach($item as $item_data ){
							$item_name = $item_data['name'];
							$item_qty = $item_qty + $item_data['qty'];
							$item_sales = $item_sales + $item_data['sales'];
							$item_discount = $item_discount + $item_data['discount'];
						}
						$total_discount = $total_discount + $item_discount;
						$total_sales = $total_sales + $item_sales;
					?>
						<tr>
							<td style="text-align: left;">X<?=$item_qty?></td>
							<td style="text-align: right;">&#8369;<?=number_format($item_sales,2)?></td>
						</tr>
						<tr>
							<!--td style="text-align: center;"><?=$counter?></td-->
							<td style="text-align: left;" colspan="2"><?=$item_name?></td>
						</tr>
					
					<?php } ?>
					<tr>
						<td colspan="2">
							--------------------------------
						</td>
					</tr>

					<tr>
						<td colspan="1">Item total:</td>
						<td style="text-align: right;" >&#8369;<?=number_format($total_sales,2)?></td>
					</tr>
					<tr>
						<td colspan="1">Discount: </td>
						<td style="text-align: right;">&#8369;<?=number_format($total_discount,2)?></td>
					</tr>
					<tr>
						<td colspan="1">Total Sales:</td>
						<td style="text-align: right;">&#8369;<?=number_format($total_sales - $total_discount,2)?></td>
					</tr>

			</tbody>
	

		</table>
	</div>
</div>

<body>

</html>