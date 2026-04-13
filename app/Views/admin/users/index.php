			<?= $this->extend('layouts/admin') ?>
			<?= $this->section('content') ?>
				<div class="content-wrapper">
				    <!-- Content Header (Page header) -->
				    <section class="content-header">
				      	<div class="container-fluid">
				        	<div class="row mb-2">
				          		<div class="col-sm-6">
				            		<h1>User Management</h1>
				          		</div>
				          		<div class="col-sm-6">
				            		<ol class="breadcrumb float-sm-right">
						              	<li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
						              	<li class="breadcrumb-item active">User Management</li>
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
							                <h3 class="card-title">Users List</h3>
							            </div>
						              	<div class="card-body pad table-responsive">
						              		<a href="<?= base_url('admin/users/create') ?>" class="btn btn-primary mb-3">
											   + Add User
											</a>

						                	<table id="usersTable" class="table table-bordered table-striped">
											    <thead>
											        <tr>
											            <th>No</th>
											            <th>Nama</th>
											            <th>Email</th>
											            <th>Role</th>
											            <th>Aksi</th>
											        </tr>
											    </thead>
											</table>
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
				<!-- DataTables -->
				<link rel="stylesheet" href="<?= base_url('themesadmin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
				<script src="<?= base_url('themesadmin/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
				<script src="<?= base_url('themesadmin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>

				<script>
					$(function () {
					    let table = $('#usersTable').DataTable({
					        processing: true,
					        serverSide: true,
					        responsive: true,
					        ajax: {
					            url: "<?= base_url('admin/users/datatable') ?>",
					            type: "POST",
					            data: function (d) {
					                d.<?= csrf_token() ?> = "<?= csrf_hash() ?>";
					            }
					        },
					        order: [[1, 'asc']],
					        columnDefs: [
					            { orderable: false, targets: [0,4] }
					        ]
					    });

					    // DELETE CONFIRMATION
					    $('#usersTable').on('click', '.btn-delete', function () {
					        let userId = $(this).data('id');

					        Swal.fire({
					            title: 'Delete User?',
					            text: 'Data cannot be returned!',
					            icon: 'warning',
					            showCancelButton: true,
					            confirmButtonColor: '#d33',
					            cancelButtonColor: '#3085d6',
					            confirmButtonText: 'Yes, delete!',
					            cancelButtonText: 'Cancel'
					        }).then((result) => {
					            if (result.isConfirmed) {

					                $.ajax({
					                    url: "<?= base_url('admin/users/delete') ?>",
					                    type: "POST",
					                    dataType: "json",
					                    data: {
					                        id: userId,
					                        <?= csrf_token() ?>: "<?= csrf_hash() ?>"
					                    },
					                    success: function (res) {
					                        if (res.status) {
					                            Swal.fire({
					                                icon: 'success',
					                                title: 'Success',
					                                text: res.message,
					                                confirmButtonText: "Close"
					                            });
					                            table.ajax.reload(null, false);
					                        } else {
					                            Swal.fire('Gagal', res.message, 'error');
					                        }
					                    }
					                });

					            }
					        });
					    });
					});
				</script>

				<?php if (session()->getFlashdata('success')): ?>
					<script>
						Swal.fire({
						    icon: 'success',
						    title: 'Success',
						    text: '<?= session()->getFlashdata('success') ?>',
						    confirmButtonText: "Close"
						});
					</script>
				<?php endif; ?>

				<?php if (session()->getFlashdata('error')): ?>
					<script>
						Swal.fire({
						    icon: 'error',
						    title: 'Error',
						    text: '<?= session()->getFlashdata('error') ?>',
						    confirmButtonText: "Close"
						});
					</script>
				<?php endif; ?>

				<?php if (session()->getFlashdata('warning')): ?>
					<script>
						Swal.fire({
						    icon: 'warning',
						    title: 'Warning',
						    text: '<?= session()->getFlashdata('warning') ?>',
						    confirmButtonText: "Close"
						});
					</script>
				<?php endif; ?>
			<?= $this->endSection() ?>
