<!doctype html>
<html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>HRIS Employee Login</title><link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>"><style>body{font-family:Arial,sans-serif;background:#f1f3f5;display:flex;min-height:100vh;align-items:center;justify-content:center}.card{background:white;padding:30px;border-radius:12px;box-shadow:0 8px 30px #0001;width:min(420px,92vw)}h2{margin-top:0}</style></head>
<body><div class="card"><h2>HRIS Employee Login</h2><p>Use your POS username and password to check in or check out.</p>
<?php if($e=$this->session->flashdata('hris_error')): ?><div class="alert alert-danger"><?= html_escape($e) ?></div><?php endif; ?>
<?php if($e=$this->session->flashdata('hris_success')): ?><div class="alert alert-success"><?= html_escape($e) ?></div><?php endif; ?>
<form method="post" action="<?= site_url('hris/employee_login_post') ?>"><input type="hidden" name="id" value="<?= html_escape($calendar_id) ?>"><div class="form-group"><label>Username</label><input class="form-control" name="username" required autocomplete="username"></div><div class="form-group"><label>Password</label><input class="form-control" type="password" name="password" required autocomplete="current-password"></div><button class="btn btn-primary btn-block">Log In</button></form>
</div></body></html>
