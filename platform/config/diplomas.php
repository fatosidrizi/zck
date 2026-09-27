<?php

/*
|--------------------------------------------------------------------------
| Diploma verification
|--------------------------------------------------------------------------
|
| Shown on the Diploma Verification card on the homepage. The office sends
| a new verified count every month: update `verified_count`, deploy, then
| run `php artisan config:clear`. Kept here rather than in the database on
| purpose, so the update needs no migration or admin screen.
|
*/

return [
    'verified_count' => 721,
    'verified_since' => 2023,

    'rulebook_url' => 'https://mash.rks-gov.net/sr/alldocuments/pravilnik-o-proceduri-rada-za-izdavanje-uverenja-o-validnosti-diploma-drzavljanima-republike-kosovo-za-diplome-izdate-odstrane-univerziteta-u-severnoj-mitrovici-i-diplome-izdate-odstrane-srednjih-sko/',
];
