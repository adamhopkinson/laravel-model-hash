<?php

namespace AdamHopkinson\LaravelModelHash\Tests;

use AdamHopkinson\LaravelModelHash\LaravelModelHashServiceProvider;
use Illuminate\Database\Schema\Blueprint;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->setupDatabase($this->app);
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelModelHashServiceProvider::class,
        ];
    }

    protected function setupDatabase($app)
    {
        $app['config']->set('database.default', 'sqlite');

        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['db']->connection()->getSchemaBuilder()->create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hash', 5)->unique()->index();
        });

        $app['db']->connection()->getSchemaBuilder()->create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hash', 5)->nullable()->unique()->index();
        });

        $app['db']->connection()->getSchemaBuilder()->create('authors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key', 6)->unique()->index();
        });

        $app['db']->connection()->getSchemaBuilder()->create('books', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hash', 6)->unique()->index();
        });
    }
}
