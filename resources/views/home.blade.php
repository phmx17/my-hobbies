@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                  <div class="row">
                    <div class="col-md-9">
                      <h2>Hello {{ auth()->user()->name }}</h2>
                      <h5>My Motto:</h5>
                      <p>{{ auth()->user()->motto ?? '' }}</p>
                      <h5>About Me:</h5>
                      <p>{{ auth()->user()->about_me ?? '' }}</p>
                      <!-- Edit profile button -->
                      <a class="btn btn-sm btn-primary mb-4" href="/user/{{ auth()->user()->id }}/edit"><i class="fas fa-edit"></i> Edit Profile</a>
                    </div>

                    <!-- Right Column Display User Image -->
                    <div class="col-md-3">
                      @if(file_exists('img/users/'.auth()->user()->id.'_large.jpg'))
                        <a href="/img/users/{{ auth()->user()->id}}_large.jpg" data-lightbox="img/users/{{ auth()->user()->id }}_large.jpg" data-title="{{ auth()->user()->name }}">
                          <img class="img-thumbnail" src="/img/users/{{ auth()->user()->id }}_large.jpg" alt="large thumbnail">
                        </a>
                        <i class="fa fa-search-plus"></i> Click Image to Enlarge
                      @endif 
                    </div>
                  </div>

                    <!-- Display Hobbies -->
                    @isset($hobbies)
                        @if($hobbies->count() > 0)
                        <h3>My Hobbies:</h3>
                        @endif
                    <ul class="list-group">
                        @foreach($hobbies as $hobby)
                            <li class="list-group-item">
                                @if(file_exists('img/hobbies/'.$hobby->id.'_thumb.jpg'))
                                  <a title="Show Details" href="/hobby/{{ $hobby->id }}">
                                    <img src="/img/hobbies/{{ $hobby->id }}_thumb.jpg" alt="Hobby Thumb">
                                  </a>
                                @endif
                                  &nbsp; <!-- non-breaking space -->
                                  <a title="Show Details" href="/hobby/{{ $hobby->id }}">{{ $hobby->name }}</a>
                                @auth
                                    <a class="btn btn-sm btn-light ml-2" href="/hobby/{{ $hobby->id }}/edit"><i class="fas fa-edit"></i> Edit Hobby</a>
                                @endauth

                                @auth
                                    <form class="float-right" style="display: inline" action="/hobby/{{ $hobby->id }}" method="post">
                                        @csrf
                                        @method("DELETE")
                                        <input class="btn btn-sm btn-outline-danger" type="submit" value="Delete">
                                    </form>
                                @endauth
                                <span class="float-right mx-2">{{ $hobby->created_at->diffForHumans() }}</span>
                                <br>
                                @foreach($hobby->tags as $tag)
                                    <a href="/hobby/tag/{{ $tag->id }}"><span class="badge badge-{{ $tag->style }}">{{ $tag->name }}</span></a>
                                @endforeach
                            </li>
                        @endforeach
                    </ul>
                    @endisset

                    <a class="btn btn-success btn-sm" href="/hobby/create"><i class="fas fa-plus-circle"></i> Create new Hobby</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
