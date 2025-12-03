<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomePageTest extends TestCase
{
    public function test_home_page_redirects_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('login'));
    }

    public function test_login_page_has_register_button(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee(route('register'));
        $response->assertSee('Register now');
        $response->assertSee('images/logo.jpg');
    }
}
