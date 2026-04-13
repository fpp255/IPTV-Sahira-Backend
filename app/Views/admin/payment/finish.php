            <?= $this->extend('layouts/admin') ?>
            <?= $this->section('content') ?>
                <div class="content-wrapper">
                    <!-- Content Header (Page header) -->
                    <section class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1>Payment</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                                        <li class="breadcrumb-item active">Payment</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- /.content header -->

                    <!-- Main content -->
                    <section class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card card-primary card-outline">
                                        <div class="card-body d-flex align-items-center justify-content-center text-center" style="min-height: 70vh;">
                                            <div>
                                                <h2 class="text-success">✅ Payment Success</h2>
                                                <p>Thank you. Payment has been processed.</p>

                                                <button class="btn btn-primary mt-3"
                                                    onclick="location.href='<?= base_url('admin/orders') ?>'">
                                                    Back to Orders
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- ./row -->
                        </div>
                    </section>
                    <!-- /.main content -->
                </div>
            <?= $this->endSection() ?>