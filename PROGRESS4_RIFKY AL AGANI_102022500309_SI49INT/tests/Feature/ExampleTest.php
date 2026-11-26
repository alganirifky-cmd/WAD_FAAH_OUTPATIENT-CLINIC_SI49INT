<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_root_redirects_to_course_catalogue(): void
    {
        $this->get('/')->assertRedirect('/drugs');
    }
}