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
						              	<li class="breadcrumb-item active">Menu Management - Variants</li>
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
							                <h3 class="card-title">Variant List</h3>
							            </div>
						              	<div class="card-body pad table-responsive">
						              		<button class="btn btn-primary mb-3" data-toggle="modal" data-target="#createVariantModal">
											    <i class="fas fa-plus"></i> Add Variant
											</button>

						                	<table id="variantTable" class="table table-bordered table-striped">
											    <thead>
											        <tr class="text-center">
											        	<th width="5%">No</th>
											            <th>Menu</th>
											            <th>Variant</th>
											            <th>Price</th>
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

					        <!-- EDIT MODAL -->
							<div class="modal fade" id="editVariantModal">
							  	<div class="modal-dialog">
							    	<div class="modal-content">
							      		<form name="editVariantForm" id="editVariantForm">
							      			<?= csrf_field() ?>
							        		<input type="hidden" name="id" id="variant_id">
							        		<div class="modal-header">
							          			<h5 class="modal-title">Edit Variant</h5>
							          			<button type="button" class="close" data-dismiss="modal">&times;</button>
							        		</div>

							        		<div class="modal-body">
							          			<div class="form-group">
										            <label for="edit_menu_id">Menu</label>
							                        <select name="edit_menu_id" id="edit_menu_id" class="form-control" required>
							                            <?php foreach ($menus as $m): ?>
							                                <option value="<?= $m['id'] ?>">
							                                    <?= esc($m['name']) ?>
							                                </option>
							                            <?php endforeach ?>
							                        </select>
										        </div>
										        <div class="form-group">
									            	<label for="edit_name">Variant Name</label>
							                        <input type="text" name="edit_name" id="edit_name" class="form-control" autocomplete="name" required>
									          	</div>
									          	<div class="form-group">
									            	<label for="edit_price">Price</label>
							                        <input type="number" name="edit_price" id="edit_price" class="form-control" required>
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

					        <!-- CREATE MODAL -->
							<div class="modal fade" id="createVariantModal">
								<div class="modal-dialog">
								    <div class="modal-content">
									    <form name="createVariantForm" id="createVariantForm">
									    	 <?= csrf_field() ?>
									        <div class="modal-header">
									          	<h5 class="modal-title">Add Variant</h5>
									          	<button type="button" class="close" data-dismiss="modal">&times;</button>
									        </div>
									        <div class="modal-body">
										        <div class="form-group">
										            <label for="menu_id">Menu</label>
							                        <select name="menu_id" id="menu_id" class="form-control" required>
							                            <?php foreach ($menus as $m): ?>
							                                <option value="<?= $m['id'] ?>">
							                                    <?= esc($m['name']) ?>
							                                </option>
							                            <?php endforeach ?>
							                        </select>
										        </div>
									          	<div class="form-group">
									            	<label for="name">Variant Name</label>
							                        <input type="text" name="name" id="name" class="form-control" autocomplete="name" required>
									          	</div>
									          	<div class="form-group">
									            	<label for="price">Price</label>
							                        <input type="number" name="price" id="price" class="form-control" required>
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
					let table = $('#variantTable').DataTable({
					    processing: true,
					    serverSide: true,
					    ajax: {
					        url: '<?= base_url('admin/menu-variants/datatable') ?>',
					        type: 'POST'
					    },
					    order: [[1, 'asc']],
				        columnDefs: [
				            { orderable: false, targets: [0, 4] }
				        ]
					});

					// CREATE
					$('#createVariantForm').submit(function(e) {
						e.preventDefault();
					    // SHOW LOADING
					    Swal.fire({
					        title: 'Processing...',
					        text: 'Please wait',
					        allowOutsideClick: false,
					        didOpen: () => Swal.showLoading()
					    });

					    $.post(
					        "<?= base_url('admin/menu-variants/store') ?>",
					        $(this).serialize(),
					        function (res) {
					            Swal.close(); // tutup loading

					            if (res.status) {
					                Swal.fire({
					                    icon: 'success',
					                    title: 'Success',
					                    text: res.message,
					                    confirmButtonText: 'Close'
					                });

					                $('#createVariantModal').modal('hide');
					                $('#createVariantForm')[0].reset();
					                table.ajax.reload(null, false);
					            } else {
					                Swal.fire({
					                    icon: 'error',
					                    title: 'Failed',
					                    text: res.message ?? 'Failed to save data',
					                    confirmButtonText: 'Close'
					                });
					            }
					        },
					        'json'
					    ).fail(function () {

					        Swal.close(); // tutup loading jika server error

					        Swal.fire({
					            icon: 'error',
					            title: 'Server Error',
					            text: 'An error occurred on the server',
					            confirmButtonText: 'Close'
					        });
					    });
					});

					// EDIT SHOW
					window.editVariant = function(id){
					    $.get("<?= base_url('admin/menu-variants/show') ?>/" + id, function(res){
						    $('#variant_id').val(res.id);
					        $('#edit_menu_id').val(res.menu_id);
					        $('#edit_name').val(res.name);
					        $('#edit_price').val(res.price);
						    $('#editVariantModal').modal('show');
					    },'json');
					}

					$('#editVariantForm').submit(function (e) {
					    e.preventDefault();

					    let id = $('#variant_id').val();

					    // Loading
					    Swal.fire({
					        title: 'Processing...',
					        text: 'Please wait',
					        allowOutsideClick: false,
					        didOpen: () => Swal.showLoading()
					    });

					    $.ajax({
					        url: "<?= base_url('admin/menu-variants/update') ?>/" + id,
					        type: "POST",
					        data: $(this).serialize(),
					        dataType: "json",
					        success: function (res) {
					            Swal.close();

					            if (res.status) {
					                Swal.fire({
					                    icon: 'success',
					                    title: 'Updated',
					                    text: res.message,
					                    confirmButtonText: 'Close'
					                });

					                $('#editVariantModal').modal('hide');
					                table.ajax.reload(null, false);
					            } else {
					                Swal.fire({
					                    icon: 'error',
					                    title: 'Failed',
					                    text: res.message ?? 'Update failed'
					                });
					            }
					        },
					        error: function () {
					            Swal.close();
					            Swal.fire({
					                icon: 'error',
					                title: 'Server Error',
					                text: 'An error occurred on the server'
					            });
					        }
					    });
					});

					// DELETE
					function deleteVariant(id){
					    Swal.fire({
					        title: 'Data will be deleted?',
					        icon: 'warning',
					        showCancelButton: true,
					        confirmButtonText: 'Yes, delete'
					    }).then(res => {
					        if(res.isConfirmed){
					            $.post('<?= base_url('admin/menu-variants/delete') ?>/' + id, function(){
					                Swal.fire({
					                    icon: 'success',
					                    title: 'Deleted',
					                    text: 'Data has been successfully deleted',
					                    confirmButtonText: 'Close'
					                });
					                table.ajax.reload();
					            });
					        }
					    });
					}
				</script>

			<?= $this->endSection() ?>