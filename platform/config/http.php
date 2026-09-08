<?php

return [

    /*
    |--------------------------------------------------------------------------
    | CA Bundle for Outbound HTTPS
    |--------------------------------------------------------------------------
    |
    | PHP verifies outbound HTTPS against the CA bundle its cURL/OpenSSL build
    | knows about. On hosts where that bundle is missing (Windows PHP builds,
    | chrooted PHP-FPM) every request fails with cURL error 60 "unable to get
    | local issuer certificate". Point this at a PEM bundle such as
    | https://curl.se/ca/cacert.pem to verify against it instead. Relative
    | paths resolve from the project root. Leave empty to use PHP's default.
    |
    */

    'ca_bundle' => env('HTTP_CA_BUNDLE'),

];
