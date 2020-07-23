<?php

namespace AdamHopkinson\LaravelModelHash\Tests\Models;

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