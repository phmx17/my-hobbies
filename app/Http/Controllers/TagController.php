<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
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
      $oldName = $tag->name;
      $tag->delete();

      return $this->index()->with(  // redirect by calling index()
        [
          'message_success' => 'Your tag <b>'.$oldName.'</b> has been deleted.'
        ]
      );       
    }
}
