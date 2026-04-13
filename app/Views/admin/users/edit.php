			<?= $this->extend('layouts/admin') ?>
			<?= $this->section('content') ?>
				<div class="content-wrapper">
				    <!-- Content Header (Page header) -->
				    <section class="content-header">
				      	<div class="container-fluid">
				        	<div class="row mb-2">
				          		<div class="col-sm-6">
				            		<h1>User Management</h1>
				          		</div>
				          		<div class="col-sm-6">
				            		<ol class="breadcrumb float-sm-right">
						              	<li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
						              	<li class="breadcrumb-item active">User Management</li>
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
							                <h3 class="card-title">Edit User</h3>
							            </div>
						              	<div class="card-body pad table-responsive">
						              		<form method="post" action="<?= base_url('admin/users/update/'.$user['id']) ?>">
												<?= csrf_field() ?>
												<div class="row">
								                    <div class="col-sm-6">
								                      	<div class="form-group">
														    <label>Name</label>
														    <input type="text" name="name" class="form-control" value="<?= esc($user['name']) ?>">
														</div>
								                    </div>
								                    <div class="col-sm-6">
								                      	<div class="form-group">
														    <label>Username/email</label>
														    <input type="email" name="email" value="<?= esc($user['email']) ?>" class="form-control">
														</div>
								                    </div>
								                </div>
												<div class="row">
								                    <div class="col-sm-6">
								                      	<div class="form-group">
														    <label>Role</label>
														    <select name="role" class="form-control">
														        <option value="admin" <?= $user['role']=='admin'?'selected':'' ?>>Admin</option>
														        <option value="fnb" <?= $user['role']=='fnb'?'selected':'' ?>>FnB</option>
														        <option value="kitchen" <?= $user['role']=='kitchen'?'selected':'' ?>>Kitchen</option>
														        <option value="hk" <?= $user['role']=='hk'?'selected':'' ?>>Housekeeping</option>
														        <option value="fo" <?= $user['role']=='fo'?'selected':'' ?>>Front Office</option>
														        <option value="eng" <?= $user['role']=='eng'?'selected':'' ?>>Engineering</option>
														        <option value="hrd" <?= $user['role']=='hrd'?'selected':'' ?>>Human Resources</option>
														        <option value="it" <?= $user['role']=='it'?'selected':'' ?>>Information Technology</option>
														        <option value="gm" <?= $user['role']=='gm'?'selected':'' ?>>General Manager</option>
														    </select>
														</div>
								                    </div>
								                    <div class="col-sm-6">
								                      	<div class="form-group">
														    <label>Password (kosongkan jika tidak diganti)</label>
														    <input type="password" name="password" class="form-control">
														</div>
								                    </div>
								                </div>
												
												<button class="btn btn-primary">Update</button>
												<a href="<?= base_url('admin/users') ?>" class="btn btn-secondary">Cancel</a>
											</form>
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
			<?= $this->endSection() ?>
