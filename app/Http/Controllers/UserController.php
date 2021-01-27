<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session; // flash session; used in show(); once shown it gets removed from session; called by using ->with()
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Gate;  // allow functions only through certain policies

class UserController extends Controller
{
    public function __construct() 
    {
      $this->middleware('auth')->except(['show']); // require auth everywhere but for index and show views
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)  // arrives with the $hobby object referenced in the dynamic segment with $hobby->id in blade.index (overview of all hobbis)
    {
      return view('user.show')->with(  // \resources\views\hobby\show.blade
        ['user' => $user]
      );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)    
    {      
      abort_unless(Gate::allows('update', $user), 403);  // edit page is only accessible if user is also allowed to update();
      
      return view('user.edit')->with([
        'user'=>$user,
        'message_success' => Session::get('message_success'),
        'message_warning' => Session::get('message_warning')  
      ]);      
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {  
      abort_unless(Gate::allows('update', $user), 403);  // edit page is only accessible if user is also allowed to update();     
      
      $request->validate([
        'motto' => 'required|min:2', // use pipe to separate validators; in blade use $errors->first('name) to get first error if there are several
        'image' => 'mimes:jpeg,jpg,bmp,png,gif'
      ]);

      // save image if one was sent
      if ($request->image) {
        $this->saveImages($request->image, $user->id); // refactored into public function at EOF
      }

      // call update instead of creating new instance
      $user->update([  // extract params from POST data
          'motto' => $request->motto,
          'about_me' => $request->about_me          
        ]);

      return redirect('/home')->with(  // redirect to user's dashboard (homw) page
        [
          'message_success' => 'Your user profile for <b>'.$user->name.'</b> has been updated.'
        ]
      );  
  }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
      abort_unless(Gate::allows('delete', $user), 403);  // edit page is only accessible if user is also allowed to update();
    }

    // save three versions of user image
    public function saveImages($imageInput, $user_id) 
    {
      $image = Image::make($imageInput);  // implementing Facades method; create instance of class Image
      if ($image->width() > $image->height()) { //  this would be landscape
        // create landscape pics
        $image->widen(500)
              ->save(public_path().'/img/users/'.$user_id.'_large.jpg')
              ->widen(300)->pixelate(12)
              ->save(public_path().'/img/users/'.$user_id.'_pixelated.jpg');
        $image = Image::make($imageInput); // create new instance for thumbnail, otherwise pixelated
        $image->widen(60)
              ->save(public_path().'/img/users/'.$user_id.'_thumb.jpg');
      } else {  
        // create portrait pics
        $image->heighten(500)
        ->save(public_path().'/img/users/'.$user_id.'_large.jpg')
        ->heighten(300)->pixelate(12)
        ->save(public_path().'/img/users/'.$user_id.'_pixelated.jpg');
        $image = Image::make($imageInput); // create new instance for thumbnail, otherwise pixelated
        $image->heighten(60)
              ->save(public_path().'/img/users/'.$user_id.'_thumb.jpg');
      } 
    } 

    // delete all 3 versions of user image
    public function deleteImages($user_id) 
    {
      if (file_exists(public_path().'/img/users/'.$user_id.'_large.jpg'))
        unlink(public_path().'/img/users/'.$user_id.'_large.jpg');
      if (file_exists(public_path().'/img/users/'.$user_id.'_thumb.jpg'))
        unlink(public_path().'/img/users/'.$user_id.'_thumb.jpg');
      if (file_exists(public_path().'/img/users/'.$user_id.'_pixelated.jpg'))
        unlink(public_path().'/img/users/'.$user_id.'_pixelated.jpg');

      return back()->with(  
        [
          'message_success' => 'Your image has been deleted.'
        ]
      );     
    }
}
