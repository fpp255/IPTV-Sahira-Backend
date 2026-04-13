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
						              	<li class="breadcrumb-item active">Menu Management</li>
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
							                <h3 class="card-title">Menu List</h3>
							            </div>
						              	<div class="card-body pad table-responsive">
						              		<button class="btn btn-primary mb-3" data-toggle="modal" data-target="#createMenuModal">
											    <i class="fas fa-plus"></i> Add Menu
											</button>

						                	<table id="menuTable" class="table table-bordered table-striped">
											    <thead>
											        <tr class="text-center">
											            <th>Menu</th>
											            <th>Category</th>
											            <th width="10%">Price</th>
											            <th width="10%">Image</th>
											            <th width="12%">Room S.</th>
											            <th width="10%">Status</th>
											            <th width="15%">Action</th>
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

					        <!-- EDIT MENU MODAL -->
							<div class="modal fade" id="editMenuModal">
							    <div class="modal-dialog modal-lg">
							        <div class="modal-content">
							            <form name="editMenuForm" id="editMenuForm" enctype="multipart/form-data">
							                <?= csrf_field() ?>
							                <div class="modal-header">
							                    <h5 class="modal-title">Edit Menu</h5>
							                    <button type="button" class="close" data-dismiss="modal">&times;</button>
							                </div>

							                <div class="modal-body">
							                    <input type="hidden" name="id" id="menu_id">
							                    <div class="row">
								                    <div class="col-sm-4">
								                      	<div class="form-group">
									                        <label for="name">Menu Name</label>
									                        <input type="text" name="name" id="name" autocomplete="name" class="form-control" required>
									                    </div>
								                    </div>
								                    <div class="col-sm-4">
								                      	<div class="form-group">
									                        <label for="category_id">Category</label>
									                        <select name="category_id" id="category_id" class="form-control" required>
									                            <?php foreach ($categories as $c): ?>
									                                <option value="<?= $c['id'] ?>"><?= esc($c['name']) ?></option>
									                            <?php endforeach ?>
									                        </select>
									                    </div>
								                    </div>
								                    <div class="col-sm-4">
								                    	<div class="form-group">
									                        <label for="price">Price</label>
									                        <input type="number" name="price" id="price" class="form-control" required>
									                    </div>
								                    </div>
								                </div>
								                <div class="row">
								                    <div class="col-sm-4">
								                      	<div class="form-group">
									                        <label>Current Image</label><br>
									                        <img id="previewImage" class="img-thumbnail mb-2" width="120">
									                    </div>
								                    </div>
								                    <div class="col-sm-4">
								                      	<div class="form-group">
									                        <label for="is_roomservice">Room Service</label>
									                        <select name="is_roomservice" id="is_roomservice" class="form-control">
									                            <option value="0">No</option>
									                            <option value="1">Yes</option>
									                        </select>
									                    </div>
								                    </div>
								                    <div class="col-sm-4">
								                      	<div class="form-group">
									                        <label for="description">Description</label>
									                        <textarea name="description" id="description" rows="3" class="form-control"></textarea>
									                    </div>
								                    </div>
								                </div>
								                <div class="row">
								                    <div class="col-sm-6">
								                      	<div class="form-group">
									                        <label for="image">Change Image (optional)</label>
									                        <input type="file" name="image" id="image" class="form-control-file">
									                    </div>
								                    </div>
								                    <div class="col-sm-6">
								                      	<div class="form-group">
									                        <label for="is_active">Status</label>
									                        <select name="is_active" id="is_active" class="form-control">
									                            <option value="1">Active</option>
									                            <option value="0">Inactive</option>
									                        </select>
									                    </div>
								                    </div>
								                </div>
							                </div>

							                <div class="modal-footer">
							                    <button type="submit" class="btn btn-primary">Save Changes</button>
							                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
							                </div>

							            </form>

							        </div>
							    </div>
							</div>

							<!-- CREATE MENU MODAL -->
							<div class="modal fade" id="createMenuModal">
								<div class="modal-dialog modal-lg">
								    <div class="modal-content">
									    <form name="createMenuForm" id="createMenuForm" enctype="multipart/form-data">
									        <div class="modal-header">
									          	<h5 class="modal-title">Add Menu</h5>
									          	<button type="button" class="close" data-dismiss="modal">&times;</button>
									        </div>

									        <div class="modal-body">
									        	<div class="row">
								                    <div class="col-sm-4">
								                      	<div class="form-group">
												            <label for="name">Menu Name</label>
												            <input type="text" name="name" autocomplete="name" class="form-control" required>
												        </div>
								                    </div>
								                    <div class="col-sm-4">
								                      	<div class="form-group">
												            <label for="category_id">Category</label>
												            <select name="category_id" class="form-control" required>
												              <?php foreach ($categories as $c): ?>
												                <option value="<?= $c['id'] ?>"><?= esc($c['name']) ?></option>
												              <?php endforeach ?>
												            </select>
												        </div>
								                    </div>
								                    <div class="col-sm-4">
								                    	<div class="form-group">
												            <label for="price">Price</label>
												            <input type="number" name="price" class="form-control" required>
												        </div>
								                    </div>
								                </div>
								                <div class="row">
								                	<div class="col-sm-4">
									                    <div class="form-group">
												            <label for="image">Image</label>
												            <input type="file" name="image" class="form-control">
												        </div>
												    </div>
												    <div class="col-sm-4">
								                      	<div class="form-group">
												            <label for="is_roomservice">Room Service</label>
												            <select name="is_roomservice" class="form-control">
												              	<option value="0">No</option>
												              	<option value="1">Yes</option>
												            </select>
												        </div>
								                    </div>
								                    <div class="col-sm-4">
								                      	<div class="form-group">
												            <label for="is_active">Status</label>
												            <select name="is_active" class="form-control">
												              	<option value="1">Active</option>
												              	<option value="0">Inactive</option>
												            </select>
												        </div>
								                    </div>
								                </div>
								                <div class="row">
								                    <div class="col-sm-12">
								                      	<div class="form-group">
												            <label for="description">Description</label>
												            <textarea name="description" class="form-control"></textarea>
												        </div>
								                    </div>
								                </div>
									        </div>

									        <div class="modal-footer">
									          	<button type="submit" class="btn btn-primary">Save</button>
									          	<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
									        </div>
									    </form>
								    </div>
								</div>
							</div>
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
					    let table = $('#menuTable').DataTable({
					        processing: true,
					        serverSide: true,
					        ajax: {
					            url: "<?= base_url('admin/menus/datatable') ?>",
					            type: "POST",
					            data: function (d) {
					                d.<?= csrf_token() ?> = "<?= csrf_hash() ?>";
					            }
					        },
					        order: [[0, 'asc']],
					        columnDefs: [
					            { targets: [3,6], orderable: false }, // IMAGE, ACTION
					            { targets: [3,6], searchable: false }  // IMAGE, ACTION
					        ]
					    });
					});

					/* =========================
				     * ADD
				     * ========================= */
					$('#createMenuForm').submit(function(e) {
					    e.preventDefault();
					    let formData = new FormData(this);
					    Swal.fire({
					        title: 'Processing...',
					        allowOutsideClick: false,
					        didOpen: () => Swal.showLoading()
					    });

					    $.ajax({
					        url: "<?= base_url('admin/menus/store') ?>",
					        type: "POST",
					        data: formData,
					        contentType: false,
					        processData: false,
					        success: function(res) {
					            Swal.close();
					            if (res.status) {
					                Swal.fire({
					                    icon: 'success',
					                    title: 'Success',
					                    text: res.message,
					                    confirmButtonText: 'Close'
					                });

					                $('#createMenuModal').modal('hide');
					                $('#createMenuForm')[0].reset();
					                $('#menuTable').DataTable().ajax.reload(null, false);
					            } else {
					                Swal.fire('Gagal', res.message, 'error');
					            }
					        },
					        error: function() {
					            Swal.fire({
				                    icon: 'error',
				                    title: 'Error',
				                    text: 'An error occurred on the server',
				                    confirmButtonText: 'Close'
				                });
					        }
					    });
					});

					/* =========================
				     * EDIT
				     * ========================= */
					function editMenu(id)
					{
					    $.get("<?= base_url('admin/menus/show') ?>/" + id, function(res) {

					        $('#menu_id').val(res.id);
					        $('#category_id').val(res.category_id);
					        $('#name').val(res.name);
					        $('#description').val(res.description);
					        $('#price').val(res.price);
					        $('#is_roomservice').val(res.is_roomservice);
					        $('#is_active').val(res.is_active);

					        $('#previewImage').attr('src',
					            res.image
					            ? "<?= base_url('uploads/menus') ?>/" + res.image
					            : ''
					        );

					        $('#editMenuModal').modal('show');
					    });
					}

					$('#editMenuForm').submit(function(e) {
					    e.preventDefault();

					    let formData = new FormData(this);

					    Swal.fire({
					        title: 'Processing...',
					        text: 'Please wait',
					        allowOutsideClick: false,
					        didOpen: () => Swal.showLoading()
					    });

					    $.ajax({
					        url: "<?= base_url('admin/menus/update') ?>/" + $('#menu_id').val(),
					        type: "POST",
					        data: formData,
					        contentType: false,
					        processData: false,
					        success: function(res) {

					            Swal.close();

					            if (res.status) {
					                Swal.fire({
					                    icon: 'success',
					                    title: 'Success',
					                    text: res.message,
					                    confirmButtonText: 'Close'
					                });

					                $('#editMenuModal').modal('hide');
					                $('#menuTable').DataTable().ajax.reload(null, false);

					            } else {
					                Swal.fire('Gagal', res.message, 'error');
					            }
					        },
					        error: function() {
					            Swal.fire('Error', 'Terjadi kesalahan server', 'error');
					        }
					    });
					});

					/* =========================
				     * DELETE
				     * ========================= */
					function deleteMenu(id)
					{
					    Swal.fire({
					        title: 'Delete data?',
					        text: 'Data will be deleted',
					        icon: 'warning',
					        showCancelButton: true,
					        confirmButtonText: 'Yes, delete',
					        cancelButtonText: 'Cancel'
					    }).then((result) => {
					        if (result.isConfirmed) {
					            $.ajax({
					                url: "<?= base_url('admin/menus/delete') ?>/" + id,
					                type: "DELETE",
					                success: function(res) {

					                    if (res.status) {
					                        Swal.fire({
					                            icon: 'success',
					                            title: 'Success',
					                            text: 'Data has been successfully deleted',
					                            confirmButtonText: 'Close'
					                        });

					                        $('#menuTable').DataTable().ajax.reload(null, false);
					                    } else {
					                        Swal.fire('Gagal', 'Data failed to delete', 'error');
					                    }
					                }
					            });
					        }

					    });
					}
				</script>
			<?= $this->endSection() ?>