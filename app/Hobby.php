<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hobby extends Model // supply Eloquent functions
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name', 'description',
  ];
}
