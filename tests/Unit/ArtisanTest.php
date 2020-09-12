<?php

declare(strict_types=1);

namespace AdamHopkinson\LaravelModelHash\Tests;

use AdamHopkinson\LaravelModelHash\Tests\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArtisanTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @throws \Exception
     */
    public function the_populate_command_works()
    {
        // instances to create
        $instances = 1000;

        foreach (range(1, $instances) as $i) {
            $p = new Page();
            $p->hash = null;
            $p->save();
        }

        $this->assertEquals($instances, Page::whereNotNull('hash')->count());
    }
}
