            <?= $this->extend('layouts/admin') ?>
            <?= $this->section('content') ?>
                <div class="content-wrapper">
                    <!-- Content Header (Page header) -->
                    <section class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1>HK Order Management</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                                        <li class="breadcrumb-item active">HK Order Management</li>
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
                                            <h3 class="card-title">HK Order List</h3>
                                        </div>
                                        <div class="card-body pad table-responsive">
                                            <table id="orderTable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th width="5%">No</th>
                                                        <th>Order No</th>
                                                        <th>Room</th>
                                                        <th>Guest</th> 
                                                        <th>Status</th>
                                                        <th>Date</th>
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

                            <!-- ORDER DETAIL MODAL -->
                            <div class="modal fade" id="orderDetailModal">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <h5 class="modal-title">HK Order Detail</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>

                                        <div class="modal-body">
                                            <table class="table table-bordered">
                                                <thead class="text-center">
                                                    <tr>
                                                        <th>Item</th>
                                                        <th>Notes</th>
                                                        <th width="5%">Qty</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="hkorderItems"></tbody>
                                            </table>
                                        </div>

                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- EDIT FORM MODAL -->
                            <div class="modal fade" id="editOrderModal" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">HK Form</h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>

                                        <form id="editOrderForm">
                                            <div class="modal-body">
                                                <input type="hidden" name="id" id="edit_order_id">
                                                <div class="form-group">
                                                    <label>Status</label>
                                                    <select name="order_status" id="edit_order_status" class="form-control">
                                                        <option value="PENDING">PENDING</option>
                                                        <option value="FINISH">FINISH</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    Cancel
                                                </button>
                                                <button type="submit" class="btn btn-primary">
                                                    Update
                                                </button>
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
                    let table = $('#orderTable').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: "<?= base_url('admin/hkorders/datatable') ?>",
                            type: "POST"
                        },
                        order: [[5, 'desc']],
                        columnDefs: [
                            { orderable: false, targets: [0, 6] },
                            { className: 'text-center', targets: [0, 6] }
                        ]
                    });

                    function detailOrder(id)
                    {
                        Swal.fire({
                            title: 'Loading...',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });

                        $.get("<?= base_url('admin/hkorders') ?>/" + id, function(res){
                            Swal.close();

                            let rows = '';
                            res.items.forEach(i => {
                                rows += `
                                    <tr>
                                        <td>${i.menu_name}</td>
                                        <td>${i.notes ?? '-'}</td>
                                        <td class="text-center">${i.qty}</td>
                                    </tr>
                                `;
                            });

                            $('#hkorderItems').html(rows);
                            $('#orderDetailModal').modal('show');
                        },'json');
                    }

                    function changeStatus(id)
                    {
                        Swal.fire({
                            title: 'Change Order Status',
                            input: 'select',
                            inputOptions: {
                                PENDING: 'PENDING',
                                FINISH: 'FINISH'
                            },
                            showCancelButton: true,
                            confirmButtonText: 'Update'
                        }).then(res => {
                            if(res.isConfirmed){
                                Swal.fire({
                                    title: 'Processing...',
                                    allowOutsideClick: false,
                                    didOpen: () => Swal.showLoading()
                                });

                                $.post("<?= base_url('admin/hkorders/status') ?>/" + id, {
                                    status: res.value
                                }, function(resp){
                                    Swal.close();
                                    if(resp.status){
                                        Swal.fire('Success', resp.message, 'success');
                                        table.ajax.reload(null,false);
                                    }
                                },'json');
                            }
                        });
                    }

                    function openEditModal(id)
                    {
                        $('#editOrderForm')[0].reset();
                        $('#edit_order_id').val(id);
                        $('#editOrderModal').modal('show');

                        $.get("<?= base_url('admin/hkorders/detail') ?>/" + id, function(resp){
                            if(resp.status){
                                // set default value dari DB
                                $('#edit_order_status').val(resp.data.status);
                                $('#edit_payment_status').val(resp.data.payment_status);

                                // LOCK PAYMENT STATUS JIKA SUDAH PAID
                                if(resp.data.payment_status === 'PAID'){
                                    $('#edit_payment_status').prop('disabled', true);
                                } else {
                                    $('#edit_payment_status').prop('disabled', false);
                                }
                            } else {
                                Swal.fire('Error', resp.message, 'error');
                                $('#editOrderModal').modal('hide');
                            }
                        }, 'json');
                    }

                    $('#editOrderForm').submit(function(e){
                        e.preventDefault();
                        const id = $('#edit_order_id').val();
                        $.post("<?= base_url('admin/hkorders/status') ?>/" + id, {
                            order_status: $('#edit_order_status').val(),
                        }, function(resp){
                            if(resp.status){
                                $('#editOrderModal').modal('hide');
                                table.ajax.reload(null, false);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Data updated successfully',
                                    text: resp.message,
                                    confirmButtonText: 'Close'
                                });
                            }
                        }, 'json');
                    });
                </script>

            <?= $this->endSection() ?>