<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Main</h6>
                    <ul>
                        <li class="<?= getActiveUrl("/", "active"); ?>">
                            <a href="<?= site_url() ?>"><i data-feather="grid"></i><span>Dashboard</span></a>
                        </li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Sales</h6>
                    <ul>
                        <li class="<?= getActiveUrl("sales/pos", "active"); ?>"><a href="<?= site_url('sales/pos') ?>"><i data-feather="hard-drive"></i><span>POS</a></li>
                        <li class="<?= getActiveUrl("quotes/create", "active"); ?>"><a href="<?= site_url('quotes/create') ?>"><i data-feather="save"></i><span>Quotation</span></a></li>
                        <li class="<?= getActiveUrl("sales", "active"); ?>"><a href="<?= site_url('sales') ?>"><i data-feather="shopping-cart"></i><span>Sales</span></a></li>

                        <li class="submenu">
                            <a href="javascript:void(0);" class="<?= getActiveUrl("sales/returns/*", "active subdrop") ?> <?= getActiveUrl("sales/returns", "active subdrop") ?>">
                                <i data-feather="corner-up-left"></i>
                                <span>Sales Returns</span><span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a class="<?= getActiveUrl("sales/returns/create"); ?>" href="<?= site_url('sales/returns/create') ?>"><span>Create Return</span></a></li>
                                <li><a class="<?= getActiveUrl("sales/returns"); ?>" href="<?= site_url('sales/returns') ?>">Sales Returns</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Purchases</h6>
                    <ul>
                        <li class="<?= getActiveUrl("purchases/create", "active"); ?>">
                            <a href="<?= site_url('purchases/create') ?>"> <i data-feather="file-minus"></i><span>Purchase Order</span></a>
                        </li>
                        <li class="<?= getActiveUrl("purchases", "active"); ?>"><a href="<?= site_url('purchases') ?>"><i data-feather="shopping-bag"></i><span>Purchases</span></a></li>
                        <li class="submenu">
                            <a href="javascript:void(0);" class="<?= getActiveUrl("purchases/returns/*", "active subdrop") ?> <?= getActiveUrl("purchases/returns", "active subdrop") ?>">
                                <i data-feather="corner-up-left"></i>
                                <span>Purchase Return</span><span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a class="<?= getActiveUrl("purchases/returns/create", "active"); ?>" href="<?= site_url('purchases/returns/create') ?>"><span>Create Return</span></a></li>
                                <li><a class="<?= getActiveUrl("purchases/returns", "active"); ?>" href="<?= site_url('purchases/returns') ?>">Purchase Returns</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li class="submenu-open">
                    <h6 class="submenu-hdr">Products</h6>
                    <ul>
                        <li class="<?= getActiveUrl("products", "active"); ?>"><a href="<?= site_url('products') ?>"><i data-feather="box"></i><span>Products</span></a></li>
                        <li class="<?= getActiveUrl("products/create", "active"); ?>"><a href="<?= site_url('products/create') ?>"><i data-feather="plus-square"></i><span>Create Product</span></a></li>
                        <li class="<?= getActiveUrl("categories", "active"); ?>"><a href="<?= site_url('categories') ?>"><i data-feather="codepen"></i><span>Category</span></a></li>
                        <li class="<?= getActiveUrl("brands", "active"); ?>"><a href="<?= site_url('brands') ?>"><i data-feather="tag"></i><span>Brands</span></a></li>
                        <li class="<?= getActiveUrl("units", "active"); ?>"><a href="<?= site_url('units') ?>"><i data-feather="speaker"></i><span>Units</span></a></li>
                        <li hidden class="<?= getActiveUrl("products/print-barcode", "active"); ?>"><a href="<?= site_url('products/-print-barcode') ?>"><i data-feather="align-justify"></i><span>Print Barcode</span></a></li>
                        <li class="<?= getActiveUrl("products/import", "active"); ?>"><a href="<?= site_url('products/import') ?>/importproduct"><i data-feather="minimize-2"></i><span>Import Products</span></a></li>
                        <li class="submenu">
                            <a class="<?= getActiveUrl("transfers/*", "active subdrop"); ?>" href="javascript:void(0);">
                                <i data-feather="shuffle"></i><span>Transfers</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a class="<?= getActiveUrl("transfers/products", "active"); ?>" href="<?= site_url('transfers/products') ?>">Product Transfers</a></li>
                                <li><a class="<?= getActiveUrl("transfers/units", "active"); ?>" href="<?= site_url('transfers/units') ?>">Unit Transfers</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a class="<?= getActiveUrl("adjustments/*", "subdrop active") ?>" href="javascript:void(0);">
                                <i data-feather="align-justify"></i>
                                <span>Adjustments</span><span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a class="<?= getActiveUrl("adjustments/create"); ?>" href="<?= site_url('adjustments/create') ?>">Create Adjustment</a></li>
                                <li><a class="<?= getActiveUrl("adjustments"); ?>" href="<?= site_url("adjustments") ?>">List Adjustment</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Finance & Accounts</h6>
                    <ul>
                        <li class="submenu">
                            <a class="<?= getActiveUrl("expenses", true) || getActiveUrl("expenses/*", true) ? "active subdrop"
                                            : (getActiveUrl("expense-categories", true) || getActiveUrl("expense-categories/*", true) ? "active subdrop" : '') ?>" href="javascript:void(0);">
                                <i data-feather="file-text"></i>
                                <span>Manage Expense</span><span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a class="<?= getActiveUrl("expenses", "active"); ?>" href="<?= site_url('expenses') ?>">Expenses</a></li>
                                <li><a class="<?= getActiveUrl("expense-categories", "active"); ?>" href="<?= site_url('expense-categories') ?>">Expense Category</a></li>
                            </ul>
                        </li>

                        <li class="submenu">
                            <a class="<?= getActiveUrl("account-debts/*", "subdrop active") ?>" href="javascript:void(0);">
                                <i data-feather="book"></i>
                                <span>Account Debts</span><span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a class="<?= getActiveUrl("account-debts/customers"); ?>" href="<?= site_url('account-debts/customers') ?>">Customers Debt</a></li>
                                <li><a class="<?= getActiveUrl("account-debts/suppliers"); ?>" href="<?= site_url("account-debts/suppliers") ?>">Suppliers Debt</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a class="<?= getActiveUrl("closing/*", "subdrop active") ?> <?= getActiveUrl("closing", "subdrop active") ?>" href="javascript:void(0);">
                                <i data-feather="airplay"></i>
                                <span>Closing</span><span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a class="<?= getActiveUrl("closing/store"); ?>" href="<?= site_url("closing/store") ?>">Store Closing</a></li>
                                <li><a class="<?= getActiveUrl("closing"); ?>" href="<?= site_url("closing") ?>">List Closings</a></li>
                            </ul>
                        </li>
                        <li class="<?= getActiveUrl("cashup", "active"); ?>"><a href="<?= site_url('cashup') ?>"><i data-feather="upload"></i><span>Cashup</span></a></li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Peoples</h6>
                    <ul>
                        <li class="<?= getActiveUrl("customers", "active"); ?>"><a href="<?= site_url('customers') ?>"><i data-feather="user"></i><span>Customers</span></a></li>
                        <li class="<?= getActiveUrl("suppliers", "active"); ?>"><a href="<?= site_url('suppliers') ?>"><i data-feather="users"></i><span>Suppliers</span></a></li>
                        <li class="<?= getActiveUrl("users", "active"); ?>"><a href="<?= site_url('users') ?>"><i data-feather="user-check"></i><span>Users</span></a></li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Reports</h6>
                    <ul>
                        <li class="<?= getActiveUrl("sales/report", "active"); ?>"><a href="<?= site_url('sales/report') ?>"><i data-feather="bar-chart-2"></i><span>Sales Report</span></a></li>
                        <li class="<?= getActiveUrl("purchases/report", "active"); ?>"><a href="<?= site_url('purchases/order-report') ?>"><i data-feather="pie-chart"></i><span>Purchase report</span></a></li>
                        <li class="<?= getActiveUrl("inventory/report", "active"); ?>"><a href="<?= site_url('inventory/report') ?>"><i data-feather="credit-card"></i><span>Inventory Report</span></a></li>
                        <li class="<?= getActiveUrl("purchases/report", "active"); ?>"><a href="<?= site_url('purchases/report') ?>"><i data-feather="bar-chart"></i><span>Purchase Report</span></a></li>
                        <li class="<?= getActiveUrl("suppliers/report", "active"); ?>"><a href="<?= site_url('suppliers/report') ?>"><i data-feather="database"></i><span>Supplier Report</span></a></li>
                        <li class="<?= getActiveUrl("customers/report", "active"); ?>"><a href="<?= site_url('customers/report') ?>"><i data-feather="pie-chart"></i><span>Customer Report</span></a></li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">User Management</h6>
                    <ul>
                        <li class="submenu">
                            <a class="<?= getActiveUrl("users/*", "subdrop active"); ?> <?= getActiveUrl("users", "subdrop active"); ?>" href="javascript:void(0);"><i data-feather="users"></i><span>Manage Users</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a class="<?= getActiveUrl("users/create", "active"); ?>" href="<?= site_url('users/create') ?>">New User </a></li>
                                <li><a class="<?= getActiveUrl("users", "active"); ?>" href="<?= site_url('users') ?>">Users List</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Settings</h6>
                    <ul>
                        <li class="<?= getActiveUrl("stores", "active"); ?>"><a href="<?= site_url('stores') ?>"><i data-feather="home"></i><span>Stores</span></a></li>
                        <li class="submenu">
                            <a href="javascript:void(0);" class="<?= getActiveUrl("settings/*", "subdrop active"); ?> <?= getActiveUrl("settings", "subdrop active"); ?>">
                                <i data-feather="settings"></i><span>Settings</span><span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li class="<?= getActiveUrl("settings/general", "active"); ?>"><a href="<?= site_url('settings/general') ?>">General Settings</a></li>
                                <li class="<?= getActiveUrl("settings/group-permissions", "active"); ?>"><a href="<?= site_url('settings/group-permissions') ?>">Group Permissions</a></li>
                                <li class="<?= getActiveUrl("settings/email", "active"); ?>"><a href="<?= site_url('settings/email') ?>">Email Settings</a></li>
                                <li class="<?= getActiveUrl("settings/sms", "active"); ?>"><a href="<?= site_url('settings/sms') ?>">SMS Settings</a></li>
                                <li class="<?= getActiveUrl("settings/payment", "active"); ?>"><a href="<?= site_url('settings/payment') ?>">Payment Settings</a></li>
                                </ul>
                        </li>
                        <li>
                            <a href="<?= site_url('logout') ?>"><i data-feather="log-out"></i><span>Logout</span> </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>