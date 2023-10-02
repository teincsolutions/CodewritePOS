<?php

use App\Controllers\AdjustmentController;
use App\Controllers\BrandController;
use App\Controllers\CategoryController;
use App\Controllers\CustomerController;
use App\Controllers\CustomerLedgerController;
use App\Controllers\ExpenseCategoryController;
use App\Controllers\ExpenseController;
use App\Controllers\ProductController;
use App\Controllers\ProductTransferController;
use App\Controllers\ProductUnitTransferController;
use App\Controllers\PurchaseController;
use App\Controllers\PurchaseReturnController;
use App\Controllers\QuoteController;
use App\Controllers\SalesController;
use App\Controllers\SalesReturnController;
use App\Controllers\StoreController;
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

//indexs
$routes->get('users', [UserController::class, 'index']);
$routes->get('product-adjustments', [AdjustmentController::class, 'index']);
$routes->get('brands', [BrandController::class, 'index']);
$routes->get('categories', [CategoryController::class, 'index']);
$routes->get('customers', [CustomerController::class, 'index']);
$routes->get('expense-categories', [ExpenseCategoryController::class, 'index']);
$routes->get('expenses', [ExpenseController::class, 'index']);
$routes->get('products', [ProductController::class, 'index']);

$routes->get('transfers/product', [ProductTransferController::class, 'index']);
$routes->get('transfers/product-unit', [ProductUnitTransferController::class, 'index']);

$routes->get('purchases', [PurchaseController::class, 'index']);
$routes->get('returns/purchase', [PurchaseReturnController::class, 'index']);
$routes->get('quotes', [QuoteController::class, 'index']);
$routes->get('sales', [SalesController::class, 'index']);
$routes->get('returns/sales', [SalesReturnController::class, 'index']);
$routes->get('stores', [StoreController::class, 'index']);
$routes->get('suppliers', [SupplierController::class, 'index']);
$routes->get('units', [UnitController::class, 'index']);

//shows
$routes->get('users/(:num)', [UserController::class, 'show/$1']);
$routes->get('product-adjustments/(:num)', [AdjustmentController::class, 'show/$1']);
$routes->get('customers/(:num)', [CustomerController::class, 'show/$1']);
$routes->get('products/(:num)', [ProductController::class, 'show/$1']);
$routes->get('product-transfers/(:num)', [ProductTransferController::class, 'show/$1']);
$routes->get('product-unit-transfers/(:num)', [ProductUnitTransferController::class, 'show/$1']);
$routes->get('purchases/(:num)', [PurchaseController::class, 'show/$1']);
$routes->get('purchase-returns/(:num)', [PurchaseReturnController::class, 'show/$1']);
$routes->get('quotes/(:num)', [QuoteController::class, 'show/$1']);
$routes->get('sales/(:num)', [SalesController::class, 'show/$1']);
$routes->get('sales-returns/(:num)', [SalesReturnController::class, 'show/$1']);
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
$routes->get('product-transfers/edit/(:num)', [ProductTransferController::class, 'edit/$1']);
$routes->get('product-unit-transfers/edit/(:num)', [ProductUnitTransferController::class, 'edit/$1']);
$routes->get('purchases/edit/(:num)', [PurchaseController::class, 'edit/$1']);
$routes->get('purchase-returns/edit/(:num)', [PurchaseReturnController::class, 'edit/$1']);
$routes->get('quotes/edit/(:num)', [QuoteController::class, 'edit/$1']);
$routes->get('sales/pos/(:num)', [SalesController::class, 'pos/$1']);
$routes->get('sales-returns/edit/(:num)', [SalesReturnController::class, 'edit/$1']);
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
$routes->get('product-transfers/create', [ProductTransferController::class, 'edit']);
$routes->get('product-unit-transfers/create', [ProductUnitTransferController::class, 'edit']);
$routes->get('purchases/create', [PurchaseController::class, 'edit']);
$routes->get('purchase-returns/create', [PurchaseReturnController::class, 'edit']);
$routes->get('quotes/create', [QuoteController::class, 'edit']);
$routes->get('sales/pos', [SalesController::class, 'pos']);
$routes->get('sales-returns/create', [SalesReturnController::class, 'edit']);
$routes->get('stores/create', [StoreController::class, 'edit']);
$routes->get('suppliers/create', [SupplierController::class, 'edit']);
$routes->get('units/create', [UnitController::class, 'edit']);

//datatables
$routes->get('users/datatable', [UserController::class, 'datatable']);
$routes->get('product-adjustments/datatable', [AdjustmentController::class, 'datatable']);
$routes->get('brands/datatable', [BrandController::class, 'datatable']);
$routes->get('categories/datatable', [CategoryController::class, 'datatable']);
$routes->get('customers/datatable', [CustomerController::class, 'datatable']);
$routes->get('expense-categories/datatable', [ExpenseCategoryController::class, 'datatable']);
$routes->get('expenses/datatable', [ExpenseController::class, 'datatable']);
$routes->get('products/datatable', [ProductController::class, 'datatable']);
$routes->get('product-transfers/datatable', [ProductTransferController::class, 'datatable']);
$routes->get('product-unit-transfers/datatable', [ProductUnitTransferController::class, 'datatable']);
$routes->get('purchases/datatable', [PurchaseController::class, 'datatable']);
$routes->get('purchase-returns/datatable', [PurchaseReturnController::class, 'datatable']);
$routes->get('quotes/datatable', [QuoteController::class, 'datatable']);
$routes->get('sales/datatable', [SalesController::class, 'datatable']);
$routes->get('sales-returns/datatable', [SalesReturnController::class, 'datatable']);
$routes->get('stores/datatable', [StoreController::class, 'datatable']);
$routes->get('suppliers/datatable', [SupplierController::class, 'datatable']);
$routes->get('units/datatable', [UnitController::class, 'datatable']);

//select2
$routes->get('users/select2', [UserController::class, 'select2']);
$routes->get('product-adjustments/select2', [AdjustmentController::class, 'select2']);
$routes->get('brands/select2', [BrandController::class, 'select2']);
$routes->get('categories/select2', [CategoryController::class, 'select2']);
$routes->get('customers/select2', [CustomerController::class, 'select2']);
$routes->get('expense-categories/select2', [ExpenseCategoryController::class, 'select2']);
$routes->get('expenses/select2', [ExpenseController::class, 'select2']);
$routes->get('products/select2', [ProductController::class, 'select2']);
$routes->get('product-transfers/select2', [ProductTransferController::class, 'select2']);
$routes->get('product-unit-transfers/select2', [ProductUnitTransferController::class, 'select2']);
$routes->get('purchases/select2', [PurchaseController::class, 'select2']);
$routes->get('purchase-returns/select2', [PurchaseReturnController::class, 'select2']);
$routes->get('quotes/select2', [QuoteController::class, 'select2']);
$routes->get('sales/select2', [SalesController::class, 'select2']);
$routes->get('sales-returns/select2', [SalesReturnController::class, 'select2']);
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
$routes->post('transfers/product', [ProductTransferController::class, 'save']);
$routes->post('transfers/product-unit', [ProductUnitTransferController::class, 'save']);
$routes->post('purchases', [PurchaseController::class, 'save']);
$routes->post('returns/purchase', [PurchaseReturnController::class, 'save']);
$routes->post('quotes', [QuoteController::class, 'save']);
$routes->post('sales', [SalesController::class, 'save']);
$routes->post('sales/hold', [SalesController::class, 'hold']);
$routes->post('returns/sales', [SalesReturnController::class, 'save']);
$routes->post('stores', [StoreController::class, 'save']);
$routes->post('suppliers', [SupplierController::class, 'save']);
$routes->post('units', [UnitController::class, 'save']);

//Put Request
$routes->put('users', [UserController::class, 'save']);
$routes->put('expense-categories', [ExpenseCategoryController::class, 'save']);
$routes->put('expenses', [ExpenseController::class, 'save']);
$routes->put('categories', [CategoryController::class, 'save']);
$routes->put('products', [ProductController::class, 'save']);
$routes->put('purchases', [PurchaseController::class, 'save']);
$routes->put('returns/purchase', [PurchaseReturnController::class, 'save']);
$routes->put('quotes', [QuoteController::class, 'save']);
$routes->put('sales', [SalesController::class, 'save']);
$routes->put('returns/sales', [SalesReturnController::class, 'save']);
$routes->put('stores', [StoreController::class, 'save']);
$routes->put('suppliers', [SupplierController::class, 'save']);
$routes->put('units', [UnitController::class, 'save']);
$routes->put('products', [ProductController::class, 'save']);

$routes->put('transfers/product', [ProductTransferController::class, 'save']);
$routes->put('transfers/product-unit', [ProductUnitTransferController::class, 'save']);

// delete requests
$routes->delete('product-adjustments/(:num)', [AdjustmentController::class, 'delete']);
$routes->delete('brands/(:num)', [BrandController::class, 'delete']);
$routes->delete('categories/(:num)', [CategoryController::class, 'delete']);
$routes->delete('customers/(:num)', [CustomerController::class, 'delete']);
$routes->delete('expense-categories/(:num)', [ExpenseCategoryController::class, 'delete']);
$routes->delete('expenses/(:num)', [ExpenseController::class, 'delete']);
$routes->delete('products/(:num)', [ProductController::class, 'delete']);

$routes->delete('transfers/product/(:num)', [ProductTransferController::class, 'delete']);
$routes->delete('transfers/product-unit/(:num)', [ProductUnitTransferController::class, 'delete']);

$routes->delete('purchases/(:num)', [PurchaseController::class, 'delete']);
$routes->delete('returns/purchase/(:num)', [PurchaseReturnController::class, 'delete']);
$routes->delete('quotes/(:num)', [QuoteController::class, 'delete']);
$routes->delete('sales/(:num)', [SalesController::class, 'delete']);
$routes->delete('returns/sales/(:num)', [SalesReturnController::class, 'delete']);
$routes->delete('stores/(:num)', [StoreController::class, 'delete']);
$routes->delete('suppliers/(:num)', [SupplierController::class, 'delete']);
$routes->delete('units/(:num)', [UnitController::class, 'delete']);

service('auth')->routes($routes);
