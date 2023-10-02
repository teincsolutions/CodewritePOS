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
        'admin.all'        => 'Has full control of the system',
        'products.create'        => 'Can add new products',
        'products.edit'          => 'Can edit products',
        'products.delete'        => 'Can delete existing products',
        'settings.general'      => 'Can view the general settings',
        'settings.email'      => 'Can view the email settings',
        'settings.invoice'      => 'Can view the invoice settings',
        'settings.payment'      => 'Can view the payment settings',
        'settings.permissions'      => 'Can view the permssions settings',
        'users.view'        => 'Can view users',
        'users.create'        => 'Can create new non-admin users',
        'users.edit'          => 'Can edit existing non-admin users',
        'users.delete'        => 'Can delete existing non-admin users',
        'products.view'        => 'Can view products',
        'products.create'        => 'Can add new products',
        'products.edit'          => 'Can edit products',
        'products.delete'        => 'Can delete existing products',
        'purchases.view'        => 'Can view purchases',
        'purchases.create'        => 'Can add new purchases',
        'purchases.edit'          => 'Can edit purchases',
        'purchases.delete'        => 'Can delete existing purchases',
        'sales.view'        => 'Can view sales',
        'sales.create'        => 'Can add new sales',
        'sales.edit'          => 'Can edit sales',
        'sales.delete'        => 'Can delete existing sales',
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
            'admin.*',
            'users.*',
        ],
        'developer' => [
        ],
        'guest' => [
            '*.view'
        ],
    ];
}
