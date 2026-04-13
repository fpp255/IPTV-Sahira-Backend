			<?= $this->extend('layouts/admin') ?>
			<?= $this->section('content') ?>
				<div class="content-wrapper">
				    <!-- Content Header (Page header) -->
				    <section class="content-header">
				      	<div class="container-fluid">
				        	<div class="row mb-2">
				          		<div class="col-sm-6">
				            		<h1>Menu Management</h1>
				          		</div>
				          		<div class="col-sm-6">
				            		<ol class="breadcrumb float-sm-right">
						              	<li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
						              	<li class="breadcrumb-item active">Menu Management - Category</li>
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
							            <div class="card-header">
							                <h3 class="card-title">Drag & Drop Category Order</h3>
							            </div>
						              	<div class="card-body pad table-responsive">
						              		<div class="alert alert-info">
							                    <i class="fas fa-info-circle"></i>
							                    Drag category to change order
							                </div>

							                <!-- SORTABLE LIST -->
							                <ul id="categorySortable" class="list-group">
							                    <?php foreach ($categories as $cat): ?>
							                        <li class="list-group-item d-flex align-items-center"
							                            data-id="<?= $cat['id'] ?>">

							                            <i class="fas fa-bars text-muted mr-3"></i>

							                            <strong><?= esc($cat['name']) ?></strong>

							                            <span class="badge badge-secondary ml-auto">
							                                <?= $cat['sort_order'] ?>
							                            </span>
							                        </li>
							                    <?php endforeach; ?>
							                </ul>
						              	</div>
						              	<!-- /.card -->
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

			<?= $this->section('scripts') ?>
				<link rel="stylesheet" href="<?= base_url('themesadmin/plugins/toastr/toastr.min.css') ?>">
				<script src="<?= base_url('themesadmin/plugins/toastr/toastr.min.js') ?>"></script>

				<script>
					$(function () {
					    $('#categorySortable').sortable({
					        placeholder: "ui-state-highlight",

					        update: function () {
					            let order = [];

					            $('#categorySortable li').each(function (index) {
					                order.push({
					                    id: $(this).data('id'),
					                    sort_order: index + 1
					                });
					            });

					            $.ajax({
					                url: "<?= base_url('admin/menu-categories/sort') ?>",
					                type: "POST",
					                data: { order: order },
					                success: function (res) {
					                    if (res.status) {
					                        toastr.success(res.message);
					                    } else {
					                        toastr.error('Failed to update order');
					                    }
					                },
					                error: function () {
					                    toastr.error('Server error');
					                }
					            });
					        }
					    });

					});
				</script>
			<?= $this->endSection() ?>