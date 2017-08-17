@extends('layouts.partials')

@section('content')
    <div class="container">
        <div class="row">
            <h1>Conference Details</h1>
        </div>
        <div class="row conference-detail-thumbnail">
            <div class="col-lg-12 text-center">
                <img src="{{ empty($conference['thumbnail']) ? URL('/images/blank-conference-thumbnail.jpg') : $conference['thumbnail'] }}" class="img-fluid">
            </div>
        </div>
        <div class="row conference-detail-content">
            <div class="col-sm-12 col-lg-12">
                <b>Title : </b>{{ $conference['name'] }}<br>
                <b>Description : </b>{{ $conference['description'] }}<br>
                <b>Owner : </b>{{ $conference['owner_name'] }}<br>
                <b>Capacity : </b>{{ $conference['capacity'] }}<br>
                <form method="POST" action="{{ URL('/conference/join/'.$conference['remember_token']) }}">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-success"> Join</button>
                </form>
            </div>
        </div>
    </div>
@endsection