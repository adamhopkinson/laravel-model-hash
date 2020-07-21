<?php

namespace AdamHopkinson\LaravelModelKey\Tests\Models;

//use AdamHopkinson\LaravelModelKey\
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{

    /** @var bool */
    public $timestamps = false;

    /** @var array */
    protected $guarded = [];

    /** @var string */
    protected $table = 'articles';

}