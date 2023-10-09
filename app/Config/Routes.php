<?php

use App\Controllers\AdjustmentController;
use App\Controllers\BrandController;
use App\Controllers\CategoryController;
use App\Controllers\ClosingController;
use App\Controllers\CustomerController;
use App\Controllers\CustomerLedgerController;
use App\Controllers\ExpenseCategoryController;
use App\Controllers\ExpenseController;
use App\Controllers\ImportController;
use App\Controllers\ProductController;
use App\Controllers\ProductTransferController;
use App\Controllers\ProductUnitTransferController;
use App\Controllers\PurchaseController;
use App\Controllers\PurchaseReturnController;
use App\Controllers\QuoteController;
use App\Controllers\SalesController;
use App\Controllers\SalesReturnController;
use App\Controllers\StoreController;
use App\Controllers\StoreLedgerController;
use App\Controllers\SupplierController;
use App\Controllers\SupplierLedgerController;
use App\Controllers\UnitController;
use App\Controllers\UserController;
use CodeIgniter\Router\RouteCollection;
use CodeIgniter\Shield\Controllers\LoginController;
use CodeIgniter\Shield\Entities\User;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'DashboardController::index');
$routes->get('/groups', 'DashboardController::addGroup');
$routes->get('products/search', 'ProductController::search');
$routes->get('products/sales/search', 'ProductController::sale_search');
$routes->get('products/purchases/search', 'ProductController::purchase_search');

//indexs
$routes->get('users', [UserController::class, 'index']);
$routes->get('product-adjustments', [AdjustmentController::class, 'index']);
$routes->get('brands', [BrandController::class, 'index']);
$routes->get('categories', [CategoryController::class, 'index']);
$routes->get('customers', [CustomerController::class, 'index']);
$routes->get('account-debts/customers', [CustomerLedgerController::class, 'customer_debts']);
$routes->get('expense-categories', [ExpenseCategoryController::class, 'index']);
$routes->get('expenses', [ExpenseController::class, 'index']);
$routes->get('products', [ProductController::class, 'index']);
$routes->get('transfers/products', [ProductTransferController::class, 'index']);
$routes->get('transfers/units', [ProductUnitTransferController::class, 'index']);
$routes->get('purchases', [PurchaseController::class, 'index']);
$routes->get('purchases/returns', [PurchaseReturnController::class, 'index']);
$routes->get('quotes', [QuoteController::class, 'index']);
$routes->get('sales', [SalesController::class, 'index']);
$routes->get('sales/returns', [SalesReturnController::class, 'index']);
$routes->get('stores', [StoreController::class, 'index']);
$routes->get('suppliers', [SupplierController::class, 'index']);
$routes->get('account-debts/suppliers', [SupplierLedgerController::class, 'supplier_debts']);
$routes->get('units', [UnitController::class, 'index']);
$routes->get('closing', [ClosingController::class, 'index']);

//shows
$routes->get('users/(:num)', [UserController::class, 'show/$1']);
$routes->get('product-adjustments/(:num)', [AdjustmentController::class, 'show/$1']);
$routes->get('customers/(:num)', [CustomerController::class, 'show/$1']);
$routes->get('products/(:num)', [ProductController::class, 'show/$1']);
$routes->get('transfers/products/(:num)', [ProductTransferController::class, 'show/$1']);
$routes->get('transfers/units/(:num)', [ProductUnitTransferController::class, 'show/$1']);
$routes->get('purchases/(:num)', [PurchaseController::class, 'show/$1']);
$routes->get('purchases/returns/(:num)', [PurchaseReturnController::class, 'show/$1']);
$routes->get('quotes/(:num)', [QuoteController::class, 'show/$1']);
$routes->get('sales/(:num)', [SalesController::class, 'show/$1']);
$routes->get('sales/returns/(:num)', [SalesReturnController::class, 'show/$1']);
$routes->get('stores/(:num)', [StoreController::class, 'show/$1']);
$routes->get('suppliers/(:num)', [SupplierController::class, 'show/$1']);

//edit
$routes->get('users/edit/(:num)', [UserController::class, 'edit/$1']);
$routes->get('product-adjustments/edit/(:num)', [AdjustmentController::class, 'edit/$1']);
$routes->get('brands/edit/(:num)', [BrandController::class, 'edit/$1']);
$routes->get('categories/edit/(:num)', [CategoryController::class, 'edit/$1']);
$routes->get('customers/edit/(:num)', [CustomerController::class, 'edit/$1']);
$routes->get('expense-categories/edit/(:num)', [ExpenseCategoryController::class, 'edit/$1']);
$routes->get('expenses/edit/(:num)', [ExpenseController::class, 'edit/$1']);
$routes->get('products/edit/(:num)', [ProductController::class, 'edit/$1']);
$routes->get('purchases/edit/(:num)', [PurchaseController::class, 'edit/$1']);
$routes->get('quotes/edit/(:num)', [QuoteController::class, 'edit/$1']);
$routes->get('sales/pos/(:num)', [SalesController::class, 'pos/$1']);
$routes->get('stores/edit/(:num)', [StoreController::class, 'edit/$1']);
$routes->get('suppliers/edit/(:num)', [SupplierController::class, 'edit/$1']);
$routes->get('units/edit/(:num)', [UnitController::class, 'edit/$1']);

//create
$routes->get('users/create', [UserController::class, 'edit']);
$routes->get('product-adjustments/create', [AdjustmentController::class, 'edit']);
$routes->get('brands/create', [BrandController::class, 'edit']);
$routes->get('categories/create', [CategoryController::class, 'edit']);
$routes->get('customers/create', [CustomerController::class, 'edit']);
$routes->get('expense-categories/create', [ExpenseCategoryController::class, 'edit']);
$routes->get('expenses/create', [ExpenseController::class, 'edit']);
$routes->get('products/create', [ProductController::class, 'edit']);
$routes->get('transfers/products/create', [ProductTransferController::class, 'edit']);
$routes->get('transfers/units/create', [ProductUnitTransferController::class, 'edit']);
$routes->get('purchases/create', [PurchaseController::class, 'edit']);
$routes->get('purchases/returns/create', [PurchaseReturnController::class, 'edit']);
$routes->get('quotes/create', [QuoteController::class, 'edit']);
$routes->get('sales/pos', [SalesController::class, 'pos']);
$routes->get('sales/returns/create', [SalesReturnController::class, 'edit']);
$routes->get('stores/create', [StoreController::class, 'edit']);
$routes->get('suppliers/create', [SupplierController::class, 'edit']);
$routes->get('units/create', [UnitController::class, 'edit']);
$routes->get('products/import', [ImportController::class, 'index']);
$routes->get('closing/store', [ClosingController::class, 'store']);
$routes->get('cashup', [StoreLedgerController::class, 'edit']);

//datatables
$routes->get('users/datatable', [UserController::class, 'datatable']);
$routes->get('product-adjustments/datatable', [AdjustmentController::class, 'datatable']);
$routes->get('brands/datatable', [BrandController::class, 'datatable']);
$routes->get('categories/datatable', [CategoryController::class, 'datatable']);
$routes->get('customers/datatable', [CustomerController::class, 'datatable']);
$routes->get('customers/ledger/datatable', [CustomerLedgerController::class, 'datatable']);
$routes->get('customers/debtors/datatable', [CustomerLedgerController::class, 'debtors_datatable']);
$routes->get('expense-categories/datatable', [ExpenseCategoryController::class, 'datatable']);
$routes->get('expenses/datatable', [ExpenseController::class, 'datatable']);
$routes->get('products/datatable', [ProductController::class, 'datatable']);
$routes->get('transfers/products/datatable', [ProductTransferController::class, 'datatable']);
$routes->get('transfers/units/datatable', [ProductUnitTransferController::class, 'datatable']);
$routes->get('purchases/datatable', [PurchaseController::class, 'datatable']);
$routes->get('purchases/returns/datatable', [PurchaseReturnController::class, 'datatable']);
$routes->get('quotes/datatable', [QuoteController::class, 'datatable']);
$routes->get('sales/datatable', [SalesController::class, 'datatable']);
$routes->get('sales/returns/datatable', [SalesReturnController::class, 'datatable']);
$routes->get('stores/datatable', [StoreController::class, 'datatable']);
$routes->get('suppliers/datatable', [SupplierController::class, 'datatable']);
$routes->get('suppliers/ledger/datatable', [SupplierLedgerController::class, 'datatable']);
$routes->get('cashup/datatable', [StoreLedgerController::class, 'datatable']);
$routes->get('suppliers/creditors/datatable', [SupplierLedgerController::class, 'creditors_datatable']);
$routes->get('units/datatable', [UnitController::class, 'datatable']);
$routes->get('closing/datatable', [ClosingController::class, 'datatable']);

//select2
$routes->get('users/select2', [UserController::class, 'select2']);
$routes->get('product-adjustments/select2', [AdjustmentController::class, 'select2']);
$routes->get('brands/select2', [BrandController::class, 'select2']);
$routes->get('categories/select2', [CategoryController::class, 'select2']);
$routes->get('customers/select2', [CustomerController::class, 'select2']);
$routes->get('expense-categories/select2', [ExpenseCategoryController::class, 'select2']);
$routes->get('expenses/select2', [ExpenseController::class, 'select2']);
$routes->get('products/select2', [ProductController::class, 'select2']);
$routes->get('purchases/select2', [PurchaseController::class, 'select2']);
$routes->get('purchases/returns/select2', [PurchaseReturnController::class, 'select2']);
$routes->get('quotes/select2', [QuoteController::class, 'select2']);
$routes->get('sales/select2', [SalesController::class, 'select2']);
$routes->get('sales/returns/select2', [SalesReturnController::class, 'select2']);
$routes->get('stores/select2', [StoreController::class, 'select2']);
$routes->get('suppliers/select2', [SupplierController::class, 'select2']);
$routes->get('units/select2', [UnitController::class, 'select2']);

// post requests
$routes->post('users', [UserController::class, 'save']);
$routes->post('product-adjustments', [AdjustmentController::class, 'save']);
$routes->post('brands', [BrandController::class, 'save']);
$routes->post('categories', [CategoryController::class, 'save']);
$routes->post('customers', [CustomerController::class, 'save']);
$routes->post('expense-categories', [ExpenseCategoryController::class, 'save']);
$routes->post('expenses', [ExpenseController::class, 'save']);
$routes->post('products', [ProductController::class, 'save']);
$routes->post('suppliers/ledgers', [SupplierLedgerController::class, 'save']);
$routes->post('customers/ledgers', [CustomerLedgerController::class, 'save']);
$routes->post('transfers/products', [ProductTransferController::class, 'save']);
$routes->post('transfers/units', [ProductUnitTransferController::class, 'save']);
$routes->post('purchases', [PurchaseController::class, 'save']);
$routes->post('purchases/returns', [PurchaseReturnController::class, 'save']);
$routes->post('quotes', [QuoteController::class, 'save']);
$routes->post('sales', [SalesController::class, 'save']);
$routes->post('sales/hold', [SalesController::class, 'hold']);
$routes->post('sales/returns', [SalesReturnController::class, 'save']);
$routes->post('stores', [StoreController::class, 'save']);
$routes->post('suppliers', [SupplierController::class, 'save']);
$routes->post('units', [UnitController::class, 'save']);
$routes->post('cashup', [StoreLedgerController::class, 'save']);
$routes->post('closing/save', [ClosingController::class, 'save']);

// delete requests
$routes->delete('product-adjustments/(:num)', [AdjustmentController::class, 'delete']);
$routes->delete('brands/(:num)', [BrandController::class, 'delete']);
$routes->delete('categories/(:num)', [CategoryController::class, 'delete']);
$routes->delete('customers/(:num)', [CustomerController::class, 'delete']);
$routes->delete('customers/ledger/(:num)', [CustomerLedgerController::class, 'delete']);
$routes->delete('expense-categories/(:num)', [ExpenseCategoryController::class, 'delete']);
$routes->delete('expenses/(:num)', [ExpenseController::class, 'delete']);
$routes->delete('products/(:num)', [ProductController::class, 'delete']);
$routes->delete('transfers/products/(:num)', [ProductTransferController::class, 'delete']);
$routes->delete('transfers/units/(:num)', [ProductUnitTransferController::class, 'delete']);
$routes->delete('purchases/(:num)', [PurchaseController::class, 'delete']);
$routes->delete('purchases/returns/(:num)', [PurchaseReturnController::class, 'delete']);
$routes->delete('quotes/(:num)', [QuoteController::class, 'delete']);
$routes->delete('sales/(:num)', [SalesController::class, 'delete']);
$routes->delete('sales/returns/(:num)', [SalesReturnController::class, 'delete']);
$routes->delete('stores/(:num)', [StoreController::class, 'delete']);
$routes->delete('suppliers/(:num)', [SupplierController::class, 'delete']);
$routes->delete('suppliers/ledger/(:num)', [SupplierLedgerController::class, 'delete']);
$routes->delete('units/(:num)', [UnitController::class, 'delete']);
$routes->delete('cashup/(:num)', [StoreLedgerController::class, 'delete']);

service('auth')->routes($routes);
