<style>
	.table_content{
		border: 1px solid #c2c2c2;
		background-color: #fff;
		border-radius: 10px;
		display: inline-block;
		padding: 10px 5%;
	}
	.list_back{
		display: flex;
		justify-content: right;
	}
</style>
<div class="table_content container-fluid" >
	<h1 class="mt-3" >Add User</h1>
	<div class="list_back mt-3">
		<a href="<?=base_url('pos/users')?>" class="btn btn-primary btn-sm">
			<i class="fa-solid fa-less-than"></i>&nbsp;
			Back to List
		</a>
	</div>
	<form class="mt-3" action="<?=base_url('pos/users/do_add')?>" method="POST">
		<div class="mb-3">
			<label for="input_name" class="form-label">Frist Name</label>
			<input type="text" class="form-control" id="input_name" name="name" placeholder="Enter first name" value="" required>
		</div>
		<div class="mb-3">
			<label for="input_lname" class="form-label">Last Name</label>
			<input type="text" class="form-control" id="input_lname" name="lname" placeholder="Enter last name" value="" required>
		</div>
		<div class="mb-3">
			<label for="input_username" class="form-label">Username</label>
			<input type="text" class="form-control" id="input_username" name="username" placeholder="Enter username" value="" required>
		</div>

		<div class="mb-3">
			<label for="input_password" class="form-label">Password</label>
			<input type="password" class="form-control" id="input_password" name="password" placeholder="Enter password" value="" required>
		</div>
		<div class="mb-3">
			<label for="input_password" class="form-label">Access</label>
			<select class="form-control" name="role">
				<option value="">Select Role</option>
				<option value="2">Casher</option>
				<option value="3">Waiter</option>
				<option value="5">Tennat</option>
			</select>
		</div>
		
		<button type="submit" class="btn btn-primary">Save</button>
	</form>
</div>