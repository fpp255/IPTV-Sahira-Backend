            <?php $uri = service('uri')->getSegment(2); ?>

            <aside class="main-sidebar elevation-4 sidebar-dark-primary">
                <!-- Brand Logo -->
                <a href="#" class="brand-link">
                    <img src="<?= base_url('themesadmin/dist/img/logosahira.png') ?>" class="brand-image img-circle elevation-3" style="opacity: .8">
                    <span class="brand-text font-weight-light">SBH Paledang</span>
                </a>

                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Sidebar user (optional) -->
                    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                        <div class="image">
                            <?php if (session()->get('role') === 'admin'): ?>
                                <img src="<?= base_url('themesadmin/dist/img/fotoadmin.jpg') ?>" class="img-circle elevation-2" alt="User Image">
                            <?php else: ?>
                                <img src="<?= base_url('themesadmin/dist/img/user-default.jpg') ?>" class="img-circle elevation-2" alt="User Image">
                            <?php endif; ?>
                        </div>
                        <div class="info">
                            <a href="#" class="d-block"><?= esc(session()->get('user_name')) ?></a>
                        </div>
                    </div>

                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                            <?php if (can(['admin','fnb','fo','hk','eng','it'])): ?>
                            <li class="nav-item">
                                <a href="<?= base_url('admin/dashboard') ?>" class="nav-link <?= ($uri=='dashboard')?'active':'' ?>">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>Dashboard</p>
                                </a>
                            </li>
                            <?php endif; ?>

                            <?php if (can(['admin','fo','hk','it'])): ?>
                            <li class="nav-item">
                                <a href="<?= base_url('admin/guests') ?>"
                                   class="nav-link <?= ($uri=='guests')?'active':'' ?>">
                                    <i class="nav-icon fas fa-user-check"></i>
                                    <p>Guest Management</p>
                                </a>
                            </li>
                            <?php endif; ?>

                            <?php if (can(['admin','fnb'])): ?>
                            <?php
                                $uri2 = service('uri')->getSegment(2);
                                $uri3 = service('uri')->getSegment(3); 
                                $isOrderActive = in_array($uri2, ['orders', 'payment']);
                            ?>
                            <li class="nav-item">
                                <a href="<?= base_url('admin/orders') ?>"
                                   class="nav-link <?= $isOrderActive ? 'active' : '' ?>">
                                    <i class="nav-icon fas fa-receipt"></i>
                                    <p>Orders</p>
                                </a>
                            </li>
                            <?php endif; ?>

                            <?php if (can(['admin','hk'])): ?>
                            <li class="nav-item">
                                <a href="<?= base_url('admin/hkorders') ?>"
                                   class="nav-link <?= ($uri=='hkorders')?'active':'' ?>">
                                    <i class="nav-icon fas fa-broom"></i>
                                    <p>HK Service</p>
                                </a>
                            </li>
                            <?php endif; ?>

                            <!-- TV MANAGEMENT -->
                            <?php if (can(['admin','eng','it'])): ?>
                            <li class="nav-item">
                                <a href="<?= base_url('admin/tv-devices') ?>"
                                   class="nav-link <?= ($uri=='tv-devices')?'active':'' ?>">
                                    <i class="nav-icon fas fa-tv"></i>
                                    <p>TV Management</p>
                                </a>
                            </li>
                            <?php endif; ?>

                            <!-- MENU MANAGEMENT -->
                            <?php if (can(['admin','fnb'])): ?>
                            <li class="nav-item
                                <?= in_array(service('uri')->getSegment(2), ['menus','menu-categories','menu-variants']) ? 'menu-open' : '' ?>">
                                
                                <a href="#" class="nav-link
                                    <?= in_array(service('uri')->getSegment(2), ['menus','menu-categories','menu-variants']) ? 'active' : '' ?>">
                                    <i class="nav-icon fas fa-utensils"></i>
                                    <p>
                                        Menu Management
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>

                                <ul class="nav nav-treeview">

                                    <!-- MENU -->
                                    <li class="nav-item">
                                        <a href="<?= base_url('admin/menus') ?>"
                                           class="nav-link <?= service('uri')->getSegment(2) === 'menus' ? 'active' : '' ?>">
                                            <i class="fas fa-hamburger nav-icon"></i>
                                            <p>Menus</p>
                                        </a>
                                    </li>

                                    <!-- MENU CATEGORIES -->
                                    <li class="nav-item
                                        <?= in_array(service('uri')->getSegment(3), ['sort']) ||
                                           service('uri')->getSegment(2) === 'menu-categories'
                                            ? 'menu-open' : '' ?>">

                                        <a href="#" class="nav-link
                                            <?= service('uri')->getSegment(2) === 'menu-categories'
                                                ? 'active' : '' ?>">
                                            <i class="fas fa-layer-group nav-icon"></i>
                                            <p>
                                                Categories
                                                <i class="right fas fa-angle-left"></i>
                                            </p>
                                        </a>

                                        <ul class="nav nav-treeview">
                                            <!-- LIST CATEGORY -->
                                            <li class="nav-item">
                                                <a href="<?= base_url('admin/menu-categories') ?>"
                                                   class="nav-link
                                                   <?= service('uri')->getSegment(2) === 'menu-categories'
                                                       && service('uri')->getSegment(3) === ''
                                                        ? 'active' : '' ?>">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Category List</p>
                                                </a>
                                            </li>

                                            <!-- SORT CATEGORY -->
                                            <li class="nav-item">
                                                <a href="<?= base_url('admin/menu-categories/sort') ?>"
                                                   class="nav-link
                                                   <?= service('uri')->getSegment(3) === 'sort'
                                                        ? 'active' : '' ?>">
                                                    <i class="fas fa-sort nav-icon"></i>
                                                    <p>Sort Categories</p>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>

                                    <!-- MENU VARIANTS -->
                                    <li class="nav-item">
                                        <a href="<?= base_url('admin/menu-variants') ?>"
                                           class="nav-link <?= service('uri')->getSegment(2) === 'menu-variants' ? 'active' : '' ?>">
                                            <i class="fas fa-tags nav-icon"></i>
                                            <p>Variants</p>
                                        </a>
                                    </li>

                                </ul>
                            </li>
                            <?php endif; ?>

                            <!-- HK MANAGEMENT -->
                            <?php if (can(['admin','hk'])): ?>
                            <li class="nav-item
                                <?= in_array(service('uri')->getSegment(2), ['hkitems','hk-categories']) ? 'menu-open' : '' ?>">
                                <a href="#" class="nav-link
                                    <?= in_array(service('uri')->getSegment(2), ['hkitems','hk-categories']) ? 'active' : '' ?>">
                                    <i class="nav-icon fas fa-bed"></i>
                                    <p>
                                        HK Management
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>

                                <ul class="nav nav-treeview">
                                    <!-- ITEMS -->
                                    <li class="nav-item">
                                        <a href="<?= base_url('admin/hkitems') ?>"
                                           class="nav-link <?= service('uri')->getSegment(2) === 'hkitems' ? 'active' : '' ?>">
                                            <i class="fas fa-retweet nav-icon"></i>
                                            <p>Items</p>
                                        </a>
                                    </li>

                                    <!-- HK CATEGORIES -->
                                    <li class="nav-item
                                        <?= in_array(service('uri')->getSegment(3), ['sort']) ||
                                           service('uri')->getSegment(2) === 'hk-categories'
                                            ? 'menu-open' : '' ?>">

                                        <a href="#" class="nav-link
                                            <?= service('uri')->getSegment(2) === 'hk-categories'
                                                ? 'active' : '' ?>">
                                            <i class="fas fa-layer-group nav-icon"></i>
                                            <p>
                                                Categories
                                                <i class="right fas fa-angle-left"></i>
                                            </p>
                                        </a>

                                        <ul class="nav nav-treeview">
                                            <!-- LIST CATEGORY -->
                                            <li class="nav-item">
                                                <a href="<?= base_url('admin/hk-categories') ?>"
                                                   class="nav-link
                                                   <?= service('uri')->getSegment(2) === 'hk-categories'
                                                       && service('uri')->getSegment(3) === ''
                                                        ? 'active' : '' ?>">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Category List</p>
                                                </a>
                                            </li>

                                            <!-- SORT CATEGORY -->
                                            <li class="nav-item">
                                                <a href="<?= base_url('admin/hk-categories/sort') ?>"
                                                   class="nav-link
                                                   <?= service('uri')->getSegment(3) === 'sort'
                                                        ? 'active' : '' ?>">
                                                    <i class="fas fa-sort nav-icon"></i>
                                                    <p>Sort Categories</p>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <?php endif; ?>

                            <?php if (can('admin')): ?>
                            <li class="nav-item">
                                <a href="<?= base_url('admin/users') ?>"
                                   class="nav-link <?= ($uri=='users')?'active':'' ?>">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>User Management</p>
                                </a>
                            </li>
                            <?php endif; ?>

                            <li class="nav-item">
                                <a href="<?= base_url('logout') ?>" class="nav-link">
                                    <i class="nav-icon fas fa-sign-out-alt"></i>
                                    <p>Logout</p>
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <!-- /.sidebar-menu -->
                </div>
                <!-- /.sidebar -->
            </aside>