<?php

namespace AdamHopkinson\LaravelModelHash\Tests\Models;

use AdamHopkinson\LaravelModelHash\Traits\ModelHash;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use ModelHash;

    /** @var bool */
    public $timestamps = false;

    /** @var array */
    protected $guarded = [];

    /** @var string */
    protected $table = 'authors';

    protected $hashName = 'key'; // string
    protected $hashLength = 6; // int
    protected $hashAlphabet = 'abcdefghj12345'; // string
    protected $hashMaximumAttempts = 20; // int
    protected $useHashInRoutes = false; // boolean
}
