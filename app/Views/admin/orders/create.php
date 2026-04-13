            <?= $this->extend('layouts/admin') ?>
            <?= $this->section('content') ?>
                <?php $activeCat = $activeCat ?? null; ?>
                <div class="content-wrapper">
                    <!-- Content Header (Page header) -->
                    <section class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1>Create Order</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                                        <li class="breadcrumb-item active">Create Order</li>
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
                                <!-- ================= LEFT : MENU ================= -->
                                <div class="col-md-7">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Menu</h3>
                                            <button class="btn btn-dark btn-sm float-right"
                                                    onclick="toggleFullscreen()">⛶ Fullscreen</button>
                                        </div>

                                        <div class="card-body">
                                            <!-- Categories -->
                                            <div class="mb-3 menu-categories">
                                                <button class="btn btn-sm <?= !$activeCat ? 'btn-primary':'btn-outline-primary' ?>"
                                                        onclick="filterCategory('')">All</button>

                                                <?php foreach ($categories as $c): ?>
                                                    <button class="btn btn-sm <?= $activeCat == $c['id'] ? 'btn-primary':'btn-outline-primary' ?>"
                                                            onclick="filterCategory(<?= $c['id'] ?>)">
                                                        <?= esc($c['name']) ?>
                                                    </button>
                                                <?php endforeach ?>
                                            </div>

                                            <!-- Menu Grid -->
                                            <div class="row">
                                                <?php foreach ($menus as $m): ?>
                                                <div class="col-md-4 col-sm-6 mb-3 d-flex">
                                                    <div class="card w-100">
                                                        <div class="card-body">
                                                            <h6><?= esc($m['name']) ?></h6>
                                                            <p class="text-success mb-1">
                                                                Rp <?= number_format($m['price']) ?>
                                                            </p>
                                                        </div>
                                                        <div class="card-footer p-2">
                                                            <button class="btn btn-primary w-100"
                                                                    data-menu='<?= esc(json_encode($m),'attr') ?>'
                                                                    onclick="openVariantModal(this)">
                                                                Add
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endforeach ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ================= RIGHT : CART ================= -->
                                <div class="col-md-5 cart-sticky">
                                    <div class="card">
                                        <div class="card-header bg-dark text-white">
                                            <h3 class="card-title">Order Cart</h3>
                                        </div>

                                        <div class="card-body">
                                            <form name="orderForm" id="orderForm">
                                                <div class="form-group">
                                                    <select name="order_type" class="form-control">
                                                        <option value="POS">POS</option>
                                                        <option value="ROOM">ROOM</option>
                                                        <option value="QR">QR</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" name="room_no" class="form-control" placeholder="Room / Table">
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" name="guest_name" class="form-control" placeholder="Guest Name">
                                                </div>

                                                <table class="table table-sm" id="cartTable">
                                                    <thead>
                                                        <tr>
                                                            <th>Item</th>
                                                            <th class="text-center">Qty</th>
                                                            <th class="text-right">Sub</th>
                                                            <th class="text-center">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>

                                                <h4 class="text-right">
                                                    Total: Rp <span id="total">0</span>
                                                </h4>

                                                <button class="btn btn-success btn-lg btn-block mt-3">
                                                    Submit Order
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ================= VARIANT MODAL ================= -->
                        <div class="modal fade" id="variantModal">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 id="variantTitle"></h5>
                                        <button class="close" data-dismiss="modal">&times;</button>
                                    </div>

                                    <div class="modal-body">
                                        <div id="variantList"></div>

                                        <div class="form-group mt-3">
                                            <textarea id="menuNotes" class="form-control"
                                                      placeholder="Notes (optional)"></textarea>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-primary w-100" onclick="confirmAddToCart()">
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ================= CASH PAYMENT MODAL ================= -->
                        <div class="modal fade" id="cashPaymentModal">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5>Cash Payment</h5>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Total Amount</label>
                                            <input type="text" id="paymentTotal" class="form-control" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Cash Payment</label>
                                            <input type="number" id="cashInput" class="form-control" placeholder="Enter cash amount">
                                        </div>
                                        <div class="form-group">
                                            <label>Change</label>
                                            <input type="text" id="cashChange" class="form-control" readonly>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-success w-100" id="confirmCashPayment">Confirm Payment</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            <?= $this->endSection() ?>

            <?= $this->section('scripts') ?>
                <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="Mid-client-_gjNM3szLAgKxq1a"></script>

                <style>
                    body.pos-mode .main-header,
                    body.pos-mode .main-sidebar { display:none }
                    body.pos-mode .content-wrapper { margin-left:0 }
                    .cart-sticky { position:sticky; top:10px }
                    body.pos-mode .btn { font-size:18px; padding:12px }
                </style>

                <script>
                    /* ================= POS MODE ================= */
                    const isPosMode =
                        new URLSearchParams(window.location.search).get('mode') === 'pos'
                        || window.innerWidth <= 1024;

                    if (isPosMode) document.body.classList.add('pos-mode');

                    /* ================= CART ================= */
                    let cart = [];
                    let selectedMenu = null;
                    let selectedVariant = null;

                    function addItem(menu){
                        let item = cart.find(i =>
                            i.menu_id==menu.id &&
                            i.variant_id==menu.variant_id &&
                            i.notes==menu.notes
                        );

                        if(item){
                            item.qty++;
                        } else {
                            cart.push({
                                menu_id: menu.id,
                                menu_name: menu.name,
                                variant_id: menu.variant_id ?? null,
                                variant_name: menu.variant_name ?? '',
                                notes: menu.notes ?? '',
                                price: menu.price,
                                qty: 1
                            });
                        }
                        renderCart();
                    }

                    function increaseQty(i){ cart[i].qty++; renderCart(); }
                    function decreaseQty(i){
                        cart[i].qty--;
                        if(cart[i].qty<=0) cart.splice(i,1);
                        renderCart();
                    }

                    function renderCart(){
                        let html = '', total = 0;

                        cart.forEach((i, idx) => {
                            let sub = i.qty * i.price;
                            total += sub;

                            html += `
                            <tr>
                                <td>
                                    <strong>${i.menu_name}</strong>
                                    ${i.variant_name ? `<br><small>${i.variant_name}</small>` : ''}
                                    ${i.notes ? `<br><small class="text-muted">📝 ${i.notes}</small>` : ''}
                                </td>

                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-secondary"
                                                onclick="decreaseQty(${idx})">−</button>
                                        <button class="btn btn-light" disabled>${i.qty}</button>
                                        <button class="btn btn-outline-secondary"
                                                onclick="increaseQty(${idx})">+</button>
                                    </div>
                                </td>

                                <td class="text-right">
                                    Rp ${sub.toLocaleString()}
                                </td>

                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-danger" onclick="removeItem(${idx})" title="Delete item">
                                        🗑
                                    </button>
                                </td>
                            </tr>`;
                        });

                        document.querySelector('#cartTable tbody').innerHTML = html;
                        document.getElementById('total').innerText = total.toLocaleString();
                    }

                    /* ================= VARIANT ================= */
                    function openVariantModal(btn){
                        selectedMenu = JSON.parse(btn.dataset.menu);
                        selectedVariant = null;
                        document.getElementById('variantTitle').innerText = selectedMenu.name;
                        document.getElementById('menuNotes').value='';
                        loadVariants(selectedMenu.id);
                        $('#variantModal').modal('show');
                    }

                    function loadVariants(menuId){
                        fetch(`<?= base_url('admin/menus/variants') ?>/${menuId}`)
                        .then(r=>r.json())
                        .then(data=>{
                            let html='';
                            data.forEach(v=>{
                                html+=`
                                <div class="custom-control custom-radio">
                                    <input type="radio" name="variant" id="v${v.id}"
                                           class="custom-control-input"
                                           onchange="selectedVariant=${v.id};selectedMenu.variant_name='${v.name}'">
                                    <label class="custom-control-label" for="v${v.id}">
                                        ${v.name} (+Rp ${Number(v.price).toLocaleString()})
                                    </label>
                                </div>`;
                            });
                            document.getElementById('variantList').innerHTML=html;
                        });
                    }

                    function confirmAddToCart(){
                        addItem({
                            ...selectedMenu,
                            variant_id:selectedVariant,
                            notes:document.getElementById('menuNotes').value
                        });
                        $('#variantModal').modal('hide');
                    }

                    /* ================= SUBMIT ================= */
                    $('#orderForm').submit(function(e){
                        e.preventDefault();

                        // Validasi cart
                        if(cart.length === 0){
                            Swal.fire({
                                icon: 'warning',
                                title: 'Empty Cart',
                                text: 'Please add menu first',
                                confirmButtonText: 'Close'
                            });
                            return;
                        }

                        // Loading
                        Swal.fire({
                            title: 'Processing Order...',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });

                        // CREATE ORDER (WAJIB DULU)
                        $.post("<?= base_url('admin/orders/store') ?>", {
                            order_type: $('[name=order_type]').val(),
                            room_no: $('[name=room_no]').val(),
                            guest_name: $('[name=guest_name]').val(),
                            items: cart
                        }, function(res){
                            Swal.close();

                            if(!res.status){
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Failed',
                                    text: res.message ?? 'Gagal membuat order'
                                });
                                return;
                            }

                            const orderId = res.order_id;

                            // PILIH METODE PAYMENT
                            Swal.fire({
                                title: 'Payment Method',
                                icon: 'question',
                                showDenyButton: true,
                                confirmButtonText: '💵 Cash',
                                denyButtonText: '💳 Online Payment'
                            }).then((result) => {
                                // =====================
                                // CASH
                                // =====================
                                if (result.isConfirmed) {
                                    // buka modal cash
                                    const totalAmount = parseFloat($('#total').text().replace(/,/g, ''));
                                    $('#orderForm').data('order-id', orderId); // simpan order_id di form
                                    openCashModal(totalAmount);
                                }

                                // =====================
                                // ONLINE PAYMENT (MIDTRANS)
                                // =====================
                                if (result.isDenied) {
                                    $.get("<?= base_url('admin/payment/midtrans-link') ?>/" + orderId, function(res){

                                        if (!res.status) {
                                            Swal.fire('Error', res.message, 'error');
                                            return;
                                        }

                                        // LANGSUNG REDIRECT (TANPA MODAL LAGI)
                                        window.location.href = res.payment_url;
                                    }, 'json')
                                    .fail(() => {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Server Error',
                                            text: 'Tidak dapat terhubung ke server Midtrans'
                                        });
                                    });
                                }
                            });
                        }, 'json').fail(() => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Server Error',
                                text: 'Tidak dapat menghubungi server'
                            });
                        });
                    });

                    /* ================= UTILS ================= */
                    function filterCategory(id){
                        const url=new URL(location.href);
                        id?url.searchParams.set('category',id):url.searchParams.delete('category');
                        location.href=url;
                    }

                    function toggleFullscreen(){
                        !document.fullscreenElement
                            ? document.documentElement.requestFullscreen()
                            : document.exitFullscreen();
                    }

                    /* ================= CASH PAYMENT ================= */
                    function openCashModal(totalAmount) {
                        $('#paymentTotal').val(totalAmount.toLocaleString());
                        $('#cashInput').val('');
                        $('#cashChange').val('');
                        $('#cashPaymentModal').modal('show');
                    }

                    $('#cashInput').on('input', function() {
                        const total = parseFloat($('#paymentTotal').val().replace(/,/g, ''));
                        const cash  = parseFloat($(this).val()) || 0;
                        const change = cash - total;
                        $('#cashChange').val(change >= 0 ? change.toLocaleString() : '');
                    });

                    $('#confirmCashPayment').on('click', function() {
                        const total = parseFloat($('#paymentTotal').val().replace(/,/g, ''));
                        const cash  = parseFloat($('#cashInput').val()) || 0;

                        if (cash < total) {
                            Swal.fire('Warning', 'Uang pembayaran kurang!', 'warning');
                            return;
                        }

                        // Update payment ke server
                        const orderId = $('#orderForm').data('order-id');
                        $.post("<?= base_url('admin/orders/update-payment') ?>", {
                            order_id: orderId,
                            payment_method: 'CASH'
                        }, function(res) {
                            if(res.status){
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Payment Success',
                                    text: 'Paid by cash. Change: Rp ' + (cash - total).toLocaleString(),
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.open(
                                        "<?= base_url('admin/orders/print') ?>/" + orderId,
                                        '_blank'
                                    );
                                    location.href = "<?= base_url('admin/orders') ?>";
                                });
                            } else {
                                Swal.fire('Error', res.message ?? 'Gagal memproses pembayaran', 'error');
                            }
                        }, 'json');
                    });

                    function removeItem(index){
                        Swal.fire({
                            title: 'Remove Item?',
                            text: 'Item will be removed',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, delete',
                            cancelButtonText: 'Cancel'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                cart.splice(index, 1);
                                renderCart();

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted',
                                    timer: 800,
                                    showConfirmButton: false
                                });
                            }
                        });
                    }
                </script>
            <?= $this->endSection() ?>
