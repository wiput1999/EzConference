@extends('layouts.partials')

@section('content')
    <div class="container">
        <div class="row">
            <h1>Edit Answer</h1>
        </div>
        <div class="row">
            @include('layouts.error')
        </div>
        <div class="col-lg-12">
            <form action="{{ URL('/conference/'. $conference .'/questions/'. $question .'/answer/'.$answer['id'].'/edit') }}" method="POST">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="answer">Answer</label>
                    <textarea class="form-control" id="answer" name="answer" rows="3">{{ $answer['answer'] }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary"> Save</button>
            </form>
        </div>
    </div>
@endsection