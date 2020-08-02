<?php

declare(strict_types=1);

namespace AdamHopkinson\LaravelModelHash\Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class InstallationTest extends TestCase
{
    /** @test */
    public function the_install_command_copies_the_configuration()
    {
        // make sure we're starting from a clean state
        if (File::exists(config_path('laravelmodelhash.php'))) {
            unlink(config_path('laravelmodelhash.php'));
        }

        $this->assertFalse(File::exists(config_path('laravelmodelhash.php')));

        Artisan::call('laravelmodelhash:install');

        $this->assertTrue(File::exists(config_path('laravelmodelhash.php')));
    }
}