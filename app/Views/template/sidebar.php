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
                <?php if (auth()->user()->can('sales.create', 'sales.view', 'quotes.create', 'quotes.view')) : ?>
                    <li class="submenu-open">
                        <h6 class="submenu-hdr">Sales</h6>
                        <ul>
                            <?php if (auth()->user()->can('sales.create')) : ?>
                                <li class="<?= getActiveUrl("sales/pos", "active"); ?>"><a href="<?= site_url('sales/pos') ?>"><i data-feather="hard-drive"></i><span>POS</a></li>
                            <?php endif ?>
                            <?php if (auth()->user()->can('sales.view')) : ?>
                                <li class="<?= getActiveUrl("sales", "active"); ?>"><a href="<?= site_url('sales') ?>"><i data-feather="shopping-cart"></i><span>Sales</span></a></li>
                            <?php endif ?>
                            <?php if (auth()->user()->can('sales-returns.create', 'sales-returns.view')) : ?>
                                <li class="submenu">
                                    <a href="javascript:void(0);" class="<?= getActiveUrl("sales/returns*", "active subdrop") ?>">
                                        <i data-feather="corner-up-left"></i>
                                        <span>Sales Returns</span><span class="menu-arrow"></span>
                                    </a>
                                    <ul>
                                        <?php if (auth()->user()->can('sales-returns.create')) : ?>
                                            <li><a class="<?= getActiveUrl("sales/returns/create"); ?>" href="<?= site_url('sales/returns/create') ?>"><span>Create Return</span></a></li>
                                        <?php endif ?>
                                        <?php if (auth()->user()->can('sales-returns.view')) : ?>
                                            <li><a class="<?= getActiveUrl("sales/returns"); ?>" href="<?= site_url('sales/returns') ?>">Sales Returns</a></li>
                                        <?php endif ?>
                                    </ul>
                                </li>
                            <?php endif ?>
                            <?php if (auth()->user()->can('quotes.create', 'quotes.view')) : ?>
                                <li class="submenu">
                                    <a href="javascript:void(0);" class="<?= getActiveUrl("quotes.*", "active subdrop") ?> <?= getActiveUrl("quotes", "active subdrop") ?>">
                                        <i data-feather="file"></i>
                                        <span>Quotations</span><span class="menu-arrow"></span>
                                    </a>
                                    <ul>
                                        <?php if (auth()->user()->can('quotes.create')) : ?>
                                            <li><a class="<?= getActiveUrl("quotes/create"); ?>" href="<?= site_url('quotes/create') ?>"><span>Quotation</span></a></li>
                                        <?php endif ?>
                                        <?php if (auth()->user()->can('quotes.view')) : ?>
                                            <li><a class="<?= getActiveUrl("quotes"); ?>" href="<?= site_url('quotes') ?>">List Quotation</a></li>
                                        <?php endif ?>
                                    </ul>
                                </li>
                            <?php endif ?>
                        </ul>
                    </li>
                <?php endif ?>
                <?php if (auth()->user()->can('purchases.view', 'purchases.create', 'purchase-returns.view', 'purchase-returns.create')) : ?>
                    <li class="submenu-open">
                        <h6 class="submenu-hdr">Purchases</h6>
                        <ul>
                            <?php if (auth()->user()->can('purchases.create')) : ?>
                                <li class="<?= getActiveUrl("purchases/create", "active"); ?>">
                                    <a href="<?= site_url('purchases/create') ?>"> <i data-feather="file-minus"></i><span>Purchase Order</span></a>
                                </li>
                            <?php endif ?>
                            <?php if (auth()->user()->can('products.view')) : ?>
                                <li class="<?= getActiveUrl("purchases", "active"); ?>"><a href="<?= site_url('purchases') ?>"><i data-feather="shopping-bag"></i><span>Purchases</span></a></li>
                            <?php endif ?>
                            <?php if (auth()->user()->can('purchase-returns.view', 'purchase-returns.create')) : ?>
                                <li class="submenu">
                                    <a href="javascript:void(0);" class="<?= getActiveUrl("purchases/returns*", "active subdrop") ?>">
                                        <i data-feather="corner-up-left"></i>
                                        <span>Purchase Return</span><span class="menu-arrow"></span>
                                    </a>
                                    <ul>
                                        <?php if (auth()->user()->can('purchase-returns.create')) : ?>
                                            <li><a class="<?= getActiveUrl("purchases/returns/create", "active"); ?>" href="<?= site_url('purchases/returns/create') ?>"><span>Create Return</span></a></li>
                                        <?php endif ?>
                                        <?php if (auth()->user()->can('purchase-returns.view')) : ?>
                                            <li><a class="<?= getActiveUrl("purchases/returns", "active"); ?>" href="<?= site_url('purchases/returns') ?>">Purchase Returns</a></li>
                                        <?php endif ?>
                                    </ul>
                                </li>
                            <?php endif ?>
                        </ul>
                    </li>
                <?php endif ?>
                <?php if (auth()->user()->can(
                    'products.view',
                    'products.create',
                    'product-transfers.create',
                    'unit-transfers.create',
                    'adjustments.create',
                    'adjustments.view'
                )) : ?>
                    <li class="submenu-open">
                        <h6 class="submenu-hdr">Products</h6>
                        <ul>
                            <?php if (auth()->user()->can('products.view')) : ?>
                                <li class="<?= getActiveUrl("products", "active"); ?>"><a href="<?= site_url('products') ?>"><i data-feather="box"></i><span>Products</span></a></li>
                            <?php endif ?>
                            <?php if (auth()->user()->can('products.create')) : ?>
                                <li class="<?= getActiveUrl("products/create", "active"); ?>"><a href="<?= site_url('products/create') ?>"><i data-feather="plus-square"></i><span>Create Product</span></a></li>
                            <?php endif ?>
                            <?php if (auth()->user()->can('categories.view')) : ?>
                                <li class="<?= getActiveUrl("categories", "active"); ?>"><a href="<?= site_url('categories') ?>"><i data-feather="codepen"></i><span>Category</span></a></li>
                            <?php endif ?>
                            <?php if (auth()->user()->can('brands.view')) : ?>
                                <li class="<?= getActiveUrl("brands", "active"); ?>"><a href="<?= site_url('brands') ?>"><i data-feather="tag"></i><span>Brands</span></a></li>
                            <?php endif ?>
                            <?php if (auth()->user()->can('units.view')) : ?>
                                <li class="<?= getActiveUrl("units", "active"); ?>"><a href="<?= site_url('units') ?>"><i data-feather="speaker"></i><span>Units</span></a></li>
                            <?php endif ?>
                            <?php if (auth()->user()->can('products.print-barcode')) : ?>
                                <li hidden class="<?= getActiveUrl("products/print-barcode", "active"); ?>"><a href="<?= site_url('products/-print-barcode') ?>"><i data-feather="align-justify"></i><span>Print Barcode</span></a></li>
                            <?php endif ?>
                            <?php if (auth()->user()->can('products.import')) : ?>
                                <li class="<?= getActiveUrl("products/import", "active"); ?>"><a href="<?= site_url('products/import') ?>/importproduct"><i data-feather="minimize-2"></i><span>Import Products</span></a></li>
                            <?php endif ?>
                            <?php if (auth()->user()->can('product-transfers.create', 'unit-transfers.create')) : ?>
                                <li class="submenu">
                                    <a class="<?= getActiveUrl("transfers/*", "active subdrop"); ?>" href="javascript:void(0);">
                                        <i data-feather="shuffle"></i><span>Transfers</span><span class="menu-arrow"></span></a>
                                    <ul>
                                        <li><a class="<?= getActiveUrl("transfers/products", "active"); ?>" href="<?= site_url('transfers/products') ?>">Product Transfers</a></li>
                                        <li><a class="<?= getActiveUrl("transfers/units", "active"); ?>" href="<?= site_url('transfers/units') ?>">Unit Transfers</a></li>
                                    </ul>
                                </li>
                            <?php endif ?>
                            <?php if (auth()->user()->can('adjustments.create', 'adjustments.view')) : ?>
                                <li class="submenu">
                                    <a class="<?= getActiveUrl("adjustments*", "subdrop active") ?>" href="javascript:void(0);">
                                        <i data-feather="align-justify"></i>
                                        <span>Adjustments</span><span class="menu-arrow"></span>
                                    </a>
                                    <ul>
                                        <li><a class="<?= getActiveUrl("adjustments/create"); ?>" href="<?= site_url('adjustments/create') ?>">Create Adjustment</a></li>
                                        <li><a class="<?= getActiveUrl("adjustments"); ?>" href="<?= site_url("adjustments") ?>">List Adjustment</a></li>
                                    </ul>
                                </li>
                            <?php endif ?>
                            <?php if (auth()->user()->can('stocks.view')) : ?>
                                <li class="submenu">
                                    <a class="<?= getActiveUrl("inventory*", "subdrop active") ?>" href="javascript:void(0);">
                                        <i data-feather="align-justify"></i>
                                        <span>Inventory</span><span class="menu-arrow"></span>
                                    </a>
                                    <ul>
                                        <li><a class="<?= getActiveUrl("inventory/short-stocks"); ?>" href="<?= site_url('inventory/short-stocks') ?>">Short Stocks</a></li>
                                        <li><a class="<?= getActiveUrl("inventory/outofstocks"); ?>" href="<?= site_url('inventory/outofstocks') ?>">Out of Stocks</a></li>
                                        <li><a class="<?= getActiveUrl("inventory/instocks"); ?>" href="<?= site_url('inventory/instocks') ?>">In Stocks</a></li>
                                    </ul>
                                </li>
                            <?php endif ?>
                        </ul>
                    </li>
                <?php endif ?>
                <?php if (auth()->user()->can(
                    'expenses.create',
                    'expenses.view',
                    'expense-categories.create',
                    'expense-categories.view',
                    'customer-ledgers.view',
                    'supplier-ledgers.view',
                    'closing.view',
                    'closing.create',
                    'cashup.view',
                    'cashup.create'
                )) : ?>
                    <li class="submenu-open">
                        <h6 class="submenu-hdr">Finance & Accounts</h6>
                        <ul>
                            <?php if (auth()->user()->can(
                                'expenses.create',
                                'expenses.view',
                                'expense-categories.create',
                                'expense-categories.view'
                            )) : ?>
                                <li class="submenu">
                                    <a class="<?= getActiveUrl("expenses*",  "active subdrop") ?>" href="javascript:void(0);">
                                        <i data-feather="file-text"></i>
                                        <span>Manage Expense</span><span class="menu-arrow"></span>
                                    </a>
                                    <ul>
                                        <?php if (auth()->user()->can('expenses.create', 'expenses.view')) : ?>
                                            <li><a class="<?= getActiveUrl("expenses", "active"); ?>" href="<?= site_url('expenses') ?>">Expenses</a></li>
                                        <?php endif ?>
                                        <?php if (auth()->user()->can('expense-categories.create', 'expense-categories.view')) : ?>
                                            <li><a class="<?= getActiveUrl("expense/categories", "active"); ?>" href="<?= site_url('expenses/categories') ?>">Expense Category</a></li>
                                        <?php endif ?>
                                    </ul>
                                </li>
                            <?php endif ?>
                            <?php if (auth()->user()->can('supplier-ledgers.view', 'customer-ledgers.view')) : ?>
                                <li class="submenu">
                                    <a class="<?= getActiveUrl("account-debts/*", "subdrop active") ?>" href="javascript:void(0);">
                                        <i data-feather="book"></i>
                                        <span>Account Debts</span><span class="menu-arrow"></span>
                                    </a>
                                    <ul>
                                        <?php if (auth()->user()->can('customer-ledgers.view')) : ?>
                                            <li><a class="<?= getActiveUrl("account-debts/customers"); ?>" href="<?= site_url('account-debts/customers') ?>">Customers Debt</a></li>
                                        <?php endif ?>
                                        <?php if (auth()->user()->can('supplier-ledgers.view')) : ?>
                                            <li><a class="<?= getActiveUrl("account-debts/suppliers"); ?>" href="<?= site_url("account-debts/suppliers") ?>">Suppliers Debt</a></li>
                                        <?php endif ?>
                                    </ul>
                                </li>
                            <?php endif ?>
                            <?php if (auth()->user()->can('closing.create', 'closing.view')) : ?>
                                <li class="submenu">
                                    <a class="<?= getActiveUrl("closing*", "subdrop active") ?>" href="javascript:void(0);">
                                        <i data-feather="airplay"></i>
                                        <span>Closing</span><span class="menu-arrow"></span>
                                    </a>
                                    <ul>
                                        <?php if (auth()->user()->can('closing.create')) : ?>
                                            <li><a class="<?= getActiveUrl("closing/store"); ?>" href="<?= site_url("closing/store") ?>">Store Closing</a></li>
                                        <?php endif ?>
                                        <?php if (auth()->user()->can('closing.view')) : ?>
                                            <li><a class="<?= getActiveUrl("closing"); ?>" href="<?= site_url("closing") ?>">List Closings</a></li>
                                        <?php endif ?>
                                    </ul>
                                </li>
                            <?php endif ?>
                            <?php if (auth()->user()->can('cashup.view', 'cashup.create')) : ?>
                                <li class="<?= getActiveUrl("cashup", "active"); ?>"><a href="<?= site_url('cashup') ?>"><i data-feather="upload"></i><span>Cashup</span></a></li>
                            <?php endif ?>
                        </ul>
                    </li>
                <?php endif ?>
                <?php if (auth()->user()->can('customers.view', 'suppliers.view')) : ?>
                    <li class="submenu-open">
                        <h6 class="submenu-hdr">Peoples</h6>
                        <ul>
                            <?php if (auth()->user()->can('customers.view')) : ?>
                                <li class="<?= getActiveUrl("customers", "active"); ?>"><a href="<?= site_url('customers') ?>"><i data-feather="user"></i><span>Customers</span></a></li>
                            <?php endif ?>
                            <?php if (auth()->user()->can('suppliers.view')) : ?>
                                <li class="<?= getActiveUrl("suppliers", "active"); ?>"><a href="<?= site_url('suppliers') ?>"><i data-feather="users"></i><span>Suppliers</span></a></li>
                            <?php endif ?>
                        </ul>
                    </li>
                <?php endif ?>
                <?php if (auth()->user()->can('sales.report', 'purchases.report', 'stocks.report')) : ?>
                    <li class="submenu-open">
                        <h6 class="submenu-hdr">Reports</h6>
                        <ul>
                            <?php if (auth()->user()->can('sales.report')) : ?>
                                <li class="<?= getActiveUrl("reports/sales", "active"); ?>"><a href="<?= site_url('reports/sales') ?>"><i data-feather="bar-chart-2"></i><span>Sales Report</span></a></li>
                            <?php endif ?>
                            <?php if (auth()->user()->can('purchases.report')) : ?>
                                <li class="<?= getActiveUrl("purchases/report", "active"); ?>"><a href="<?= site_url('reports/purchases') ?>"><i data-feather="pie-chart"></i><span>Purchase report</span></a></li>
                            <?php endif ?>
                            <?php if (auth()->user()->can('stocks.report')) : ?>
                                <li class="<?= getActiveUrl("reports/stocks", "active"); ?>"><a href="<?= site_url('reports/stocks') ?>"><i data-feather="credit-card"></i><span>Inventory Report</span></a></li>
                            <?php endif ?>
                        </ul>
                    </li>
                <?php endif ?>
                <?php if (auth()->user()->can('users.view', 'users.create', 'users.edit')) : ?>
                    <li class="submenu-open">
                        <h6 class="submenu-hdr">User Management</h6>
                        <ul>
                            <li class="submenu">
                                <a class="<?= getActiveUrl("users/*", "subdrop active"); ?> <?= getActiveUrl("users", "subdrop active"); ?>" href="javascript:void(0);"><i data-feather="users"></i><span>Manage Users</span><span class="menu-arrow"></span></a>
                                <ul>
                                    <?php if (auth()->user()->can('users.create')) : ?>
                                        <li><a class="<?= getActiveUrl("users/create", "active"); ?>" href="<?= site_url('users/create') ?>">New User </a></li>
                                    <?php endif ?>
                                    <?php if (auth()->user()->can('users.view')) : ?>
                                        <li><a class="<?= getActiveUrl("users", "active"); ?>" href="<?= site_url('users') ?>">Users List</a></li>
                                    <?php endif ?>
                                </ul>
                            </li>
                        </ul>
                    </li>
                <?php endif ?>
                <?php if (auth()->user()->can('general-settings.*', 'email-settings.*', 'payment-settings.*', 'permission-settings.*')) : ?>
                    <li class="submenu-open">
                        <h6 class="submenu-hdr">Settings</h6>
                        <ul>
                            <li class="<?= getActiveUrl("stores", "active"); ?>"><a href="<?= site_url('stores') ?>"><i data-feather="home"></i><span>Stores</span></a></li>
                            <li class="submenu">
                                <a href="javascript:void(0);" class="<?= getActiveUrl("settings/*", "subdrop active"); ?> <?= getActiveUrl("settings", "subdrop active"); ?>">
                                    <i data-feather="settings"></i><span>Settings</span><span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <?php if (auth()->user()->can('general-settings.*')) : ?>
                                        <li class="<?= getActiveUrl("settings/general", "active"); ?>"><a href="<?= site_url('settings/general') ?>">General Settings</a></li>
                                    <?php endif ?>
                                    <?php if (auth()->user()->can('permission-settings.*')) : ?>
                                        <li class="<?= getActiveUrl("settings/groups", "active"); ?>"><a href="<?= site_url('settings/groups') ?>">Group Permissions</a></li>
                                    <?php endif ?>
                                    <?php if (auth()->user()->can('email-settings.*')) : ?>
                                        <li class="<?= getActiveUrl("settings/email", "active"); ?>"><a href="<?= site_url('settings/email') ?>">Email Settings</a></li>
                                    <?php endif ?>
                                    <?php if (auth()->user()->can('sms-settings.*')) : ?>
                                        <li class="<?= getActiveUrl("settings/sms", "active"); ?>"><a href="<?= site_url('settings/sms') ?>">SMS Settings</a></li>
                                    <?php endif ?>
                                    <?php if (auth()->user()->can('payment-settings.*')) : ?>
                                        <li class="<?= getActiveUrl("settings/payment", "active"); ?>"><a href="<?= site_url('settings/payment') ?>">Payment Settings</a></li>
                                    <?php endif ?>
                                </ul>
                            </li>
                            <li>
                                <a href="<?= site_url('logout') ?>"><i data-feather="log-out"></i><span>Logout</span> </a>
                            </li>
                        </ul>
                    </li>
                <?php endif ?>
            </ul>
        </div>
    </div>
</div>