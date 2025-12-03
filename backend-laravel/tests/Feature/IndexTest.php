<?php

namespace Tests\Feature;

use Tests\TestCase;

class IndexTest extends TestCase
{
    public function test_index_page_returns_login_view(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }
}
