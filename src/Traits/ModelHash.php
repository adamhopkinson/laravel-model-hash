<?php

namespace AdamHopkinson\LaravelModelHash\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait ModelHash
{

    public static function bootModelKey()
    {
        static::creating(function ($model) {
            $modelType = get_class($model);
            $tableName = with($model)->getTable();

            // get the name of the column to use for the key
            // first check to see if the model has a setter
            // then fallback to the default value from config
            if(method_exists($model, 'getKeyName')) {
                $hashName = $model->getHashName();
            } else {
                $hashName = config('laravelmodelhash.default_name');
            }

            Log::info('Key name is ' . $hashName);

            // work out the database type
            $connectionType = config('database.default');

            //$con = DB::connection();

            $keyLength = config('laravelmodelhash.default_length');

            if(method_exists($model, 'getHashLength')) {}
            // use doctrine to get the length of the key

            $model->hash = 'abcde';
        });
    }

}