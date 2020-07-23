<?php

declare(strict_types=1);

namespace AdamHopkinson\LaravelModelHash\Tests;

use AdamHopkinson\LaravelModelHash\Utils\ModelHashUtils;
use AdamHopkinson\LaravelModelHash\Tests\TestCase;
use AdamHopkinson\LaravelModelHash\Tests\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TraitTests extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @throws \Exception
     */
    public function database_type_is_returned()
    {
        $type = ModelHashUtils::getDatabaseType();

        $this->assertIsString($type);
    }
}