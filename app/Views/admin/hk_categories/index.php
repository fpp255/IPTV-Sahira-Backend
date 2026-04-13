			<?= $this->extend('layouts/admin') ?>
			<?= $this->section('content') ?>
				<div class="content-wrapper">
				    <!-- Content Header (Page header) -->
				    <section class="content-header">
				      	<div class="container-fluid">
				        	<div class="row mb-2">
				          		<div class="col-sm-6">
				            		<h1>HK Management</h1>
				          		</div>
				          		<div class="col-sm-6">
				            		<ol class="breadcrumb float-sm-right">
						              	<li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
						              	<li class="breadcrumb-item active">HK Management - Category</li>
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
						              		<button class="btn btn-primary mb-3" id="btnAdd"><i class="fas fa-plus"></i> Add Category</button>

						              		<table id="table" class="table table-bordered table-striped">
											    <thead>
											        <tr class="text-center">
											        	<th width="5%">No</th>
												        <th>Category Name</th>
												        <th>Sort</th>
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

					        <!-- MODAL -->
					        <div class="modal fade" id="modalForm">
							    <div class="modal-dialog">
							        <form id="form">
							            <div class="modal-content">
							                <div class="modal-header">
							                    <h5 class="modal-title">HK Category</h5>
							                </div>
							                <div class="modal-body">
							                    <input type="hidden" id="id">
							                    <div class="form-group">
							                        <label for="name">Name</label>
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
							                    <button class="btn btn-primary">Save</button>
							                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
							                </div>
							            </div>
							        </form>
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
					let table = $('#table').DataTable({
					    processing: true,
					    serverSide: true,
					    ajax: {
					        url: "<?= site_url('admin/hk-categories/datatable') ?>",
					        type: "POST"
					    },
					    order: [[1, 'asc']],
				        columnDefs: [
				            { orderable: false, targets: [0, 4] }
				        ]
					});

					$('#btnAdd').click(function(){
					    $('#form')[0].reset();
					    $('#id').val('');
					    $('#sort_order').val('');
					    $('#sort_order').prop('disabled', true);
					    $('#modalForm').modal('show');
					});

					$('#table').on('click','.edit',function(){
					    $('#id').val($(this).data('id'));
					    $('#name').val($(this).data('name'));
					    $('#sort_order').prop('disabled', false);
					    $('#sort_order').val($(this).data('sort'));
					    $('#is_active').val($(this).data('active'));
					    $('#modalForm').modal('show');
					});

					$('#form').submit(function(e){
					    e.preventDefault();

					    let id = $('#id').val();
					    let url = id
					        ? "<?= site_url('admin/hk-categories/update') ?>/"+id
					        : "<?= site_url('admin/hk-categories/store') ?>";

					    $.post(url,{
					        name: $('#name').val(),
					        sort_order: $('#sort_order').val(),
					        is_active: $('#is_active').val()
					    }, function(res){
					        if(res.status){
					            $('#modalForm').modal('hide');
					            table.ajax.reload(null, false);

					            Swal.fire({
					                icon: 'success',
					                title: 'Success',
					                text: res.message,
					                confirmButtonText: 'Close'
					            });
					        }
					    }, 'json').fail(function(xhr){
						    Swal.fire({
						        icon: 'error',
						        title: 'Error',
						        text: xhr.responseText
						    });
						});
					});

					$('#table').on('click','.delete',function(){
					    let id = $(this).data('id');
					    Swal.fire({
					        title: 'Delete Data?',
					        text: 'Category & related HK will be deleted',
					        icon: 'warning',
					        showCancelButton: true,
					        confirmButtonText: 'Yes, delete!'
					    }).then((result)=>{
					        if(result.isConfirmed){
					            $.post(
					                "<?= site_url('admin/hk-categories/delete') ?>/" + id,
					                function(res){
					                    if(res.status){
					                        table.ajax.reload(null, false);
					                        Swal.fire({
					                            icon: 'success',
					                            title: 'Deleted!',
					                            text: res.message,
					                            confirmButtonText: 'Close'
					                        });
					                    }

					                },
					                'json'
					            );

					        }
					    });
					});
				</script>
				
			<?= $this->endSection() ?>