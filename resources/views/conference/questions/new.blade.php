@extends('layouts.partials')

@section('content')
    <div class="container">
        <div class="row">
            <h1>New Question</h1>
        </div>
        <div class="row">
            @if (count($errors) > 0)
                <div class="alert alert-danger" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <div class="col-lg-12">
            <form action="{{ URL('/conference/'.$conference.'/questions/new') }}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="owner" value="{{ Auth::user()->id }}">
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