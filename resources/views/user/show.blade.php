@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-header">User Detail</div>
                <div class="card-body">
                  <b>{{ $user->name }}</b>
                  <p>{{ $user->motto }}</p>
                  <p>{{ $user->about_me }}</p>        
                </div>
            </div>
            <div class="mt-2">
            <a class="btn btn-primary btn-sm" href="{{ URL::previous() }}"><i class="fas fa-arrow-circle-up"></i> Back to Overview</a>
            </div>
        </div>
    </div> 
</div>
@endsection
