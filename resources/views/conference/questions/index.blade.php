@extends('layouts.partials')

@section('content')
    <div class="container">
        <div class="row">
            <h1>{{ $question['question'] }}</h1>
        </div>
        <div class="row">
            <div class="col-lg-12">
                {{ $question['description'] }}
            </div>
        </div>
        <div class="row">
            <h2>Answers</h2>
        </div>
        <div class="row">
            <a class="btn btn-success" href="{{ URL('/conference/'. $conference .'/questions/'. $question['id'] .'/answer/new') }}"><span class="oi oi-plus"></span> New Answer</a>
        </div>
        <div class="row answer-card">
            @foreach($answers as $answer)
                <div class="card col-lg-3 col-sm-12 answer-sub-card">
                    <div class="card-body">
                        <h4 class="card-title">{{ $answer['owner_name'] }}</h4>
                        <p class="card-text">{{ $answer['answer'] }}</p>
                        @if($answer['owner'] == Auth::user()->id)
                            <a href="{{ URL('/conference/'. $conference .'/questions/'. $question['id'] .'/answer/'.$answer['id'].'/edit') }}" class="card-link"><span class="oi oi-pencil"></span> Edit</a>
                            <a href="{{ URL('/conference/'. $conference .'/questions/'. $question['id'] .'/answer/'.$answer['id'].'/destroy') }}" class="card-link" style="color: #FF0000"><span class="oi oi-delete"></span> Delete</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection