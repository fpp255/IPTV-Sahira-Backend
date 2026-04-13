<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= $title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="<?= base_url('themesadmin/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('themesadmin/dist/css/styletheme.css') ?>">
</head>

<body class="hold-transition login-page">

<div class="login-box">
    <div class="login-logo"><b>Admin</b> Login</div>

    <div class="card">
        <div class="card-body login-card-body">

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
                        <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-lock"></span></div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    Login
                </button>
            </form>

        </div>
    </div>
</div>

<script src="<?= base_url('themesadmin/plugins/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('themesadmin/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('themesadmin/dist/js/styletheme.js') ?>"></script>

</body>
</html>
