<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Payment Finish</title>
        <link rel="icon" href="<?= base_url('themesadmin/dist/img/favicon.ico') ?>" type="image/x-icon">
        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&amp;display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?= base_url('themesadmin/plugins/fontawesome-free/css/all.min.css') ?>">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?= base_url('themesadmin/dist/css/styletheme.css?v=3.2.0') ?>">
    </head>

    <body class="hold-transition lockscreen">
        <!-- Automatic element centering -->
        <div class="lockscreen-wrapper text-center">
            <h2 class="text-success">✅ Payment Success</h2>
            <p>Thank you. Payment has been processed.</p>

            <!-- <button class="btn btn-primary" onclick="location.href='<?= base_url('admin/orders') ?>'">
                Back to Orders
            </button> -->

            <?php
                $backUrl = session()->get('isLoggedIn')
                    ? base_url('admin/orders')
                    : 'https://iptv.sahirahotelsgroup.com';
            ?>
            <button class="btn btn-primary" onclick="location.href='<?= $backUrl ?>'"> Back </button>

        </div>

        <!-- jQuery -->
        <script src="<?= base_url('themesadmin/plugins/jquery/jquery.min.js') ?>"></script>
        <!-- Bootstrap 4 -->
        <script src="<?= base_url('themesadmin/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    </body>
</html>
