			<?= $this->extend('layouts/admin') ?>
			<?= $this->section('content') ?>
				<div class="content-wrapper">
				    <!-- Content Header (Page header) -->
				    <section class="content-header">
				      	<div class="container-fluid">
				        	<div class="row mb-2">
				          		<div class="col-sm-6">
				            		<h1>Guest Management</h1>
				          		</div>
				          		<div class="col-sm-6">
				            		<ol class="breadcrumb float-sm-right">
						              	<li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
						              	<li class="breadcrumb-item active">Guest Management</li>
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
							                <h3 class="card-title">Guests List</h3>
							            </div>
						              	<div class="card-body pad table-responsive">
						              		<button class="btn btn-primary mb-3" id="btnAdd">
							                    <i class="fas fa-plus"></i> Add Guests
							                </button>

						                	<table id="guestsTable" class="table table-bordered table-striped">
							                    <thead>
							                        <tr>
							                            <th>Guests Name</th>
							                            <th>Device TV</th>
							                            <th>Room</th>
							                            <th>Status</th>
							                            <th>Check-in</th>
							                            <th>Check-out</th>
							                            <th width="100">Action</th>
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
				<?= $this->include('admin/guests/modal_add') ?>
				<?= $this->include('admin/guests/modal_edit') ?>
			<?= $this->endSection() ?>

			<?= $this->section('scripts') ?>
				<!-- AUTO REFRESH SETIAP 30 DETIK -->
				<script>
					let refreshInterval = 30000; // 30 detik
				    let isUserActive = false;

				    // Deteksi aktivitas user
				    $(document).on('mousemove keydown click', function () {
				        isUserActive = true;
				        setTimeout(() => isUserActive = false, 5000); // idle setelah 5 detik
				    });

				    setInterval(function () {
				        if (!isUserActive && !$('.modal.show').length && !Swal.isVisible()) {
				            location.reload();
				        }
				    }, refreshInterval);
				</script>

				<!-- DataTables -->
				<link rel="stylesheet" href="<?= base_url('themesadmin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
				<script src="<?= base_url('themesadmin/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
				<script src="<?= base_url('themesadmin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>

				<script>
					$(function () {
					    let table = $('#guestsTable').DataTable({
						    processing: true,
						    serverSide: true,
						    ajax: {
						        url: "<?= base_url('admin/guests/datatable') ?>",
						        type: "POST",
						        data: function (d) {
						            d.<?= csrf_token() ?> = "<?= csrf_hash() ?>";
						        }
						    },
						    order: [[4, 'desc']], // default sort Check-in
						    columnDefs: [
						        { orderable: false, targets: [6] } // Action
						    ]
						});

					    /* =========================
					     * INIT SELECT2 ROOM
					     * ========================= */
					    function initRoomSelect2() {
					        $('#room_no').select2({
					            theme: 'bootstrap4',
					            placeholder: '-- Select Room --',
					            allowClear: true,
					            width: '100%'
					        });
					    }

					    /* =========================
						 * ADD (ROOM LOCK ENABLED)
						 * ========================= */
						$('#btnAdd').click(function () {
						    $('#addGuestForm')[0].reset();

						    // Destroy select2 sebelum rebuild
						    if ($('#room_no').hasClass("select2-hidden-accessible")) {
						        $('#room_no').select2('destroy');
						    }

						    $('#room_no').html('<option value="">-- Select Room --</option>');
						    $('#device_id').val('');

						    $.get("<?= base_url('admin/guests/room-devices') ?>", function (data) {

						        data.forEach(function (item) {

						            let locked = parseInt(item.is_locked) === 1;
						            let label  = locked
						                ? item.room_no + ' (Occupied)'
						                : item.room_no;

						            $('#room_no').append(
						                `<option value="${item.room_no}"
						                         data-device="${item.device_id}"
						                         ${locked ? 'disabled' : ''}>
						                    ${label}
						                </option>`
						            );
						        });

						        // Init select2 SETELAH option lengkap
						        initRoomSelect2();
						    }, 'json');

						    $('#addGuestModal').modal('show');
						});


						/* =========================
						 * AUTO SET DEVICE ID
						 * ========================= */
						$('#room_no').on('select2:select', function (e) {
						    let device = $(e.params.data.element).data('device') || '';
						    $('#device_id').val(device);
						});

						$('#room_no').on('select2:clear', function () {
						    $('#device_id').val('');
						});

						function initEditRoomSelect2() {
						    $('#edit_room_no').select2({
						        theme: 'bootstrap4',
						        placeholder: '-- Select Room --',
						        allowClear: true,
						        width: '100%',
						        dropdownParent: $('#editGuestModal') // ⬅️ PENTING
						    });
						}

					    /* =========================
						 * EDIT (ROOM LOCK ENABLED)
						 * ========================= */
						$('#guestsTable').on('click', '.btn-edit', function () {
						    let id = $(this).data('id');

						    $.get("<?= base_url('admin/guests/edit') ?>/" + id, function (res) {
						        if (!res.status) return;

						        $('#edit_id').val(res.data.id);
						        $('#edit_guest_name').val(res.data.guest_name);
						        $('#edit_status').val(res.data.status);
						        $('#edit_checkin_date').val(res.data.checkin_date);
						        $('#edit_checkout_date').val(res.data.checkout_date);

						        loadEditRooms(res.data.room_no);

						        $('#editGuestModal').modal('show');
						    }, 'json');
						});

						/* =========================
						 * LOAD ROOM LIST (EDIT)
						 * ========================= */
						function loadEditRooms(selectedRoom) {
						    // Destroy Select2 jika sudah aktif
						    if ($('#edit_room_no').hasClass('select2-hidden-accessible')) {
						        $('#edit_room_no').select2('destroy');
						    }

						    $('#edit_room_no').html('<option value="">-- Select Room --</option>');
						    $('#edit_device_id').val('');

						    $.get("<?= base_url('admin/guests/room-devices') ?>", function (data) {

						        data.forEach(function (item) {

						            let locked   = parseInt(item.is_locked) === 1;
						            let selected = item.room_no === selectedRoom;
						            let disabled = locked && !selected;

						            let label = locked && !selected
						                ? item.room_no + ' (Occupied)'
						                : item.room_no;

						            $('#edit_room_no').append(
						                `<option value="${item.room_no}"
						                         data-device="${item.device_id}"
						                         ${selected ? 'selected' : ''}
						                         ${disabled ? 'disabled' : ''}>
						                    ${label}
						                </option>`
						            );

						            if (selected) {
						                $('#edit_device_id').val(item.device_id);
						            }
						        });

						        // INIT SELECT2 SETELAH OPTION TERISI
						        initEditRoomSelect2();
						    }, 'json');
						}


						/* =========================
						 * AUTO FILL DEVICE (EDIT)
						 * ========================= */
						$('#edit_room_no').on('select2:select', function (e) {
						    let device = $(e.params.data.element).data('device') || '';
						    $('#edit_device_id').val(device);
						});

						$('#edit_room_no').on('select2:clear', function () {
						    $('#edit_device_id').val('');
						});

						/* =========================
					     * STORE
					     * ========================= */
					    $('#addGuestForm').submit(function (e) {
						    e.preventDefault();

						    $.post(
						        "<?= base_url('admin/guests/store') ?>",
						        $(this).serialize(),
						        function (res) {
						            Swal.fire('Success', res.message, 'success');
						            $('#addGuestModal').modal('hide');
						            table.ajax.reload(null, false);
						        },
						        'json'
						    );
						});

					    /* =========================
					     * UPDATE
					     * ========================= */
					    $('#editGuestForm').submit(function (e) {
						    e.preventDefault();

						    $.post(
						        "<?= base_url('admin/guests/update') ?>",
						        $(this).serialize(),
						        function (res) {
						            Swal.fire({
						                icon: 'success',
						                title: 'Updated',
						                text: res.message
						            });

						            $('#editGuestModal').modal('hide');
						            $('#guestsTable').DataTable().ajax.reload();
						        },
						        'json'
						    );
						});

					    /* =========================
					     * DELETE
					     * ========================= */
					    $('#guestsTable').on('click', '.btn-delete', function () {
					        let id = $(this).data('id');

					        Swal.fire({
					            title: 'Delete data?',
					            text: 'Data will be deleted',
					            icon: 'warning',
					            showCancelButton: true,
					            confirmButtonText: 'Yes, delete'
					        }).then((result) => {
					            if (result.isConfirmed) {
					                $.post("<?= base_url('admin/guests/delete') ?>", {
					                    id: id,
					                    <?= csrf_token() ?>: "<?= csrf_hash() ?>"
					                }, function (res) {
					                    Swal.fire({
									        icon: 'success',
									        title: 'Success',
									        text: res.message,
									        confirmButtonText: 'Close'
									    });
					                    table.ajax.reload();
					                }, 'json');
					            }
					        });
					    });

					});
				</script>
			<?= $this->endSection() ?>
