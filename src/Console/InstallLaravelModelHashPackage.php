<?php

namespace AdamHopkinson\LaravelModelHash\Console;

use Illuminate\Console\Command;

class InstallLaravelModelHashPackage extends Command
{
    protected $signature = 'laravelmodelhash:install';

    protected $description = 'Install Laravel Model Hash';

    /**
     * Handle the command
     *
     * @return void
     */
    public function handle(): void
    {
        $this->info('Installing Laravel Model Hash..');
        $this->info('Publishing configuration...');
        $this->call('vendor:publish', [
            '--provider' => 'AdamHopkinson\LaravelModelHash\LaravelModelHashServiceProvider',
            '--tag'      => 'config',
        ]);
        $this->info('Done');
    }
}
