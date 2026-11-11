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
</style>
<?php 
// echo "<pre>";
// print_r($products);
// echo "</pre>";

?>
<div class="table_content container-fluid" >
	<h1 class="mt-3">Print Queue</h1>
	
	<?php /*
	<div class="mt-3 mb-3" id="clock">00:00:00</div>
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
	</script>
	*/ ?>
	
	<?php 
	// echo "<pre>";
	// print_r($unprinted);
	// echo "</pre>";
	
	?>
	
	<?php if(count($unprinted)){ ?>
	<div class="mt-3"></div>
	<h4>Unprinted</h4>
	<table class="table table-striped">
		<thead>
			<tr>
				<th scope="col">#</th>
				<th scope="col" >Name</th>
				<th scope="col" >Table</th>
				<th scope="col" >Type</th>
				<th scope="col" >Date Created</th>
				<th scope="col" >Action</th>
			</tr>
		</thead>
		<tbody>
		<?php 
			$counter = 0;
			foreach($unprinted as $unprint){
			$counter++;
		?>
			<tr>
				<td><?=$counter?></td>
				<td><?=$unprint->prtName?></td>
				<td><?=$unprint->tbName?></td>
				<td><?=$unprint->prtType?></td>
				<td><?=$unprint->prtDateCreated?></td>
				<td><div class="btn btn-primary">Print</div></td>
			</tr>
		
			<?php } ?>
		</tbody>
	</table>
	<?php } ?>

	<div class="mt-3"></div>
	<h4>Printed</h4>
	<table class="table table-striped">
		<thead>
			<tr>
				<th scope="col">#</th>
				<th scope="col" >Name</th>
				<th scope="col" >Table</th>
				<th scope="col" >Type</th>
				<th scope="col" >Date printed</th>
				<th scope="col" ></th>
			</tr>
		</thead>
		<tbody>
		<?php 
			$counter = 0;
			foreach($printed as $print){
			$counter++;
		?>
			<tr>
				<td><?=$counter?></td>
				<td><?=$print->prtName?></td>
				<td><?=$print->tbName?></td>
				<td><?=$print->prtType?></td>
				<td><?=$print->prtDatePrinted?></td>
				<td><div class="btn btn-primary">View</div></td>
			</tr>
		
			<?php } ?>
		</tbody>
	</table>
	
</div>

<script>

var counter_limit = 1000;
var counter = 0;
var delay = 2000; // 2sec
var checkStatus = setInterval(() => {
	$.post("<?=base_url('pos/ajax/checkUnprintedOnline')?>", function(result){
		counter++;
		console.log(result);
		if(result > 0){
			console.log('check: yes');
			clearInterval(checkStatus);

			// setTimeout(function(){
				window.location.href = '<?=base_url('pos/prints/do_print')?>';
			// }, 2000); 
			// location.reload();
		}else{
			console.log('check: no');
		}
		
		if(counter >= counter_limit ){
			clearInterval(checkStatus);
			location.reload();
		}
	});
	
}, delay );



</script>
