<?php

namespace Tests\Feature;

use App\Models\Community;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommunityImageTest extends TestCase
{
    use RefreshDatabase;

    private function community(string $slug, ?string $image = null): Community
    {
        $community = new Community(['slug' => $slug, 'image' => $image]);
        $community->setTranslations('name', ['en' => ucfirst($slug)]);
        $community->setTranslations('description', ['en' => 'About the community.']);
        $community->save();

        return $community;
    }

    public function test_an_uploaded_image_takes_precedence_over_the_bundled_one(): void
    {
        $community = $this->community('croatian', 'communities/custom.png');

        $this->assertSame(asset('storage/communities/custom.png'), $community->imageUrl());
    }

    public function test_a_bundled_flag_is_used_when_nothing_was_uploaded(): void
    {
        $this->assertSame(asset('images/communities/croatian.png'), $this->community('croatian')->imageUrl());
        $this->assertSame(asset('images/communities/roma.svg'), $this->community('roma')->imageUrl());
        $this->assertNull($this->community('no-such-community')->imageUrl());
    }

    public function test_the_index_lists_names_without_images_and_the_detail_page_shows_the_flag(): void
    {
        $this->community('croatian');
        $this->community('no-such-community');

        $this->get('/en/communities')
            ->assertOk()
            ->assertSee('Croatian')
            ->assertSee('No-such-community')
            ->assertDontSee('images/communities/');

        $this->get('/en/communities/croatian')
            ->assertOk()
            ->assertSee('images/communities/croatian.png');
    }
}
