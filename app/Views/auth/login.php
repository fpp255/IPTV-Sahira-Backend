<!DOCTYPE html>
<html lang="en">
	<head>
	  	<meta charset="utf-8">
	  	<meta name="viewport" content="width=device-width, initial-scale=1">
	  	<title><?= $title ?></title>
	  	<link rel="icon" href="<?= base_url('themesadmin/dist/img/favicon.ico') ?>" type="image/x-icon">

	  	<!-- Google Font: Source Sans Pro -->
	  	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&amp;display=fallback">
	  	<!-- Font Awesome -->
	  	<link rel="stylesheet" href="<?= base_url('themesadmin/plugins/fontawesome-free/css/all.min.css') ?>">
	  	<!-- icheck bootstrap -->
	  	<link rel="stylesheet" href="<?= base_url('themesadmin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') ?>">
	  	<!-- Theme style -->
	  	<link rel="stylesheet" href="<?= base_url('themesadmin/dist/css/styletheme.css?v=3.2.0') ?>">
	</head>
	<body class="hold-transition login-page">
		<div class="login-box">
		  	<div class="card card-outline card-primary">
			    <div class="card-header text-center">
			      	<a href="../../index2.html" class="h1"><b>Hotel</b> System</a>
			    </div>
			    <div class="card-body">
			      	<p class="login-box-msg">Sign in to start your session</p>

			      	<?php if (session()->getFlashdata('error')): ?>
		                <div class="alert alert-danger">
		                    <?= session()->getFlashdata('error') ?>
		                </div>
		            <?php endif; ?>
			      	
				    <form method="post" action="<?= base_url('login') ?>">
				    	<?= csrf_field() ?>
				        <div class="input-group mb-3">
				          	<input type="email" name="email" class="form-control" placeholder="Email" required>
				          	<div class="input-group-append">
				            	<div class="input-group-text">
				              		<span class="fas fa-envelope"></span>
				            	</div>
				          	</div>
				        </div>
				        <div class="input-group mb-3">
				          	<input type="password" name="password" class="form-control" placeholder="Password" required>
				          	<div class="input-group-append">
				            	<div class="input-group-text">
				              		<span class="fas fa-lock"></span>
				            	</div>
				          	</div>
				        </div>
				        <div class="row">
				          	<div class="col-8">
				            	<div class="icheck-primary">
				              		<input type="checkbox" id="remember">
				              		<label for="remember">
				                		Remember Me
				              		</label>
				            	</div>
				          	</div>
				          	<div class="col-4">
				            	<button type="submit" class="btn btn-primary btn-block">Sign In</button>
				          	</div>
				        </div>
				    </form>
				    <p class="mb-1">
				        <a href="#">I forgot my password</a>
				    </p>
			    </div>
		  	</div>
		</div>

		<!-- jQuery -->
		<script src="<?= base_url('themesadmin/plugins/jquery/jquery.min.js') ?>"></script>
		<!-- Bootstrap 4 -->
		<script src="<?= base_url('themesadmin/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
		<!-- Themes App -->
		<script src="<?= base_url('themesadmin/dist/js/styletheme.js?v=3.2.0') ?>"></script>
	</body>
</html>
