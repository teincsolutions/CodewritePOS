<?php

declare(strict_types=1);

namespace Config;

use CodeIgniter\Shield\Config\AuthGroups as ShieldAuthGroups;

class AuthGroups extends ShieldAuthGroups
{
    /**
     * --------------------------------------------------------------------
     * Default Group
     * --------------------------------------------------------------------
     * The group that a newly registered user is added to.
     */
    public string $defaultGroup = 'developer';

    /**
     * --------------------------------------------------------------------
     * Disabled Groups
     * --------------------------------------------------------------------
     * The group that are not allowed to be edit.
     */
    public array $disabledGroup = [];

    /**
     * --------------------------------------------------------------------
     * Groups
     * --------------------------------------------------------------------
     * An associative array of the available groups in the system, where the keys
     * are the group names and the values are arrays of the group info.
     *
     * Whatever value you assign as the key will be used to refer to the group
     * when using functions such as:
     *      $user->addGroup('superadmin');
     *
     * @var array<string, array<string, string>>
     *
     * @see https://github.com/codeigniter4/shield/blob/develop/docs/quickstart.md#change-available-groups for more info
     */
    public array $groups = [
        'admin' => [
            'title'       => 'Admin',
            'description' => 'Day to day administrators of the system.',
        ],
        'manager' => [
            'title'       => 'Store Manager',
            'description' => 'Management of the store(s) for sales or purchases.',
        ],
        'seller' => [
            'title'       => 'Seller',
            'description' => 'Sales clerk or seller for minimal access only.',
        ],
        'developer' => [
            'title'       => 'Developer',
            'description' => 'Application developer.',
        ],
        'guest' => [
            'title'       => 'Guest User',
            'description' => 'Visitor who want to checkout the system.',
        ],
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions
     * --------------------------------------------------------------------
     * The available permissions in the system.
     *
     * If a permission is not listed here it cannot be used.
     */
    public array $permissions = [
        'users.view'        => 'Can view users',
        'users.create'        => 'Can create new users',
        'users.edit'          => 'Can edit existing users',
        'users.delete'        => 'Can delete existing users',

        'customers.view'        => 'Can view customers',
        'customers.create'        => 'Can create new customers',
        'customers.edit'          => 'Can edit existing customers',
        'customers.delete'        => 'Can delete existing customers',

        'suppliers.view'        => 'Can view suppliers',
        'suppliers.create'        => 'Can create new suppliers',
        'suppliers.edit'          => 'Can edit existing suppliers',
        'suppliers.delete'        => 'Can delete existing suppliers',

        'stores.view'        => 'Can view stores',
        'stores.create'        => 'Can add new stores',
        'stores.edit'          => 'Can edit stores',
        'stores.delete'        => 'Can delete existing stores',

        'products.view'        => 'Can view products',
        'products.create'        => 'Can add new products',
        'products.edit'          => 'Can edit products',
        'products.delete'        => 'Can delete existing products',
        'products.import'        => 'Can import new products',

        'product-transfers.view'        => 'Can view product transfers',
        'product-transfers.create'        => 'Can add new product transfers',
        'product-transfers.edit'          => 'Can edit product transfers',
        'product-transfers.delete'        => 'Can delete existing product transfers',

        'unit-transfers.view'        => 'Can view unit transfers',
        'unit-transfers.create'        => 'Can add new unit transfers',
        'unit-transfers.edit'          => 'Can edit unit transfers',
        'unit-transfers.delete'        => 'Can delete existing unit transfers',

        'categories.view'        => 'Can view categories',
        'categories.create'        => 'Can add new categories',
        'categories.edit'          => 'Can edit categories',
        'categories.delete'        => 'Can delete existing categories',

        'brands.view'        => 'Can view brands',
        'brands.create'        => 'Can add new brands',
        'brands.edit'          => 'Can edit brands',
        'brands.delete'        => 'Can delete existing brands',

        'units.view'        => 'Can view units',
        'units.create'        => 'Can add new units',
        'units.edit'          => 'Can edit units',
        'units.delete'        => 'Can delete existing units',

        'expense-categories.view'        => 'Can view expense categories',
        'expense-categories.create'        => 'Can add new expense categories',
        'expense-categories.edit'          => 'Can edit expense categories',
        'expense-categories.delete'        => 'Can delete existing expense categories',

        'expenses.view'        => 'Can view expenses',
        'expenses.create'        => 'Can add new expenses',
        'expenses.edit'          => 'Can edit expenses',
        'expenses.delete'        => 'Can delete existing expenses',

        'customer-ledgers.view'        => 'Can view customer ledgers',
        'customer-ledgers.create'        => 'Can add new customer ledgers',
        'customer-ledgers.edit'          => 'Can edit customer ledgers',
        'customer-ledgers.delete'        => 'Can delete existing customer ledgers',

        'supplier-ledgers.view'        => 'Can view supplier ledgers',
        'supplier-ledgers.create'        => 'Can create new supplier ledgers',
        'supplier-ledgers.edit'          => 'Can edit existing supplier ledgers',
        'supplier-ledgers.delete'        => 'Can delete existing supplier ledgers',

        'purchases.view'        => 'Can view purchases',
        'purchases.create'        => 'Can add new purchases',
        'purchases.edit'          => 'Can edit purchases',
        'purchases.delete'        => 'Can delete existing purchases',

        'purchase-returns.view'        => 'Can view purchase returns',
        'purchase-returns.create'        => 'Can add new purchase returns',
        'purchase-returns.edit'          => 'Can edit purchase returns',
        'purchase-returns.delete'        => 'Can delete existing purchase returns',

        'sales.view'        => 'Can view sales',
        'sales.create'        => 'Can add new sales',
        'sales.edit'          => 'Can edit sales',
        'sales.delete'        => 'Can delete existing sales',

        'sales-returns.view'        => 'Can view sales returns',
        'sales-returns.create'        => 'Can add new sales returns',
        'sales-returns.edit'          => 'Can edit sales returns',
        'sales-returns.delete'        => 'Can delete existing sales returns',

        'quotes.view'        => 'Can view quotes',
        'quotes.create'        => 'Can add new quotes',
        'quotes.edit'          => 'Can edit quotes',
        'quotes.delete'        => 'Can delete existing quotes',

        'adjustments.view'        => 'Can view adjustments',
        'adjustments.create'        => 'Can add new adjustments',
        'adjustments.edit'          => 'Can edit adjustments',
        'adjustments.delete'        => 'Can delete existing adjustments',

        'stocks.view'        => 'Can view stocks',
        'stocks.create'        => 'Can add new stocks',
        'stocks.edit'          => 'Can edit stocks',
        'stocks.delete'        => 'Can delete existing stocks',

        'closing.view'        => 'Can view closings',
        'closing.create'        => 'Can create closing report',
        'closing.delete'        => 'Can delete existing closing',

        'cashup.view'           => 'Can view cashup',
        'cashup.create'           => 'Can create cashup',
        'cashup.delete'           => 'Can delte cashup',

        'general-settings.access'      => 'Can view and edit the general settings',
        'email-settings.access'      => 'Can view and edit the email settings',
        'payment-settings.access'      => 'Can view and edit the payment settings',
        'permission-settings.access'      => 'Can view and edit the permssions settings',
        'updates.access'      => 'Can access new version update settings',
        'sms-settings.access'      => 'Can access sms settings',

        'users.*' => 'Has full control',
        'customers.*' => 'Has full control',
        'suppliers.*' => 'Has full control',
        'stores.*' => 'Has full control',
        'products.*' => 'Has full control',
        'brands.*' => 'Has full control',
        'categories.*' => 'Has full control',
        'units.*' => 'Has full control',
        'product-transfers.*' => 'Has full control',
        'unit-transfers.*' => 'Has full control',
        'expense-categories.*'  => 'Has full control',
        'expenses.*' => 'Has full control',
        'customer-ledgers.*' => 'Has full control',
        'supplier-ledgers.*' => 'Has full control',
        'purchases.*' => 'Has full control',
        'sales.*' => 'Has full control',
        'purchase-returns.*' => 'Has full control',
        'sales-returns.*' => 'Has full control',
        'quotes.*' => 'Has full control',
        'stocks.*' => 'Has full control',
        'adjustments.*' => 'Has full control',
        'closing.*' => 'Has full control',
        'cashup.*' => 'Has full control',
        'general-settings.*' => 'Has full control',
        'email-settings.*' => 'Has full control',
        'payment-settings.*' => 'Has full control',
        'permission-settings.*' => 'Has full control',
        'sms-settings.*' => 'Has full control'
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions
     * --------------------------------------------------------------------
     * The available permissions in the system.
     *
     * If a permission is not listed here it cannot be used.
     */
    public array $permissionItems = [
        'users'        => 'Users Management',
        'customers'        => 'Customers Management',
        'suppliers'        => 'Suppliers Management',
        'stores'        => 'Stores Management',
        'products'        => 'Products Management',
        'product-transfers'        => 'Product Transfers Management',
        'unit-transfers'        => 'Unit transfers Management',
        'categories'        => 'Categories Management',
        'brands'        => 'Brands Management',
        'units'        => 'Units Managements',
        'expense-categories'        => 'Expense Categories Management',
        'expenses'        => 'Expenses Management',
        'customer-ledgers'        => 'Customer Account Book Management',
        'supplier-ledgers'        => 'Supplier Account Book Management',
        'purchases'        => 'Purchases Management',
        'purchase-returns'        => 'Purchase Returns Management',
        'sales'        => 'Sales Managements',
        'sales-returns'        => 'Sales Returns Management',
        'quotes'        => 'Quotes Management',
        'adjustments'        => 'Adjustments Management',
        'stocks'        => 'Stocks Management',
        'closing'        => 'Store Closing Management',
        'cashup'            => 'Cashup Management',
        'general-settings'      => 'General Settings',
        'email-settings'      => 'Email Settings',
        'payment-settings'      => 'Payment Settings',
        'permission-settings'      => 'Permssions Settings',
        'updates'      => 'New Version Update Settings',
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions Matrix
     * --------------------------------------------------------------------
     * Maps permissions to groups.
     *
     * This defines group-level permissions.
     */
    public array $matrix = [
        'admin' => [
            'users.*', 'customers.*', 'suppliers.*', 'stores.*', 'products.*', 'brands.*',
            'categories.*', 'units.*', 'product-transfers.*', 'unit-transfers.*', 'expense-categories.*',
            'expenses.*', 'customer-ledgers.*', 'supplier-ledgers.*', 'purchases.*', 'sales.*',
            'purchase-returns.*', 'sales-returns.*', 'quotes.*', 'adjustments.*', 'closing.*',
            'general-settings.*', 'email-settings.*', 'payment-settings.*', 'permission-settings.*',
            'sms-settings.*', 'cashup.*'
        ],
        'developer' => [
            'users.*', 'customers.*', 'suppliers.*', 'stores.*', 'products.*', 'brands.*',
            'categories.*', 'units.*', 'product-transfers.*', 'unit-transfers.*', 'expense-categories.*',
            'expenses.*', 'customer-ledgers.*', 'supplier-ledgers.*', 'purchases.*', 'sales.*', 'cashup.*','closing.*',
            'purchase-returns.*', 'sales-returns.*', 'quotes.*', 'adjustments.*', 'stocks.*', 'account-debts.*',
            'general-settings.*', 'email-settings.*', 'payment-settings.*', 'permission-settings.*', 'updates.*',
        ],
        'guest' => [
            'customers.view', 'suppliers.view', 'stores.view', 'products.view', 'brands.view',
            'categories.view', 'units.view', 'product-transfers.view', 'unit-transfers.view', 'expense-categories.view',
            'expenses.view', 'customer-ledgers.view', 'supplier-ledgers.view', 'purchases.view', 'sales.view',
            'purchase-returns.view', 'sales-returns.view', 'quotes.view', 'adjustments.view',
        ],
        'seller' => [
            'customers.view', 'products.view', 'brands.view', 'customers.edit',
            'categories.view', 'units.view', 'product-transfers.view', 'expense-categories.view',
            'expenses.view','expenses.create','expenses.edit', 'customer-ledgers.view','customer-ledgers.create', 'sales.view','sales.create',
            'sales-returns.view','sales-returns.create', 'quotes.view','quotes.create','cashup.create','cashup.view','closing.create','closing.view'
        ],
    ];
}
