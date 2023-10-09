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
        'general-settings.edit'      => 'Can view and edit the general settings',
        'email-settings.edit'      => 'Can view and edit the email settings',
        'invoice-settings.edit'      => 'Can view and edit the invoice settings',
        'receipt-settings.edit'      => 'Can view and edit the receipt settings',
        'sales-settings.edit'      => 'Can view and edit the sales settings',
        'purchase-settings.edit'      => 'Can view and edit the purchase settings',
        'payment-settings.edit'      => 'Can view and edit the payment settings',
        'permission-settings.edit'      => 'Can view and edit the permssions settings',
        'updates.access'      => 'Can access new version update settings',

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
            'users.*','customers.*','suppliers.*','stores.*','products.*','brands.*',
            'categories.*','units.*','product-transfers.*','unit-transfers.*','expense-categories.*',
            'expenses.*', 'customer-ledgers.*','supplier-ledgers.*','purchases.*','sales.*',
            'purchase-returns.*','sales-returns.*','quotes.*','adjustments.*',
            'general-settings.*' ,'email-settings.*','invoice-settings.*','sales-settings.*',
            'receipt-settings.*', 'purchase-settings.*','payment-settings.*','permission-settings.*',
        ],
        'developer' => [
            'users.*','customers.*','suppliers.*','stores.*','products.*','brands.*',
            'categories.*','units.*','product-transfers.*','unit-transfers.*','expense-categories.*',
            'expenses.*', 'customer-ledgers.*','supplier-ledgers.*','purchases.*','sales.*',
            'purchase-returns.*','sales-returns.*','quotes.*','adjustments.*','stocks.*',
            'general-settings.*' ,'email-settings.*','invoice-settings.*','sales-settings.*','receipt-settings.*', 
            'purchase-settings.*','payment-settings.*', 'permission-settings.*','updates.access',
        ],
        'guest' => [
            'customers.view','suppliers.view','stores.view','products.view','brands.view',
            'categories.view','units.view','product-transfers.view','unit-transfers.view','expense-categories.view',
            'expenses.view', 'customer-ledgers.view','supplier-ledgers.view','purchases.view','sales.view',
            'purchase-returns.view','sales-returns.view','quotes.view','adjustments.view',
        ],
    ];
}
