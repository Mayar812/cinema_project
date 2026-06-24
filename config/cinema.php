<?php

return [
    // The fixed seat grid for every hall: rows A-C, seats 1-6 (18 seats).
    'seat_rows' => ['A', 'B', 'C'],
    'seat_columns' => [1, 2, 3, 4, 5, 6],

    // Rows sold as VIP chairs; every other row is Premium.
    'vip_rows' => ['A'],

    // Chair surcharge added on top of the ticket price. VIP costs $10 more.
    'chair_fees' => [
        'Premium' => 4,
        'VIP' => 14,
    ],

    // Flat fee added when any snack (other than "None") is chosen.
    'snack_fee' => 7,
];
