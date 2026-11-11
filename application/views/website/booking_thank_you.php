<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Thank You</title>
<style>
.thankyou-wrap{max-width:760px;margin:70px auto;padding:48px 24px;text-align:center;background:#fff;border:1px solid #e5e5e5;border-radius:12px}
.thankyou-wrap h1{margin:0 0 16px;font-size:32px}
.thankyou-wrap p{font-size:17px;line-height:1.6;color:#555}
.thankyou-wrap a{display:inline-block;margin-top:20px;padding:12px 22px;background:#182b28;color:#fff;text-decoration:none;border-radius:5px}
@media(max-width:650px){.thankyou-wrap{margin:28px 12px;padding:34px 18px}}
</style>
</head>
<body>
<?php $this->load->view('website/partials/navigation'); ?>
<main class="thankyou-wrap">
 <h1>Thank You!</h1>
 <p>Your booking request has been received. The hotel will contact you to confirm.</p>
 <a href="<?=base_url()?>">Back to Home</a>
</main>
<?php $this->load->view('website/partials/footer'); ?>
</body>
</html>
