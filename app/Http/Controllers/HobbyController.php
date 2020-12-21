<?php

namespace App\Http\Controllers;

use App\Hobby;  // import class model with additional functionality from Eloquent (parent class)
use App\Tag; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session; // flash session; used in show(); once shown it gets removed from session; called by using ->with()
// use Illuminate\Support\Carbon;  // great API extension for working with dates and times
use Intervention\Image\Facades\Image;




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
        'description' => 'required|min:5',
        'image' => 'mimes:jpeg,jpg,bmp,png,gif'
      ]);

      // make new instance of Hobby
      $hobby = new Hobby([  // extract params from POST data
          'name' => $request->name, // or: $request['name']
          'description' => $request->description,
          'user_id' => auth()->id()
        ]);
        $hobby->save(); // Eloquent

      // if image available go store it
      if ($request->image) {
        $this->saveImages($request->image, $hobby->id); // refactored into public function at EOF
      }

      return redirect('./hobby/'.$hobby->id)->with(  // redirect to hobby detail
        [
          'message_warning' => 'Please assign some Tags to your Hobby'
        ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Hobby  $hobby
     * @return \Illuminate\Http\Response
     */
    public function show(Hobby $hobby)  // arrives with the $hobby object referenced in the dynamic segment with $hobby->id in blade.index (overview of all hobbis)
    {
      $allTags = Tag::all();
      $usedTags = $hobby->tags; // no () even though it calls the function 
      $availableTags = $allTags->diff($usedTags); // neat function that returns the difference
      return view('hobby.show')->with(  // \resources\views\hobby\show.blade
        [
          'hobby' => $hobby,
          'availableTags' => $availableTags,
          'message_success' => Session::get('message_success'),
          'message_warning' => Session::get('message_warning')          
        ]
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
          'hobby'=>$hobby,
          'message_success' => Session::get('message_success'),
          'message_warning' => Session::get('message_warning')  
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
        'description' => 'required|min:5',
        'image' => 'mimes:jpeg,jpg,bmp,png,gif'
      ]);
      if ($request->image) {
        $this->saveImages($request->image, $hobby->id); // refactored into public function at EOF
      }

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
    // save 3 versions of the hobby picture
    public function saveImages($imageInput, $hobby_id) 
    {
      $image = Image::make($imageInput);  // implementing Facades method; create instance of class Image
      if ($image->width() > $image->height()) { //  this would be landscape
        // create landscape pics
        $image->widen(1200)
              ->save(public_path().'/img/hobbies/'.$hobby_id.'_large.jpg')
              ->widen(400)->pixelate(12)
              ->save(public_path().'/img/hobbies/'.$hobby_id.'_pixelated.jpg');
        $image = Image::make($imageInput); // create new instance for thumbnail, otherwise pixelated
        $image->widen(60)
              ->save(public_path().'/img/hobbies/'.$hobby_id.'_thumb.jpg');
      } else {  
        // create portrait pics
        $image->heighten(900)
        ->save(public_path().'/img/hobbies/'.$hobby_id.'_large.jpg')
        ->heighten(400)->pixelate(12)
        ->save(public_path().'/img/hobbies/'.$hobby_id.'_pixelated.jpg');
        $image = Image::make($imageInput); // create new instance for thumbnail, otherwise pixelated
        $image->heighten(60)
              ->save(public_path().'/img/hobbies/'.$hobby_id.'_thumb.jpg');
      } 
    } 
    
    // delete all 3 versions of image
    public function deleteImages($hobby_id) 
    {
      if (file_exists(public_path().'/img/hobbies/'.$hobby_id.'_large.jpg'))
        unlink(public_path().'/img/hobbies/'.$hobby_id.'_large.jpg');
      if (file_exists(public_path().'/img/hobbies/'.$hobby_id.'_thumb.jpg'))
        unlink(public_path().'/img/hobbies/'.$hobby_id.'_thumb.jpg');
      if (file_exists(public_path().'/img/hobbies/'.$hobby_id.'_pixelated.jpg'))
        unlink(public_path().'/img/hobbies/'.$hobby_id.'_pixelated.jpg');

      return back()->with(  
        [
          'message_success' => 'Your image has been deleted.'
        ]
      );     
    }
}
