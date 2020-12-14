<?php

namespace App\Http\Controllers;

use App\Hobby;  // import class model with additional functionality from Eloquent (parent class)
use Illuminate\Http\Request;
// use Illuminate\Support\Carbon;  // great API extension for working with dates and times

class HobbyController extends Controller
{
    public function __construct() {
      $this->middleware('auth')->except(['index', 'show']); // require auth everywhere but for index and show views
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $hobbies = Hobby::all();  // model; :all is an Eloquent function
        // $hobbies = Hobby::paginate(10); // pagination
        $hobbies = Hobby::orderBy('created_at', 'DESC')->paginate(10); // descending order and paginate
        return view('hobby.index')->with([
          'hobbies' => $hobbies
        ]);       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('hobby.create');
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
        'name' => 'required|min:3', // use pipe to separate validators; in blade use $errors->first('name) to get first error if there are several
        'description' => 'required|min:5'
      ]) ;

      // make new instance of Hobby
      $hobby = new Hobby([  // extract params from POST data
          'name' => $request->name, // or: $request['name']
          'description' => $request->description,
          'user_id' => auth()->id()
        ]);
        $hobby->save(); // Eloquent

        return $this->index()->with(  // redirect by calling index()
          [
            'message_success' => 'Your hobby <b>'.$hobby->name.'</b> has been created.'
          ]
        );  // redirect by calling index()
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Hobby  $hobby
     * @return \Illuminate\Http\Response
     */
    public function show(Hobby $hobby)  // arrives with the $hobby object referenced in the dynamic segment with $hobby->id in blade.index (overview of all hobbis)
    {
        return view('hobby.show')->with(  // \resources\views\hobby\show.blade
          ['hobby' => $hobby]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Hobby  $hobby
     * @return \Illuminate\Http\Response
     */
    public function edit(Hobby $hobby)    // route model binding
    {
        return view('hobby.edit')->with([
          'hobby'=>$hobby
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Hobby  $hobby
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Hobby $hobby)  // $request = form data
    { 
      $request->validate([
        'name' => 'required|min:3', // use pipe to separate validators; in blade use $errors->first('name) to get first error if there are several
        'description' => 'required|min:5'
      ]) ;

      // call update instead of creating new instance
      $hobby->update([  // extract params from POST data
          'name' => $request->name, // or: $request['name']
          'description' => $request->description
        ]);

        return $this->index()->with(  // redirect by calling index()
          [
            'message_success' => 'Your hobby <b>'.$hobby->name.'</b> has been updated.'
          ]
        );  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Hobby  $hobby
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hobby $hobby)
    {
        $oldName = $hobby->name;
        $hobby->delete();

        return $this->index()->with(  // redirect by calling index()
          [
            'message_success' => 'Your hobby <b>'.$oldName.'</b> has been deleted.'
          ]
        ); 
    } 
}
