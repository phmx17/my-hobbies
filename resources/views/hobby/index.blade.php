@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-header">All Hobbies</div>
                <div class="card-body">
                    <ul class="list-group">
                      @foreach($hobbies as $hobby)
                        <li class="list-group-item">
                          <a title="Show Details" href="/hobby/{{ $hobby->id }}">{{ $hobby->name }}</a>
                          <a class="btn btn-sm btn-light ml-2" href="/hobby/{{ $hobby->id }}/edit"><i class="fa fa-edit" ></i>Edit Hobby</a> 
                          <!-- delete button as form -->
                          <form method="POST" class="float-right" action="/hobby/{{ $hobby->id }}">
                            @csrf
                            @method('DELETE')
                            <input class="btn btn-sm btn-danger ml-2 float-right" type="submit" value="Delete" >
                          </form>
                        </li>
                      @endforeach
                    </ul>            
                </div>
            </div>
            <div class="mt-2">
              <a class="btn btn-success btn-sm" href="/hobby/create"><i class="fas fa-plus-circle"></i> Create new Hobby</a>
            </div>
        </div>
    </div>
</div>
@endsection
