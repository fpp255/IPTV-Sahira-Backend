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
						              	<li class="breadcrumb-item active">HK Management - Items</li>
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
							                <h3 class="card-title">Items List</h3>
							            </div>
						              	<div class="card-body pad table-responsive">
						              		<button class="btn btn-primary mb-3" id="btnAdd"><i class="fas fa-plus"></i> Add Items</button>

						                	<table id="hkTable" class="table table-bordered table-striped">
											    <thead>
											        <tr class="text-center">
											        	<th width="5%">No</th>
											        	<th>Item Name</th>
												        <th>Category</th>
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
					        <div class="modal fade" id="hkModal">
							    <div class="modal-dialog">
							        <form name="hkForm" id="hkForm">
							            <div class="modal-content">
							                <div class="modal-header">
							                    <h5 class="modal-title">HK Items</h5>
							                </div>
							                <div class="modal-body">
							                    <input type="hidden" name="id" id="id">

							                    <div class="form-group">
							                        <label for="name">Name</label>
							                        <input name="name" id="name" autocomplete="name" class="form-control" required>
							                    </div>

							                    <div class="form-group">
										            <label for="category_id">Category</label>
										            <select name="category_id" id="category_id" class="form-control" required>
										              <?php foreach ($categories as $c): ?>
										                <option value="<?= $c['id'] ?>"><?= esc($c['name']) ?></option>
										              <?php endforeach ?>
										            </select>
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
					const csrfName = '<?= csrf_token() ?>';
					const csrfHash = '<?= csrf_hash() ?>';

					let table;

					$(function () {
					    table = $('#hkTable').DataTable({
					        serverSide: true,
					        processing: true,
					        ajax: {
					            url: "<?= base_url('admin/hkitems/datatable') ?>",
					            type: "POST"
					        },
					        order: [[1, 'asc']],
					        columnDefs: [
					            { orderable: false, targets: [0, 4] }
					        ]
					    });

					    $('#btnAdd').click(() => {
					        $('#hkForm')[0].reset();
					        $('#id').val('');
					        $('#hkModal').modal('show');
					    });

					    $('#hkForm').submit(function(e){
						    e.preventDefault();
						    let id = $('#id').val();
						    let url = id
						        ? "<?= base_url('admin/hkitems/update') ?>/" + id
						        : "<?= base_url('admin/hkitems/store') ?>";

						    $.post(url, $(this).serialize(), res => {
						        Swal.fire('Success', res.message, 'success');
						        $('#hkModal').modal('hide');
						        table.ajax.reload(null, false);
						    }, 'json');
						});

						$('#hkTable').on('click','.btn-edit',function(){
						    $('#id').val($(this).data('id'));
						    $('#name').val($(this).data('name'));
						    $('#category_id').val($(this).data('categoryId'));
						    $('#is_active').val($(this).data('active'));
						    $('#hkModal').modal('show');
						});

					    $('#hkTable').on('click','.btn-delete',function(){
						    let id = $(this).data('id');

						    Swal.fire({
						        title: 'Delete Data?',
						        text: 'Data will be removed',
						        icon: 'warning',
						        showCancelButton: true,
						        confirmButtonText: 'Yes, delete!'
						    }).then(result => {
						        if (result.isConfirmed) {
						            $.post(
						                "<?= base_url('admin/hkitems/delete') ?>/" + id,
						                {
						                    [csrfName]: csrfHash
						                },
						                function(res){
						                    Swal.fire('Deleted!', res.message, 'success');
						                    table.ajax.reload(null, false);
						                },
						                'json'
						            );
						        }
						    });
						});
					});
				</script>
			<?= $this->endSection() ?>