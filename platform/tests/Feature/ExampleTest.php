<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        // The root redirects to the default locale; the localized homepages
        // themselves are covered by LocaleTest.
        $response = $this->get('/');

        $response->assertRedirect('/'.config('app.locale'));
    }
}
