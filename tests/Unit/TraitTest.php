<?php

declare(strict_types=1);

namespace AdamHopkinson\LaravelModelHash\Tests;

use AdamHopkinson\LaravelModelHash\Utils\ModelHashUtils;
use AdamHopkinson\LaravelModelHash\Tests\TestCase;
use AdamHopkinson\LaravelModelHash\Tests\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TraitTest extends TestCase
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

    /**
     * @test
     * @throws \Exception
     */
    public function hashesAreGenerated()
    {
        $iterations = 1; //rand(10,20);
        foreach(range(1, $iterations) as $iteration) {
            Article::create();
        }

        $uniqueHashes = Article::all()->groupBy('hash')->count();
        $this->assertEquals($iterations, $uniqueHashes);
    }


}