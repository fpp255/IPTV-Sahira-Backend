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
							                <h3 class="card-title">Category List</h3>
							            </div>
						              	<div class="card-body pad table-responsive">
						              		<button class="btn btn-primary mb-3" data-toggle="modal" data-target="#createCategoryModal">
											    <i class="fas fa-plus"></i> Add Category
											</button>

						                	<table id="categoryTable" class="table table-bordered table-striped">
											    <thead>
											        <tr class="text-center">
											        	<th width="5%">No</th>
											            <th>Name</th>
											            <th>Sort Order</th>
											            <th>Status</th>
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

					        <!-- EDIT CATEGORY MODAL -->
							<div class="modal fade" id="editCategoryModal">
							  	<div class="modal-dialog">
							    	<div class="modal-content">
							      		<form name="editCategoryForm" id="editCategoryForm">
							        		<input type="hidden" name="id" id="edit_id">
							        		<div class="modal-header">
							          			<h5 class="modal-title">Edit Category</h5>
							          			<button type="button" class="close" data-dismiss="modal">&times;</button>
							        		</div>

							        		<div class="modal-body">
							          			<div class="form-group">
							            			<label for="name">Category Name</label>
							            			<input type="text" name="name" id="edit_name" class="form-control" required>
							          			</div>
							          			<div class="form-group">
							            			<label for="edit_sort_order">Sort Order</label>
							            			<input type="number" name="edit_sort_order" id="edit_sort_order" class="form-control">
							          			</div>
							          			<div class="form-group">
							            			<label for="is_active">Status</label>
						            				<select name="is_active" id="edit_is_active" class="form-control">
						              					<option value="1">Active</option>
						              					<option value="0">Inactive</option>
						            				</select>
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

							<!-- CREATE CATEGORY MODAL -->
							<div class="modal fade" id="createCategoryModal">
								<div class="modal-dialog">
								    <div class="modal-content">
									    <form name="createCategoryForm" id="createCategoryForm">
									        <div class="modal-header">
									          	<h5 class="modal-title">Add Category</h5>
									          	<button type="button" class="close" data-dismiss="modal">&times;</button>
									        </div>
									        <div class="modal-body">
										        <div class="form-group">
										            <label for="name">Category Name</label>
										            <input type="text" name="name" id="name" class="form-control" required>
										        </div>
									          	<div class="form-group">
									            	<label for="sort_order">Sort Order</label>
									            	<input type="number" name="sort_order" id="sort_order" class="form-control">
									          	</div>
									          	<div class="form-group">
									            	<label for="is_active">Status</label>
										            <select name="is_active" id="is_active" class="form-control">
										              	<option value="1">Active</option>
										              	<option value="0">Inactive</option>
										            </select>
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
					  	let table = $('#categoryTable').DataTable({
					    	processing: true,
					    	serverSide: true,
					    	ajax: {
					      		url: "<?= base_url('admin/menu-categories/datatable') ?>",
					      		type: "POST"
					    	},
					    	order: [[1, 'asc']],
					        columnDefs: [
					            { orderable: false, targets: [0, 4] }
					        ]
					  	});

					  	// CREATE
					  	$('#createCategoryModal').on('show.bs.modal', function () {
						    $.get("<?= base_url('admin/menu-categories/last-sort-order') ?>", function(res){
						        $('#sort_order').val(res.sort_order);
						    }, 'json');
						});
						
					  	$('#createCategoryForm').submit(function(e){
						    e.preventDefault();
						    $.post("<?= base_url('admin/menu-categories/store') ?>", $(this).serialize(), function(res){
							    if(res.status){
							        Swal.fire({
					                    icon: 'success',
					                    title: 'Success',
					                    text: res.message,
					                    confirmButtonText: 'Close'
					                });
							        $('#createCategoryModal').modal('hide');
							        $('#createCategoryForm')[0].reset();
							        table.ajax.reload(null,false);
							    }
						    },'json');
					  	});

					  	// EDIT SHOW
						window.editCategory = function(id){
						    $.get("<?= base_url('admin/menu-categories/show') ?>/" + id, function(res){
							    $('#edit_id').val(res.id);
							    $('#edit_name').val(res.name);
							    $('#edit_sort_order').val(res.sort_order);
							    $('#edit_is_active').val(res.is_active);
							    $('#editCategoryModal').modal('show');
						    },'json');
						}

						// UPDATE
						$('#editCategoryForm').submit(function(e){
							e.preventDefault();
						    let id = $('#edit_id').val();
						    $.post("<?= base_url('admin/menu-categories/update') ?>/" + id, $(this).serialize(), function(res){
							    if(res.status){
							        Swal.fire({
					                    icon: 'success',
					                    title: 'Updated',
					                    text: res.message,
					                    confirmButtonText: 'Close'
					                });
							        $('#editCategoryModal').modal('hide');
							        table.ajax.reload(null,false);
							    }
						    },'json');
						});

					  	// DELETE
						window.deleteCategory = function(id){
						    Swal.fire({
							    title: 'Delete Category?',
							    text: 'Data will be deleted along with the related menus',
							    icon: 'warning',
							    showCancelButton: true,
							    confirmButtonText: 'Yes, delete'
						    }).then((result)=>{
						      	if(result.isConfirmed){
							        $.ajax({
								        url: "<?= base_url('admin/menu-categories/delete') ?>/" + id,
								        type: "DELETE",
								        success: function(res){
								            if(res.status){
								        	    Swal.fire({
								                    icon: 'success',
								                    title: 'Deleted',
								                    text: res.message,
								                    confirmButtonText: 'Close'
								                });
								            	table.ajax.reload(null,false);
								            }
								        }
							        });
						      	}
							});
					  	}
					});
				</script>

			<?= $this->endSection() ?>