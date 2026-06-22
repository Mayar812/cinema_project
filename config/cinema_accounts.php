<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Built-in Login Accounts
    |--------------------------------------------------------------------------
    |
    | These accounts are intentionally defined in code as requested. The
    | database seeder hashes each password before storing it in the users table.
    |
    */
    'accounts' => [
        [
            'name' => 'Cinema Admin',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'admin',
        ],
        [
            'name' => 'Cinema User',
            'username' => 'user',
            'email' => 'user@example.com',
            'password' => 'password',
            'role' => 'user',
        ],
    ],
];
