<?php

declare(strict_types=1);

namespace AdamHopkinson\LaravelModelHash\Tests;

use AdamHopkinson\LaravelModelHash\Tests\TestCase;
use AdamHopkinson\LaravelModelHash\Tests\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateArticleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @throws \Exception
     */
    public function article_is_created()
    {
        $article = Article::query()->create($record = ['key' => 'abcde']);

        $this->assertDatabaseHas('articles', $record);
    }
}