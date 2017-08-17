@extends('layouts.partials')

@section('content')
    <div class="container">
        <div class="row">
            <h1>Profile</h1>
        </div>
        <div class="row">
            <div class="col-sm-12 col-lg-3 text-center">
                <img src="{{ empty(Auth::user()->profile) ? "/images/blank-profile.svg" : Auth::user()->profile }}" alt="" class="img-fluid rounded-circle">
            </div>
            <div class="col-sm-12 col-lg-9">
                <b>Name : </b>{{ Auth::user()->name }}<br>
                <b>E-mail : </b>{{ Auth::user()->email }}<br>
            </div>
        </div>
    </div>
@endsection