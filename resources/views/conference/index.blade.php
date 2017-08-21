@extends('layouts.partials')

@section('content')
    <div class="container">
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
        <div class="row">
            <h1>{{ $conference['name'] }}</h1>
        </div>
        <hr />

        <div class="row">
            <h3>Attachments</h3>
        </div>
        <div class="row">
            <a class="btn btn-primary" href="{{ URL('/conference/'.$conference['remember_token'].'/attachments/new') }}"><span class="oi oi-cloud-upload"></span> Upload</a>
            <table class="table table-striped table-responsive conference-questions-table">
                <thead>
                <tr>
                    <th>Attachments</th>
                    <th>Owner</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($attachments as $attachment)
                    <tr>
                        <td>{{ $attachment['filename'] }}</td>
                        <td>{{ $attachment['owner_name'] }}</td>
                        <td class="text-right">
                            <a class="btn btn-success" href="{{ URL('/conference/'. $conference['remember_token'] . '/attachments/'.$attachment['id']) }}"><span class="oi oi-cloud-download"></span> Download</a>
                            @if($attachment['owner'] == Auth::user()->id)
                                <a class="btn btn-danger" href="{{ URL('/conference/'. $conference['remember_token'] . '/attachments/delete/'. $attachment['id']) }}"><span class="oi oi-delete"></span> Delete</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="row">
            <h3>Questions</h3>
        </div>
        <div class="row">
            <a class="btn btn-success" href="{{ URL('/conference/'.$conference['remember_token'].'/questions/new') }}"><span class="oi oi-plus"></span> Create</a>
            <table class="table table-striped table-responsive conference-questions-table">
                <thead>
                <tr>
                    <th>Questions</th>
                    <th>Owner</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($questions as $question)
                    <tr>
                        <td>{{ $question['question'] }}</td>
                        <td>{{ $question['owner_name'] }}</td>
                        <td class="text-right">
                            <a class="btn btn-success" href="{{ URL('/conference/'. $conference['remember_token'] . '/questions/'.$question['id']) }}"><span class="oi oi-eye"></span> View</a>
                            @if($question['owner'] == Auth::user()->id)
                                <a class="btn btn-primary" href="{{ URL('/conference/'. $conference['remember_token'] . '/questions/'.$question['id'] . '/edit') }}"><span class="oi oi-pencil"></span> Edit</a>
                                <a class="btn btn-danger" href="{{ URL('/conference/'. $conference['remember_token'] . '/questions/'.$question['id'] . '/delete') }}"><span class="oi oi-delete"></span> Delete</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection