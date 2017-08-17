@extends('layouts.partials')

@section('content')
    <div class="container">
        <div class="row">
            <h1>Dashboard</h1>
        </div>
        <div class="row">
            <a class="btn btn-success" href="/conference/new">New</a>
        </div>
        <div class="row dashboard-table">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Capacity</th>
                        <th>Owner</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($conferences as $conference)
                        <tr>
                            <td scope="row">{{ $conference['id'] }}</td>
                            <td>{{ $conference['name'] }}</td>
                            <td>{{ $conference['capacity'] }}</td>
                            <td>{{ $conference['owner_name'] }}</td>
                            <td>
                                <a class="btn btn-success" href="{{ URL('/conference/view/'. $conference['remember_token']) }}"><span class="oi oi-eye"></span> View</a>
                                @if($conference['owner'] == Auth::user()->id)
                                    <a class="btn btn-primary" href="{{ URL('/conference/edit/'. $conference['remember_token']) }}"><span class="oi oi-pencil"></span> Edit</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection