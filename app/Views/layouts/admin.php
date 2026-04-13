<!DOCTYPE html>
<html lang="en">
	<head>
  		<meta charset="utf-8">
  		<meta name="viewport" content="width=device-width, initial-scale=1">
  		<title><?= $title ?? 'Admin Dashboard' ?></title>
  		<link rel="icon" href="<?= base_url('themesadmin/dist/img/favicon.ico') ?>" type="image/x-icon">
  		<!-- Google Font: Source Sans Pro -->
  		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&amp;display=fallback">
  		<!-- Font Awesome -->
  		<link rel="stylesheet" href="<?= base_url('themesadmin/plugins/fontawesome-free/css/all.min.css') ?>">
  		<!-- SweetAlert2 -->
  		<link rel="stylesheet" href="<?= base_url('themesadmin/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') ?>">
  		<!-- Select2 -->
		<link rel="stylesheet" href="<?= base_url('themesadmin/plugins/select2/css/select2.min.css') ?>">
		<link rel="stylesheet" href="<?= base_url('themesadmin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') ?>">
  		<!-- Theme style -->
  		<link rel="stylesheet" href="<?= base_url('themesadmin/dist/css/styletheme.css?v=3.2.0') ?>">
	</head>
	<body class="hold-transition sidebar-mini layout-fixed">
		<div class="wrapper">
  			<!-- Navbar -->
			<?= $this->include('partials/navbar') ?>
  			<!-- /.navbar -->

  			<!-- Main Sidebar Container -->
			<?= $this->include('partials/sidebar') ?>
			<!-- /.main sidebar -->

  			<!-- Content Wrapper. Contains page content -->
			<?= $this->renderSection('content') ?>
			<!-- /.content-wrapper -->

			<?= $this->include('partials/footer') ?>

			<!-- Control Sidebar -->
			<aside class="control-sidebar control-sidebar-dark">
			    <!-- Control sidebar content goes here -->
			</aside>
			<!-- /.control-sidebar -->
		</div>

		<!-- jQuery -->
		<script src="<?= base_url('themesadmin/plugins/jquery/jquery.min.js') ?>"></script>
		<script src="<?= base_url('themesadmin/plugins/jquery-ui/jquery-ui.min.js') ?>"></script>
		<!-- Bootstrap 4 -->
		<script src="<?= base_url('themesadmin/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
		<!-- SweetAlert2 -->
		<script src="<?= base_url('themesadmin/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
		<!-- Select2 -->
		<script src="<?= base_url('themesadmin/plugins/select2/js/select2.full.min.js') ?>"></script>

		<?= $this->renderSection('scripts') ?>

		<!-- Themes App -->
		<script src="<?= base_url('themesadmin/dist/js/styletheme.js?v=3.2.0') ?>"></script>
	</body>
</html>
