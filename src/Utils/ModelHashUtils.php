<?php

namespace AdamHopkinson\LaravelModelHash\Utils;

class ModelHashUtils
{
    public static function getDatabaseType()
    {
        return config('database.default');
    }
}