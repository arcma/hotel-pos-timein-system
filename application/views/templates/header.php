<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARSENIOS POS</title>
	<meta name="description" content="Teranova">
	<meta name="author" content="Teranova">
	<link rel="shortcut icon" href="<?= base_url('assets/images/stonepeak.jpg') ?>">	
	
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap-5/css/bootstrap.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/fontawesome-6.4.0/css/all.min.css') ?>" />

    <!--link rel="stylesheet" href="<?= base_url('assets/css/font-awesome.min.css') ?>" /-->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrapValidator.min.css') ?>" />
    <script type="text/javascript" src="<?= base_url('assets/js/jquery-3.7.0.min.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('assets/js/jquery-ui-1.13.2.min.js') ?>"></script>
	<script type="text/javascript" src="<?= base_url('assets/js/bootstrapValidator.min.js') ?>"></script>
	<script type="text/javascript" src="<?= base_url('assets/bootstrap-5/js/bootstrap.bundle.js') ?>"></script>
	
	<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();

			setTimeout(function() {
			  $(".alert").slideUp(500);
			}, 5000);
        });
    </script>
	<style>
		body{
			background-color: #c2c2c2;
			font-family: arial;
			font-size: 15px;
		}
		@media screen and (max-width: 800px) {
			body{
				font-size: 12px;
			}
			.btn-sm{
				font-size: 0.70rem
			}
		}
	</style>
</head>
<body>