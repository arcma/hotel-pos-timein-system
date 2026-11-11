
<style>
    .main_nav .nav-pills .nav-link { font-size: 14px; border-radius: 6px; color: #3f4c6b; border: 1px solid #d8dee4; margin: 0 3px; }
    .main_nav .nav-pills .nav-link.active { background-color: #3f4c6b; color: #fff; }
	.main_nav{
		border: 1px solid #c2c2c2;
		border-radius: 10px;
		padding: 10px;
		background-color: #fff;
		min-height: calc(100vh - 104px);
	}
	@media screen and (max-width: 800px) {
		.main_nav{
			min-height: auto;
			xtext-align: center;
			margin-bottom: 10px;
		}
	}
	@media screen and (max-width: 800px) {
		.main_nav{
			xpadding: 10px 2px;
			xmin-height: calc(100vh - 98px);
		}
	}
	.menu_title{
		font-size: 25px;
		text-align: center;
		font-weight: 600;
	}
	.mobile_menu{
		display: none;
	}
	@media screen and (max-width: 800px) {
		.menu_title{
			text-align: left;
			margin: 0px 10px;
		}
		.mobile_menu{
			display: inline-block;
			float: right;
		}
	}

	.menu_items{
		list-style-type: none;
		margin: 0px;
		padding: 0px;
	}
	
	.menu_items li{
		margin: 5px;
		padding: 3px;
		border: 1px solid transparent;
		position: relative;
		font-size: 18px;
		transition: .3s ease;
	}
	@media screen and (max-width: 800px) {
		.menu_items li{
			font-size: 14px;
			xdisplay: inline-block;
		}
	}
	.menu_items li:before, 
	.menu_items li:after {
		content: " ";
		width: 100%;
		left: 0;
		height: 2px;
		background: #3f4c6b;
		position: absolute;
		opacity: 0;
		-webkit-transition: .3s ease;
		transition: .3s ease;
	}
	.menu_items li:before{
	    top: 0;
		transform: translateY(20px);
	}
	.menu_items li:after{
	    bottom: 0;
		transform: translateY(-20px);
	}	
	.menu_items li:hover:before, 
	.menu_items li:hover:after {
	    -webkit-transform: translateY(0);
		-ms-transform: translateY(0);
		transform: translateY(0);
		opacity: 1;
	}
	.menu_items li:hover{
		padding-left: 10px;
	}
	
	.menu_items li.active{
		padding-left: 10px;
	}
	.menu_items li.active:before, 
	.menu_items li.active:after {
		content: " ";
		width: 100%;
		left: 0;
		height: 2px;
		background: #AF7867;
		position: absolute;
		opacity: 1;
	}
	.menu_items li.active:before{
	    top: 0;
		transform: translateY(0px);
	}
	.menu_items li.active:after{
	    bottom: 0;
		transform: translateY(0px);
	}
	
	.menu_items.active{
		display: block;
	}
	@media screen and (max-width: 800px) {
		.menu_items{
			display: none;
		}
		.menu_items.active{
			display: block;
		}
	}

	.menu_box{
		display: flex;
		flex-direction: column;
		justify-content: space-between;
		height: 90%;

	}
	
</style>
<?php 
$userdata = $this->session->Userdata('login');
$role = $userdata->urRoleID;
$env = strtolower($this->uri->segment('1'));


if($userdata && $role == 1 || $role  == 2 || $role  == 7 ){ 
?>
<div class="main_nav">
    <?php if ($role == 1): ?>
        <div class="nav nav-pills nav-fill mb-3" aria-label="Application menu">
            <a class="nav-link <?= $env == 'pos' ? 'active' : '' ?>" href="<?= base_url('pos/transactions') ?>">POS</a>
            <a class="nav-link <?= $env == 'cms' ? 'active' : '' ?>" href="<?= base_url('cms/home') ?>">CMS</a>
        </div>
    <?php endif; ?>
	<div class="menu_title">
		Menu
		
		<i class="fa-solid fa-bars mobile_menu"></i>
	</div>
	<div class="menu_box">
		
		<ul class="menu_items">
			<?php if($env == 'pos' ){ ?> 
			<a href="<?=base_url('pos/reports')?>"><li <?php if($title == "reports") echo 'class="active"'; ?>>Reports</li></a>
			<a href="<?=base_url('pos/transactions')?>"><li <?php if($title == "transactions") echo 'class="active"'; ?>>Transactions</li></a>
			<a href="<?=base_url('pos/tables')?>"><li <?php if($title == "tables") echo 'class="active"'; ?>>Tables</li></a>
			
			
			<?php if($role == 1 ){ ?> 
				<a href="<?=base_url('pos/products')?>"><li <?php if($title == "products") echo 'class="active"'; ?>>Products</li></a>
				<a href="<?=base_url('pos/categories')?>"><li <?php if($title == "categories") echo 'class="active"'; ?>>Categories</li></a>
				<a href="<?=base_url('pos/discounts')?>"><li <?php if($title == "discounts") echo 'class="active"'; ?>>Discounts</li></a>
				<a href="<?=base_url('pos/booking_discounts')?>"><li <?php if($title == "booking_discounts") echo 'class="active"'; ?>>Booking Discount</li></a>
				<a href="<?=base_url('pos/users')?>"><li <?php if($title == "users") echo 'class="active"'; ?>>Users</li></a>
				<a href="<?=base_url('pos/kitchen')?>"><li <?php if($title == "kitchen") echo 'class="active"'; ?>>Kitchen</li></a>
			<?php } ?>

				<a href="<?=base_url('pos/checkin')?>"><li <?php if($title == "booking" || $title == "checkin" || $title == "reservations") echo 'class="active"'; ?>>Booking</li></a>
				<a href="<?=base_url('pos/hris')?>"><li <?php if($title == "hris") echo 'class="active"'; ?>>HRIS</li></a>
				<a href="<?=base_url('pos/rooms')?>"><li <?php if($title == "rooms") echo 'class="active"'; ?>>Rooms</li></a>
			<?php }else{ ?>

			<a href="<?=base_url('cms/home')?>"><li <?php if($title == "home") echo 'class="active"'; ?>>Home</li></a>
			<a href="<?=base_url('cms/pages')?>"><li <?php if($title == "pages") echo 'class="active"'; ?>>Pages</li></a>
            <a href="<?=base_url('cms/navigations')?>"><li <?php if($title == "navigations") echo 'class="active"'; ?>>Navigations</li></a>
            <a href="<?=base_url('cms/footer')?>"><li <?php if($title == "footer") echo 'class="active"'; ?>>Footer</li></a>
			<?php } ?>
		</ul>
		

		<ul class="menu_items">
			<?php if($role == 1 ){ ?> 
				<?php if($env == 'pos' ){ ?> 
				<a href="<?=base_url('pos/settings')?>"><li <?php if($title == "settings") echo 'class="active"'; ?>>Settings</li></a>
				<?php }else{ ?>

				<?php } ?>
			<?php } ?>
		</ul>
	</div>
</div>
<?php } ?>

<script>
	$(document).ready(function(){
		$(".mobile_menu").click(function(){
			if($(".menu_items").hasClass('active')){
				$(".menu_items").removeClass('active');
			}else{
				$(".menu_items").addClass('active');
			}
		});
	});
</script>