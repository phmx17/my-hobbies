<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;
use App\Hobby;
use Illuminate\Support\Facades\Gate;

class hobbyTagController extends Controller
{
  public function getFilteredHobbies($tag_id) {
    $tag = new Tag();
    $hobbies = $tag::findOrFail($tag_id)->filteredHobbies()->paginate(10);
    $filter = $tag::find($tag_id);  // find the one tag being filtered for
    return view('hobby.index', [  // alternate use of [''=> $] instead of ->with()
      'hobbies' => $hobbies,
      'filter' => $filter
    ]);
  }
  public function attachTag($hobby_id, $tag_id) {
    $hobby = Hobby::find($hobby_id);
    // check policy
    if (Gate::denies('connect_hobbyTag', $hobby)) {
      abort(403, 'No way! This hobby is not yours!!');
    }
    $tag = Tag::find($tag_id);
    $hobby->tags()->attach($tag_id);
    return back()->with(
      [
        'message_success' => 'The tag <b>'.$tag->name.'</b> has been added.'
      ]
    );
  }
  public function detachTag($hobby_id, $tag_id) {
    $hobby = Hobby::find($hobby_id);
    // check policy
    if (Gate::denies('connect_hobbyTag', $hobby)) {
      abort(403, "No way! This hobby is not yours!!");
    }
    $tag = Tag::find($tag_id);
    $hobby->tags()->detach($tag_id);
    return back()->with(
      [
        'message_success' => 'The tag <b>'.$tag->name.'</b> has been removed.'
      ]
    );
  }

}