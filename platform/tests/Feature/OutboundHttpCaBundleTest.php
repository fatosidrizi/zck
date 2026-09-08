<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * HTTP_CA_BUNDLE exists for hosts whose PHP has no CA store (cURL error 60 on
 * every outbound HTTPS call). The importer and every other Http call must pick
 * it up without per-call configuration.
 */
class OutboundHttpCaBundleTest extends TestCase
{
    public function test_php_default_ca_store_is_used_when_nothing_is_configured(): void
    {
        config(['http.ca_bundle' => null]);

        $this->assertArrayNotHasKey('verify', Http::createPendingRequest()->getOptions());
    }

    public function test_blank_value_is_treated_as_unset(): void
    {
        config(['http.ca_bundle' => '']);

        $this->assertArrayNotHasKey('verify', Http::createPendingRequest()->getOptions());
    }

    public function test_absolute_unix_path_is_passed_through(): void
    {
        config(['http.ca_bundle' => '/etc/ssl/certs/ca-certificates.crt']);

        $this->assertSame('/etc/ssl/certs/ca-certificates.crt', Http::createPendingRequest()->getOptions()['verify']);
    }

    public function test_windows_drive_path_is_passed_through(): void
    {
        config(['http.ca_bundle' => 'C:\\php\\extras\\ssl\\cacert.pem']);

        $this->assertSame('C:\\php\\extras\\ssl\\cacert.pem', Http::createPendingRequest()->getOptions()['verify']);
    }

    public function test_relative_path_resolves_from_project_root(): void
    {
        config(['http.ca_bundle' => 'storage/certs/cacert.pem']);

        $this->assertSame(base_path('storage/certs/cacert.pem'), Http::createPendingRequest()->getOptions()['verify']);
    }

    public function test_bundle_is_read_at_request_time_not_boot_time(): void
    {
        config(['http.ca_bundle' => null]);
        Http::createPendingRequest();

        config(['http.ca_bundle' => '/tmp/late.pem']);

        $this->assertSame('/tmp/late.pem', Http::createPendingRequest()->getOptions()['verify']);
    }
}
