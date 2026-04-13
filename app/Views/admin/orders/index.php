            <?= $this->extend('layouts/admin') ?>
            <?= $this->section('content') ?>
                <div class="content-wrapper">
                    <!-- Content Header (Page header) -->
                    <section class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1>Order Management</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                                        <li class="breadcrumb-item active">Order Management</li>
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
                                            <h3 class="card-title">Order List</h3>
                                        </div>
                                        <div class="card-body pad table-responsive">
                                            <a href="<?= base_url('admin/orders/create') ?>" class="btn btn-primary mb-3">
                                                <i class="fas fa-plus"></i> Add Order
                                            </a>
                                            <table id="orderTable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th width="5%">No</th>
                                                        <th>Order No</th>
                                                        <th>Date</th>
                                                        <th>Room/Table</th>
                                                        <th>Guest</th>
                                                        <th>Type</th>  
                                                        <th>Payment</th>  
                                                        <th>Status</th>
                                                        <th>Total</th>
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
                                            <h5 class="modal-title">Order Detail</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>

                                        <div class="modal-body">
                                            <table class="table table-bordered">
                                                <thead class="text-center">
                                                    <tr>
                                                        <th>Menu</th>
                                                        <th>Variant</th>
                                                        <th>Notes</th>
                                                        <th width="5%">Qty</th>
                                                        <th width="10%">Price</th>
                                                        <th width="10%">Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="orderItems"></tbody>
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
                                            <h5 class="modal-title">Edit Order</h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>

                                        <form id="editOrderForm">
                                            <div class="modal-body">
                                                <input type="hidden" name="id" id="edit_order_id">
                                                <div class="form-group">
                                                    <label>Order Status</label>
                                                    <select name="order_status" id="edit_order_status" class="form-control">
                                                        <option value="PENDING">PENDING</option>
                                                        <option value="COOKING">COOKING</option>
                                                        <option value="READY">READY</option>
                                                        <option value="DELIVERED">DELIVERED</option>
                                                        <option value="CANCEL">CANCEL</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Payment Status</label>
                                                    <select name="payment_status" id="edit_payment_status" class="form-control">
                                                        <option value="UNPAID">UNPAID</option>
                                                        <option value="PENDING">PENDING</option>
                                                        <option value="PAID">PAID</option>
                                                        <option value="FAILED">FAILED</option>
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
                            url: "<?= base_url('admin/orders/datatable') ?>",
                            type: "POST"
                        },
                        order: [[1, 'desc']],
                        columnDefs: [
                            { orderable: false, targets: [0, 9] },
                            { className: 'text-center', targets: [0,5,6,9] },
                            { className: 'text-right', targets: [7] }
                        ]
                    });

                    function detailOrder(id)
                    {
                        Swal.fire({
                            title: 'Loading...',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });

                        $.get("<?= base_url('admin/orders') ?>/" + id, function(res){
                            Swal.close();

                            let rows = '';
                            res.items.forEach(i => {
                                rows += `
                                    <tr>
                                        <td>${i.menu_name}</td>
                                        <td>${i.variant_name}</td>
                                        <td>${i.notes}</td>
                                        <td class="text-center">${i.qty}</td>
                                        <td class="text-right">${parseInt(i.price).toLocaleString()}</td>
                                        <td class="text-right">${parseInt(i.subtotal).toLocaleString()}</td>
                                    </tr>
                                `;
                            });

                            $('#orderItems').html(rows);
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
                                COOKING: 'COOKING',
                                READY: 'READY',
                                DELIVERED: 'DELIVERED',
                                CANCEL: 'CANCEL'
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

                                $.post("<?= base_url('admin/orders/status') ?>/" + id, {
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

                    function reprintOrder(orderId){
                        window.open(
                            "<?= base_url('admin/orders/print') ?>/" + orderId,
                            '_blank'
                        );
                    }

                    function openEditModal(id)
                    {
                        $('#editOrderForm')[0].reset();
                        $('#edit_order_id').val(id);
                        $('#editOrderModal').modal('show');

                        $.get("<?= base_url('admin/orders/detail') ?>/" + id, function(resp){
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
                        $.post("<?= base_url('admin/orders/status') ?>/" + id, {
                            order_status: $('#edit_order_status').val(),
                            payment_status: $('#edit_payment_status').val()
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