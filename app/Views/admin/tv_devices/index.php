			<?= $this->extend('layouts/admin') ?>

			<?= $this->section('content') ?>
				<div class="content-wrapper">
				    <!-- Content Header (Page header) -->
				    <section class="content-header">
				      	<div class="container-fluid">
				        	<div class="row mb-2">
				          		<div class="col-sm-6">
				            		<h1>TV Management</h1>
				          		</div>
				          		<div class="col-sm-6">
				            		<ol class="breadcrumb float-sm-right">
						              	<li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
						              	<li class="breadcrumb-item active">TV Management</li>
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
						                	<h3 class="card-title">
						                  		TV List
						                	</h3>
						              	</div>
						              	<div class="card-body pad table-responsive">
						                	<button class="btn btn-primary mb-3" id="btnAdd">
											    <i class="fas fa-plus"></i> Add TV
											</button>

						                	<table id="tvTable" class="table table-bordered">
										        <thead>
											        <tr class="text-center">
											        	<th width="5%">No</th>
											            <th>Device ID</th>
											            <th>Room</th>
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
							<div class="modal fade" id="tvModal">
								<div class="modal-dialog">
									<div class="modal-content">
										<form name="tvForm" id="tvForm">
										    <input type="hidden" name="id" id="id">
										    <div class="modal-content">
										        <div class="modal-header">
										            <h5 class="modal-title">TV Device</h5>
							          				<button type="button" class="close" data-dismiss="modal">&times;</button>
										        </div>
										        <div class="modal-body">
										        	<div class="form-group">
								            			<label for="device_id">Device ID</label>
								            			<input name="device_id" id="device_id" class="form-control" required>
								          			</div>
								          			<div class="form-group">
								            			<label for="room_no">Room Number</label>
								            			<input name="room_no" id="room_no" class="form-control" required>
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
					let table;
					$(function(){
					    table = $('#tvTable').DataTable({
					        processing:true,
					        serverSide:true,
					        ajax:{
					            url:"<?= base_url('admin/tv-devices/datatable') ?>",
					            type:"POST"
					        },
					        order: [[1, 'desc']],
					        columnDefs: [
					            { orderable: false, targets: [0, 3] }
					        ]
					    });

					    $('#btnAdd').click(()=>{
					        $('#tvForm')[0].reset();
					        $('#id').val('');
					        $('#tvModal').modal('show');
					    });

					    $('#tvForm').submit(function(e){
					        e.preventDefault();
					        $.post("<?= base_url('admin/tv-devices/save') ?>", $(this).serialize(), res=>{
					            if(res.status){
					                Swal.fire('Success', res.message, 'success');
					                $('#tvModal').modal('hide');
					                table.ajax.reload();
					            }else{
					                Swal.fire('Error', res.message, 'error');
					            }
					        },'json');
					    });

					    $(document).on('click','.btn-edit',function(){
					        $('#id').val($(this).data('id'));
					        $('#device_id').val($(this).data('device'));
					        $('#room_no').val($(this).data('room'));
					        $('#tvModal').modal('show');
					    });

					    $(document).on('click','.btn-delete',function(){
					        let id = $(this).data('id');
					        Swal.fire({
					            title:'Delete?',
					            icon:'warning',
					            showCancelButton:true
					        }).then(res=>{
					            if(res.isConfirmed){
					                $.ajax({
					                    url:"<?= base_url('admin/tv-devices/delete') ?>/"+id,
					                    type:"DELETE",
					                    success:()=>{
					                        Swal.fire('Deleted','','success');
					                        table.ajax.reload();
					                    }
					                });
					            }
					        });
					    });
					});
					</script>
			<?= $this->endSection() ?>
