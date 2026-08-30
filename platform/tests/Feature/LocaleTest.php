<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocaleTest extends TestCase
{
    use RefreshDatabase;

    public function test_each_supported_locale_serves_the_homepage_in_its_language(): void
    {
        $this->get('/en')->assertOk()->assertSee('Office for Community Issues');
        $this->get('/sq')->assertOk()->assertSee('Zyra për Çështje të Komuniteteve');
        $this->get('/sr')->assertOk()->assertSee('Kancelarija za pitanja zajednica');
    }

    public function test_an_unsupported_locale_prefix_is_not_routed(): void
    {
        $this->get('/de')->assertNotFound();
    }

    public function test_the_root_redirects_to_the_default_locale(): void
    {
        $this->get('/')->assertRedirect('/'.config('app.locale'));
    }
}
