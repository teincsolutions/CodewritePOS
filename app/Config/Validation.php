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
            'label' => 'Auth.username',
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
        'address' => [
            'label' => 'Address',
            'rules' => [
            ],
        ],
        'password' => [
            'label'  => 'Auth.password',
            'rules'  => 'required|max_length[255]',
            'errors' => [
                'max_byte' => 'Auth.errorPasswordTooLongBytes',
            ],
        ],
        'password_confirm' => [
            'label' => 'Auth.passwordConfirm',
            'rules' => 'required|matches[password]',
        ],
    ];
}
