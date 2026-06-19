<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageSliderTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_homepage_slider_displays_database_movies(): void
    {
        $this->seed();

        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('data-slider', false)
            ->assertSee('Inception')
            ->assertSee('Interstellar')
            ->assertSee('Filter movies by genre')
            ->assertSee('/?search=Drama#movies', false)
            ->assertSee('data-slider-dots', false);

        $html = $response->getContent();
        $this->assertLessThan(
            strpos($html, '<nav class="movie-chips"'),
            strpos($html, '<section class="slider-section"'),
        );
        $this->assertLessThan(
            strpos($html, '<div class="slider-shell'),
            strpos($html, '<nav class="movie-chips"'),
        );
    }

    public function test_homepage_search_filters_slider_movies_by_title_or_genre(): void
    {
        $this->seed();

        $this->get('/?search=Drama')
            ->assertOk()
            ->assertSee('Joker')
            ->assertDontSee('Inception');

        $this->get('/?search=Matrix')
            ->assertOk()
            ->assertSee('The Matrix')
            ->assertDontSee('Joker');
    }
}
