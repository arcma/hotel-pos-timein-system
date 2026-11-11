<?php 
	$data['title'] = isset($title)? $title : "";
	$userdata = $this->session->Userdata('login');
	
?>
<?php $this->load->view('templates/header'); ?>
<style>
	a{
		text-decoration: none;
		color: inherit;
	}
	.main_top{
		display: flex;
		background-color: #fff;
		border: 1px solid #c2c2c2;
		border-radius: 10px;
		padding: 10px; 
		margin: 10px;
		justify-content: space-between;
		padding: 10px 30px;
	}
	.main_top a{
		padding: 5px 10px;
		border-radius: 5px;
		color: #000;
	}
	.main_top a:hover{
		color: red;
	}
	.top_welcome span{
		font-weight: 600;
		text-transform: capitalize;
	}
	.main_content{
		<?php if($userdata->urRoleID == 1 || $userdata->urRoleID == 2 || $userdata->urRoleID == 7){ ?>
		display: grid;
		<?php }else{ ?>
		display: block;
		<?php } ?>
		grid-template-columns: 200px auto;
		grid-gap: 15px;
		width: calc(100% - 20px);
		margin: 10px;
	}
	@media screen and (max-width: 800px) {
		.main_content{
			grid-template-columns: 120px auto;
			grid-gap: 10px;
			display: block;
		}
	}
	.table_content h1{
		text-align: center;
	}	
	.alert{
		color: #000!important;
	}
	.pos_title{
		font-weight: 600;
	}
	
	.table_content{
		min-height: calc(100vh - 104px);
	}
	
</style>
<div class="main_top">
	<div class='top_welcome'>
	<?php 
		$login = $this->session->userdata('login');
		// echo "<pre>";
		// print_r($login);
		// echo "</pre>";
		if(isset($login)){
			echo "Welcome <span>".$login->urName." ".$login->urLname."</span>";
		}
	?>
	</div>
	<div class="pos_title"><!--STONE HEAVEN--></div>
	<div class="top_logout"><a href="<?=base_url('/login/do_logout')?>">Log Out</a></div>
</div>

<div class="main_content">
	<?php $this->load->view('navigation', $data); ?>
	
	
	