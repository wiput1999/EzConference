@extends('layouts.partials')

@section('content')
    <div class="container">
        <div class="row">
            <h1>New Question</h1>
        </div>
        <div class="row">
            @include('layouts.error')
        </div>
        <div class="col-lg-12">
            <form action="{{ URL('/conference/'.$conference.'/questions/new') }}" method="POST">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="Question">Question</label>
                    <input type="text" class="form-control" id="Question" name="question" placeholder="Question" value="{{ old('question') }}">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary"><span class="oi oi-plus"></span> Create</button>
            </form>
        </div>
    </div>
@endsection