<?php

namespace AdamHopkinson\LaravelModelHash\Tests\Models;

use AdamHopkinson\LaravelModelHash\Traits\ModelHash;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use ModelHash;

    /** @var bool */
    public $timestamps = false;

    /** @var array */
    protected $guarded = [];

    /** @var string */
    protected $table = 'pages';
}
