<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;  // allow functions only through policies

class TagController extends Controller
{
    public function __construct() 
    {
      $this->middleware('auth')->except(['index']); // require auth everywhere but for index and show views
      $this->middleware('admin')->except(['index']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $tags = Tag::all();  // model; :all is an Eloquent function
      return view('tag.index')->with([
        'tags' => $tags
      ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('tag.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $request->validate([
        'name' => 'required', 
        'style' => 'required'
      ]) ;

      // make new instance of Hobby
      $tag = new Tag([  // extract params from POST data
          'name' => $request->name, // or: $request['name']
          'style' => $request->style
        ]);
        $tag->save(); // Eloquent

        return $this->index()->with(  // redirect by calling index()
          [
            'message_success' => 'Your tag <b>'.$tag->name.'</b> has been created.'
          ]
        );  // redirect by calling index()      
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {   
      abort_unless(Gate::allows('update', $tag), 403);  // gate policy 
           
      return view('tag.edit')->with([
        'tag'=>$tag
      ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
      abort_unless(Gate::allows('update', $tag), 403);  // gate policy 
      $request->validate([
        'name' => 'required', 
        'style' => 'required'
      ]) ;

      $tag->update([  // extract params from POST data
          'name' => $request->name, // or: $request['name']
          'style' => $request->style
        ]);

        return $this->index()->with(  // redirect by calling index()
          [
            'message_success' => 'Your tag <b>'.$tag->name.'</b> has been updated.'
          ]
        );        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
      abort_unless(Gate::allows('delete', $tag), 403);  // gate policy

      $oldName = $tag->name;
      $tag->delete();

      return $this->index()->with(
        [
          'message_success' => 'Your tag <b>'.$oldName.'</b> has been deleted.'
        ]
      );       
    }
}
