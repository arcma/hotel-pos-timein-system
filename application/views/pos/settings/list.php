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
</style>

<div class="table_content container-fluid" >
	<h1 class="mt-3">Settings</h1>
	
	<?php if (isset($_SESSION['error'])){ ?>
		<div class="container alert alert-danger mt-3">
			<?= $_SESSION['error'] ?>
		</div>
	<?php }else if (isset($_SESSION['success'])){ ?>
		<div class="container alert alert-success mt-3">
			<?= $_SESSION['success'] ?>
		</div>
	<?php } ?>
	
	<form action="<?=base_url('pos/settings/save_settings')?>" method="post">
	<table class="table table-striped">
		<thead>
			<tr>
				<th scope="col">#</th>
				<th scope="col" >Label</th>
				<th scope="col" >Description</th>
				<th scope="col" >Value</th>
			</tr>
		</thead>

		<tbody>
		<tr>
            <td>1</td>
            <td>Admin</td>
            <td>System admin password</td>
            <td>
                <input type="password" class="form-control" name="userAdmin" placeholder="Enter new admin password" value="" autocomplete="new-password">
                <small class="text-muted">Leave blank to keep the current password.</small>
            </td>
        </tr>
        <tr>
            <td>2</td>
            <td>Kitchen</td>
            <td>Kitchen login password</td>
            <td>
                <input type="password" class="form-control" name="userKitchen" placeholder="Enter new kitchen password" value="" autocomplete="new-password">
                <small class="text-muted">Leave blank to keep the current password.</small>
            </td>
        </tr>
        <tr>
            <td>3</td>
            <td>Kitchen printer IP</td>
            <td>Set IP address on kitchen printer</td>
            <td>
                <input type="text" class="form-control" name="kitchen_ip" placeholder="Enter IP" value="<?=html_escape($settings['kitchen_ip'] ?? '')?>">
            </td>
        </tr>
		<tr>
            <td>4</td>
            <td>Cashier printer IP</td>
            <td>Set IP address on cashier receipt printer</td>
            <td><input type="text" class="form-control" name="cashier_ip" placeholder="Enter IP" value="<?=html_escape($settings['cashier_ip'] ?? '')?>"></td>
        </tr>
                <tr>
            <td>5</td>
            <td>Bar printer IP</td>
            <td>Set IP address on the bar printer</td>
            <td><input type="text" class="form-control" name="bar_ip" placeholder="Enter IP" value="<?=html_escape($settings['bar_ip'] ?? '')?>"></td>
        </tr>
        <tr>
            <td>6</td>
            <td>Pantry printer IP</td>
            <td>Set IP address on the pantry printer</td>
            <td><input type="text" class="form-control" name="pantry_ip" placeholder="Enter IP" value="<?=html_escape($settings['pantry_ip'] ?? '')?>"></td>
        </tr>
<tr>
			<td colspan="4">
				<button type="submit" class="btn btn-primary">Update</button>
                <a href="<?=base_url('terminal/prints')?>" class="btn btn-outline-secondary ml-2">Print Terminal</a>
			</td>
		</tr>
		</tbody>
	</table>
	</form>
	
</div>