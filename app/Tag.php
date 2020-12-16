<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

  public function hobbies() {
    return $this->belongsToMany('App\Hobby');
  }

  public function filteredHobbies() {
    return $this->belongsToMany('App\Hobby')
      ->wherePivot('tag_id', $this->id)  // restrict to tag ids matching in a to-many relation
      ->orderBy('updated_at', 'DESC');  // oder
  }

   /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name', 'style',
  ];
}
