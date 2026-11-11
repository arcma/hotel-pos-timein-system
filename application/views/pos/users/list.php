<?php
	$action_td_width = 'style="width: 260px;"';
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
</style>
<div class="table_content container-fluid" >
	<h1 class="mt-3">Users List</h1>
	
	<?php if (isset($_SESSION['error'])){ ?>
		<div class="container alert alert-danger mt-3">
			<?= $_SESSION['error'] ?>
		</div>
	<?php }else if (isset($_SESSION['success'])){ ?>
		<div class="container alert alert-success mt-3">
			<?= $_SESSION['success'] ?>
		</div>
	<?php } ?>
	<div class="list_back">
		<a href="<?=base_url('pos/users/add')?>" class="btn btn-primary btn-sm">
			<i class="fa-solid fa-plus"></i>
			Add User
		</a>
	</div>
	<table class="table table-striped mt-3">
	  <thead>
		<tr>
		  <th scope="col">#</th>
		  <th scope="col">First Name</th>
		  <th scope="col">Last Name</th>
		  <th scope="col">Username</th>
		  <th scope="col">Role</th>
		  <th scope="col">Status</th>
		  <th scope="col">Date last modified</th>
		  <th scope="col">Date created</th>
		  <th scope="col" <?=$action_td_width?>>Action</th>
		</tr>
	  </thead>
	  
	  <tbody>
		<?php 
			$counter = 0;
			foreach($users as $user){
				$counter++;
				// echo "<pre>";
				// print_r($user);
				// echo "</pre>";
		?>
			<tr <?php if($user->urStatus != "1") echo "class='disabled_td'"; ?>>
			  <td scope="row"><?=$counter?></td>
			  <td><?=$user->urName?></td>
			  <td><?=$user->urLname?></td>
			  <td><?=$user->urUsername?></td>
			  
			   <td>
				<?php
					if($user->urRoleID == "1"){
						echo "Admin";
					}else if($user->urRoleID == "2"){
						echo "Casher";
					}else if($user->urRoleID == "3"){
						echo "Waiter";
					}else if($user->urRoleID == "4"){
						echo "Kitchen";
					}else if($user->urRoleID == "5"){
						echo "Tennat";
					
					}else if($user->urRoleID == "6"){
						echo "CheckInOut";
					}else if($user->urRoleID == "7"){
						echo "CMS";
					}
				?>
			  </td>
			  <td>
				<?php
					if($user->urStatus == "1"){
						echo "Active";
					}else{
						echo "Disabled";
					}
				?>
			  </td>
			  <td><?=$user->urDate_modified?></td>
			  <td><?=$user->urDate_created?></td>
			  <td class="no_blur">
				<a href="<?=base_url('pos/users/edit/').$user->urID?>" class="btn btn-primary btn-sm">
					<i class="fas fa-edit"></i>
					Edit
				</a>
				<?php if($user->urStatus == "1"){ ?>
					<a href="<?=base_url('pos/users/disable/').$user->urID?>" class="btn btn-warning btn-sm" onclick="return confirm('Confirm disable of <?=$user->urName?>?')">
						<i class="fas fa-toggle-off"></i>
						Disable
					</a>
				<?php }else{ ?>
					<a href="<?=base_url('pos/users/enable/').$user->urID?>" class="btn btn-success btn-sm">
						<i class="fas fa-toggle-on"></i>
						Enable
					</a>
				<?php } ?>
				<a href="<?=base_url('pos/users/delete/').$user->urID?>" class="btn btn-danger btn-sm" onclick="return confirm('Confirm deletion of <?=$user->urName?>?')">
					<i class="fa fa-trash" aria-hidden="true"></i>
					Delete
				</a>
			  </td>

			</tr>
			
			<?php } ?>
	  </tbody>
	</table>


</div>