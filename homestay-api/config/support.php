<?php

$contacts = array_map('trim', explode(',', env('SUPPORT_CONTACTS')));

return [
    'phone' => $contacts[0] ?? null,
    'email' => $contacts[1] ?? null,
    'fanpage' => $contacts[2] ?? null,
    'zalo' => $contacts[3] ?? null,
];
