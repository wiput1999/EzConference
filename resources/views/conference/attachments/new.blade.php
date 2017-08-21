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
            <form action="{{ URL('/conference/'.$conference.'/attachments/new') }}" method="POST" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <div class="form-group">
                    <label for="filename">Filename</label>
                    <input type="text" class="form-control" id="filename" name="filename" placeholder="Filename" value="{{ old('filename') }}">
                </div>
                <div class="form-group">
                    <label for="attachments">Attachments (Size limit 10 MB, PDF only)</label>
                    <input type="file" class="form-control-file" id="attachments" name="attachment" accept="application/pdf"  required>
                </div>
                <button type="submit" class="btn btn-primary"><span class="oi oi-cloud-upload"></span> Upload</button>
            </form>
        </div>
    </div>
@endsection