<?php

declare(strict_types=1);

namespace AdamHopkinson\LaravelModelHash\Tests;

use AdamHopkinson\LaravelModelHash\Tests\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TraitTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @throws \Exception
     */
    public function article_is_created()
    {
        $article = Article::create($record = []);

        $this->assertDatabaseHas('articles', $record);
    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function hash_is_not_null()
    {
        $this->assertNotNull(Article::create()->hash);
    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function hashes_are_unique()
    {
        $iterations = 1000; //rand(10,20);
        foreach (range(1, $iterations) as $iteration) {
            Article::create();
        }

        $uniqueHashes = Article::all()->groupBy('hash')->count();
        $this->assertEquals($iterations, $uniqueHashes);
    }
}
