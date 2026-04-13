			<?= $this->extend('layouts/admin') ?>

			<?= $this->section('content') ?>
				<div class="content-wrapper">
				    <!-- Content Header (Page header) -->
				    <section class="content-header">
				      	<div class="container-fluid">
				        	<div class="row mb-2">
				          		<div class="col-sm-6">
				            		<h1>Dashboard</h1>
				          		</div>
				          		<div class="col-sm-6">
				            		<ol class="breadcrumb float-sm-right">
				              			<li class="breadcrumb-item active">Dashboard</li>
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
							                  Front Office
							                </h3>

							                <div class="card-tools">
							                  	<button type="button" class="btn bg-primary btn-sm" data-card-widget="collapse">
							                    	<i class="fas fa-minus"></i>
							                  	</button>
							                  	<button type="button" class="btn bg-primary btn-sm" data-card-widget="remove">
							                    	<i class="fas fa-times"></i>
							                  	</button>
							                </div>
							            </div>
							            <div class="card-body pad table-responsive">
							                <div class="row">
										        <div class="col-lg-3 col-6">
										            <div class="small-box bg-success">
										              	<div class="inner">
										              		<h3><?= $availableRooms ?? 0 ?></h3>
										                	<p>Available IPTV</p>
										              	</div>
										              	<div class="icon"><i class="fas fa-tv"></i></div>
										              	<span class="small-box-footer">&nbsp;</span>
										            </div>
										        </div>
										        <div class="col-lg-3 col-6">
										            <div class="small-box bg-danger">
										              	<div class="inner">
										              		<h3><?= $occupiedRooms ?? 0 ?></h3>
										                	<p>Occupied IPTV</p>
										              	</div>
										              	<div class="icon"><i class="fas fa-play-circle"></i></div>
										              	<span class="small-box-footer">&nbsp;</span>
										            </div>
										        </div>
										        <div class="col-lg-3 col-6">
										            <div class="small-box bg-warning">
										              	<div class="inner">
										              		<h3><?= $checkoutToday ?? 0 ?></h3>
										                	<p>Check-out Today</p>
										              	</div>
										              	<div class="icon"><i class="fas fa-sign-out-alt"></i></div>
										              	<span class="small-box-footer">&nbsp;</span>
										            </div>
										        </div>
										        <div class="col-lg-3 col-6">
										            <div class="small-box bg-danger">
										              	<div class="inner">
										              		<h3><?= $overdueCheckout ?? 0 ?></h3>
										                	<p>Check-out Overdue</p>
										              	</div>
										              	<div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
										              	<span class="small-box-footer">&nbsp;</span>
										            </div>
										        </div>
										    </div>
										    <div class="row">
										        <!-- CHECK-OUT TODAY TABLE -->
												<div class="col-md-6">
													<div class="card">
														<div class="card-header">
															<h3 class="card-title">Check-out Today</h3>
														</div>
														<div class="card-body table-responsive p-0">
															<table class="table table-hover">
																<thead>
																	<tr>
																		<th>Room No.</th>
																		<th>Guest Name</th>
																		<th>Check Out</th>
																		<th>Action</th>
																	</tr>
																</thead>
																<tbody>
																	<?php if (!empty($todayCheckouts)): ?>
																		<?php foreach ($todayCheckouts as $row): ?>
																			<tr>
								                                                <td><?= esc($row['room_no']) ?></td>
								                                                <td><?= esc($row['guest_name']) ?></td>
								                                                <td>
																				    <?= !empty($row['checkout_date'])
																				        ? date('d-m-Y', strtotime($row['checkout_date']))
																				        : '-' ?>
																				</td>
								                                                <td>
																				    <?php if (in_array($row['status'], ['ACTIVE'])): ?>
																				        <button class="btn btn-sm btn-warning btn-checkout"
																					            data-id="<?= $row['id'] ?>">
																					        <i class="fas fa-sign-out-alt"></i> Check-out
																					    </button>
																				    <?php else: ?>
																				        <span class="text-muted">-</span>
																				    <?php endif; ?>
																				</td>
								                                            </tr>
																		<?php endforeach ?>
																	<?php else: ?>
								                                        <tr>
								                                            <td colspan="4" class="text-center">
								                                                No data available
								                                            </td>
								                                        </tr>
								                                    <?php endif ?>
																</tbody>
															</table>
														</div>
													</div>
												</div>

										        <!-- CHECK-OUT OVERDUE TABLE -->
												<div class="col-md-6">
													<div class="card">
														<div class="card-header">
															<h3 class="card-title">
																Check-Out Overdue
																<?php if (!empty($overdueCheckout) && $overdueCheckout > 0): ?>
																    <span class="badge bg-danger pulse">
																        &nbsp;&nbsp;<?= $overdueCheckout ?>&nbsp;&nbsp;
																    </span>
																<?php endif ?>
															</h3>
														</div>
														<div class="card-body table-responsive p-0">
															<table id="overdueTable" class="table table-hover">
																<thead>
																	<tr>
																		<th>Room No.</th>
																		<th>Guest Name</th>
																		<th>Check Out</th>
																		<th>Action</th>
																	</tr>
																</thead>
																<tbody>
																	<?php if (!empty($lateCheckouts)): ?>
																		<?php foreach ($lateCheckouts as $row): ?>
																			<tr>
								                                                <td><?= esc($row['room_no']) ?></td>
								                                                <td><?= esc($row['guest_name']) ?></td>
								                                                <td>
																				    <?= !empty($row['checkout_date'])
																				        ? date('d-m-Y', strtotime($row['checkout_date']))
																				        : '-' ?>
																				</td>
								                                                <td>
																				    <?php if (in_array($row['status'], ['ACTIVE'])): ?>
																				        <button class="btn btn-sm btn-danger btn-overdue"
																					            data-id="<?= $row['id'] ?>">
																					        <i class="fas fa-sign-out-alt"></i> Check-out
																					    </button>
																				    <?php else: ?>
																				        <span class="text-muted">-</span>
																				    <?php endif; ?>
																				</td>
								                                            </tr>
																		<?php endforeach ?>
																	<?php else: ?>
								                                        <tr>
								                                            <td colspan="4" class="text-center">
								                                                No data available
								                                            </td>
								                                        </tr>
								                                    <?php endif ?>
																</tbody>
															</table>
														</div>
													</div>
												</div>
										    </div>
							            </div>
						            </div>
						        </div>
					        </div>
					        <!-- ./row -->
					    </div>
				    </section>
				    <!-- /.main content -->
				</div>
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

				<script>
					$(document).on('click', '.btn-checkout', function () {
					    let id = $(this).data('id');

					    Swal.fire({
					        title: 'Check-out Guest?',
					        text: 'IPTV & Room will be available again',
					        icon: 'warning',
					        showCancelButton: true,
					        confirmButtonText: 'Yes, check-out'
					    }).then((result) => {
					        if (result.isConfirmed) {
					            $.post("<?= base_url('admin/guests/checkout') ?>", {
					                id: id,
					                <?= csrf_token() ?>: "<?= csrf_hash() ?>"
					            }, function (res) {
					                if (res.status) {
					                    Swal.fire({
					                        icon: 'success',
					                        title: 'Success',
					                        text: res.message
					                    });

					                    // reload datatable / dashboard
					                    if (typeof table !== 'undefined') {
					                        table.ajax.reload(null, false);
					                    } else {
					                        location.reload();
					                    }
					                } else {
					                    Swal.fire('Error', res.message, 'error');
					                }
					            }, 'json');
					        }
					    });
					});

					$(document).on('click', '.btn-overdue', function () {
					    let id = $(this).data('id');

					    Swal.fire({
					        title: 'Check-out Guest?',
					        text: 'IPTV & Room will be available again',
					        icon: 'warning',
					        showCancelButton: true,
					        confirmButtonText: 'Yes, check-out'
					    }).then((result) => {
					        if (result.isConfirmed) {
					            $.post("<?= base_url('admin/guests/checkout') ?>", {
					                id: id,
					                <?= csrf_token() ?>: "<?= csrf_hash() ?>"
					            }, function (res) {
					                if (res.status) {
					                    Swal.fire({
					                        icon: 'success',
					                        title: 'Success',
					                        text: res.message
					                    });

					                    // reload datatable / dashboard
					                    if (typeof table !== 'undefined') {
					                        table.ajax.reload(null, false);
					                    } else {
					                        location.reload();
					                    }
					                } else {
					                    Swal.fire('Error', res.message, 'error');
					                }
					            }, 'json');
					        }
					    });
					});
				</script>
			<?= $this->endSection() ?>