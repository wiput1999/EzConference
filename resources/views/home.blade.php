@extends('layouts.partials')
@section('content')
    <div class="container-fluid index-header">
        <div class="overlay text-center">
            <h1 class="index-title">Make conference<br>More participation</h1>
            <div class="index-subtitle">
                <a href="{{ URL('/register') }}" class="index-button">Try out Today!</a>
            </div>
        </div>
    </div>
@endsection