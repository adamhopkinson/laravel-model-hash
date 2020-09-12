<?php

namespace AdamHopkinson\LaravelModelHash\Console;

use Illuminate\Console\Command;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class PopulateModels extends Command
{
    protected $signature = 'laravelmodelhash:populate {--M|model= : The model to populate with hashes}';

    protected $description = 'Populate hashes for existing models';

    /*
     * Uses reflection to get all models (within app_path())
     * which use the trait
     *
     * @output  array   Array of fully-qualified model names
     */
    public function getModelsWithTrait()
    {
        $models = collect(File::allFiles(app_path()))
            ->map(function ($item) {
                $path = $item->getRelativePathName();
                $class = sprintf(
                    '\%s%s',
                    Container::getInstance()->getNamespace(),
                    strtr(substr($path, 0, strrpos($path, '.')), '/', '\\')
                );

                return $class;
            })
            ->filter(function ($class) {
                $valid = false;

                if (class_exists($class)) {
                    $reflection = new \ReflectionClass($class);
                    $valid = $reflection->isSubclassOf(Model::class) &&
                        !$reflection->isAbstract() &&
                        in_array('AdamHopkinson\LaravelModelHash\Traits\ModelHash', $reflection->getTraitNames());
                }

                return $valid;
            });

        return $models->values();
    }

    /*
     * Does the actual population of the model
     *
     * @param   string  $model  The name of the model to populate
     * @output  void
     */

    /**
     * @param class-string $model
     */
    private function doPopulateModel(string $model)
    {
        $instances = $model::count();
        if ($instances == 0) {
            $this->warn('There are no instances of this model');

            return;
        }
        $hashName = $model::first()->getHashName();

        $instancesMissing = $model::whereNull($hashName)->count();

        $bar = $this->output->createProgressBar($instancesMissing);
        $bar->setFormat("\t".$model.': %current%/%max% ');
        $bar->setMessage($model);
        $bar->start();

        if ($instancesMissing > 0) {
            $model::whereNull($hashName)->each(function ($instance) use ($bar) {
                $instance->makeAndAssignHash();
                $instance->save();
                $bar->advance();
            });
        }

        $bar->finish();
    }

    /*
     * Handles populating a single model,
     * specified in the command option --model or -M
     *
     * @param   string  $model  The name of the model to handle
     * @output  void
     */

    /**
     * @param array|string|true $model
     */
    private function handleSingleModel($model)
    {
        if (!class_exists($model)) {
            $this->error(sprintf('Class %s does not exist', $model));

            return;
        }

        $this->info(sprintf('Populating model %s...', $model));

        $this->doPopulateModel($model);

        $this->info(PHP_EOL.'Done');
    }

    /*
     * Handles populating all models
     *
     * @output  void
     */
    private function handleAllModels()
    {
        $this->info('Populating all existing models...');

        foreach ($this->getModelsWithTrait() as $model) {
            $this->doPopulateModel($model);
        }
        $this->info(PHP_EOL.'Done');
    }

    public function handle()
    {
        $model = $this->option('model');
        if ($model == null) {
            $this->handleAllModels();
        } else {
            $this->handleSingleModel($model);
        }
    }
}
