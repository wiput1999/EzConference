@extends('layouts.partials')

@section('content')
    <div class="container">
        <div class="row">
            <h1>{{ $conference['name'] }}</h1>
        </div>
        <hr />
        <div class="row">
            <h3>Attachment</h3>
        </div>
        <div class="row">
            <h3>Polls</h3>
        </div>
        <div class="row">
            <h3>Questions</h3>
        </div>
    </div>
@endsection