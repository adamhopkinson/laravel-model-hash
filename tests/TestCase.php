<?php

namespace AdamHopkinson\LaravelModelKey\Tests;

use AdamHopkinson\LaravelModelKey\LaravelModelKeyServiceProvider;

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
            LaravelModelKeyServiceProvider::class,
        ];
    }

    protected function setupDatabase($app)
    {
        $app['config']->set('database.default', 'sqlite');

        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['db']->connection()->getSchemaBuilder()->create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key', 4);
        });
    }
}