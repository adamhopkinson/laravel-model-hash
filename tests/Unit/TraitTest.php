<?php

declare(strict_types=1);

namespace AdamHopkinson\LaravelModelHash\Tests;

use AdamHopkinson\LaravelModelHash\Exceptions\InvalidCharactersInAlphabet;
use AdamHopkinson\LaravelModelHash\Tests\Models\Article;
use AdamHopkinson\LaravelModelHash\Tests\Models\Author;
use AdamHopkinson\LaravelModelHash\Tests\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TraitTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @throws \Exception
     */
    public function models_are_created()
    {
        $article = Article::create($record = []);
        $this->assertDatabaseHas('articles', $record);
    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function author_is_created()
    {
        $author = Author::create($record = []);
        $this->assertDatabaseHas('authors', $record);
    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function hash_is_not_null()
    {
        $this->assertNotNull(Article::create()->hash);
//        $this->assertNotNull(Author::create()->hash);
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
            $article = Article::create();
        }

        $uniqueHashes = Article::all()->groupBy(Article::first()->getHashName())->count();
        $this->assertEquals($iterations, $uniqueHashes);

        $iterations = 1000; //rand(10,20);
        foreach (range(1, $iterations) as $iteration) {
            $author = Author::create();
        }

        $uniqueHashes = Author::all()->groupBy(Author::first()->getHashName())->count();
        $this->assertEquals($iterations, $uniqueHashes);
    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function config_is_taken_from_model_properties()
    {
        // the author model uses model-specific config
        $author = Author::create();

        // check that all the model-specific config variables are different to the central config settings
        $this->assertNotEquals($author->getHashName(), config('laravelmodelhash.default_name'));
        $this->assertNotEquals($author->getHashLength(), config('laravelmodelhash.default_length'));
        $this->assertNotEquals($author->getHashAlphabet(), config('laravelmodelhash.default_alphabet'));
        $this->assertNotEquals($author->getHashMaximumAttempts(), config('laravelmodelhash.maximum_attempts'));
        $this->assertNotEquals($author->getUseHashInRoutes(), config('laravelmodelhash.use_hash_in_routes'));
    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function throws_error_with_invalid_characters_in_alphabet()
    {
        $this->expectException(InvalidCharactersInAlphabet::class);
        Book::create($record = []);
    }
}
