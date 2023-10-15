<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var string[]
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------
    /**
     * Specifies the rules that are used to validate the login
     *
     * @var array<string, string>
     */
    public array $login = [
        'username' => [
            'label' => 'Auth.username',
            'rules' => [
                'required',
                'max_length[30]',
                'min_length[3]',
                'regex_match[/\A[a-zA-Z0-9\.]+\z/]',
            ],
        ],
        'password' => [
            'label'  => 'Auth.password',
            'rules'  => 'required|max_length[255]',
            'errors' => [
                'max_byte' => 'Auth.errorPasswordTooLongBytes',
            ],
        ]
    ];

    /**
     * Specifies the rules that are used to validate the registration
     *
     * @var array<string, string>
     */
    public array $registration = [
        'username' => [
            'label' => 'UserName',
            'rules' => [
                'required',
                'max_length[30]',
                'min_length[3]',
                'regex_match[/\A[a-zA-Z0-9\.]+\z/]',
                'is_unique[users.username]'
            ],
        ],
        'firstname' => [
            'label' => 'First Name',
            'rules' => [
                'required',
                'max_length[45]',
                'min_length[3]',
            ],
        ],
        'lastname' => [
            'label' => 'Last Name',
            'rules' => [
                'required',
                'max_length[45]',
                'min_length[3]',
            ],
        ],
        'email' => [
            'label' => 'Email',
            'rules' => [
                'max_length[255]',
                'valid_email'
            ],
        ],
        'phone' => [
            'label' => 'Phone Number',
            'rules' => [
                'required',
                'max_length[16]',
                'min_length[10]',
                'is_unique[users.phone]'
            ],
        ],
        'address' => [
            'label' => 'Address',
            'rules' => [],
        ],
        'password' => [
            'label'  => 'Password',
            'rules'  => 'required|max_length[255]',
            'errors' => [
                'max_byte' => 'Auth.errorPasswordTooLongBytes',
            ],
        ],
        'password_confirm' => [
            'label' => 'Confirm Password',
            'rules' => 'required|matches[password]',
        ],
    ];

    /**
     * Specifies the rules that are used to validate the registration
     *
     * @var array<string, string>
     */
    public array $userUpdate = [
        'username' => [
            'label' => 'UserName',
            'rules' => [
                'required',
                'max_length[30]',
                'min_length[3]',
                'regex_match[/\A[a-zA-Z0-9\.]+\z/]',
            ],
        ],
        'firstname' => [
            'label' => 'First Name',
            'rules' => [
                'required',
                'max_length[45]',
                'min_length[3]',
            ],
        ],

        'lastname' => [
            'label' => 'Last Name',
            'rules' => [
                'required',
                'max_length[45]',
                'min_length[3]',
            ],
        ],
        'phone' => [
            'label' => 'Phone Number',
            'rules' => [
                'required',
                'max_length[16]',
                'min_length[10]',
            ],
        ],
        'email' => [
            'label' => 'Email',
            'rules' => [
                'max_length[255]',
                'valid_email'
            ],
        ],
        'address' => [
            'label' => 'Address',
            'rules' => [],
        ],
        'password' => [
            'label'  => 'Password',
            'rules'  => 'required|max_length[255]',
            'errors' => [
                'max_byte' => 'Auth.errorPasswordTooLongBytes',
            ],
        ],
        'password_confirm' => [
            'label' => 'Confirm Password',
            'rules' => 'required|matches[password]',
        ],
    ];
}
