<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BirthdayCurrencySearch extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'rate', 'currency', 'birthday',
    ];

    protected $dates = ['birthday'];
}
