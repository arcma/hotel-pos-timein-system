<style>
	.container{
		min-height: calc(100vh - 28px);
	}
	.login_container{
		display: flex;
		flex-direction: column;
		justify-content: center;
		height: calc(100vh - 50px);
	}
	.login_logo{
		width: 100%;
	}
	.login_logo img{
		width: 100%;
		height: auto;
	}
	.login_form{
		padding: 10px;
		background-color: #f5f5f5;
		border-radius: 5px;
	}
	.alert{
		color: #000!important;
	}
</style>
<div class="container">
	<div class="row">
		<div class="col-lg-4 offset-lg-4 col-md-6 offset-md-3 col-sm-10 offset-sm-1 login_container">
			<!--div class="login_logo mb-2">
				<img src="<?=base_url('assets/images/loginlogo.jpg')?>" />
			</div-->
			<div class="login_form">
				<?php if (isset($_SESSION['error'])) : ?>
					<div class="alert alert-danger mt-3">
						<?= $_SESSION['error'] ?>
					</div>
				<?php endif; ?>

				<form method="post" action="<?=base_url('login/do_login') ?>">
					<h2>Log in</h2>
					<div class="input-group">
						<span class="input-group-text">Username</span>
						<input type="text" name="username" class="form-control" value="" autofocus="" required>
					</div>

					<div class="input-group mt-2">
						<span class="input-group-text">Password</span>
						<input type="password" name="password" class="form-control" value="" autofocus="" required>
					</div>

					<div class="mt-3 d-flex justify-content-between">
						<a href="../" class="btn btn-link">Website</a>
						<button type="submit" class="btn btn-primary">Log in</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>