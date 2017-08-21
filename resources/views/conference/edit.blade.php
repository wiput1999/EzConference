@extends('layouts.partials')

@section('content')
    <div class="container">
        <div class="row">
            <h1>Edit Conference</h1>
        </div>
        <div class="row">
            @include('layouts.error')
        </div>
        <div class="row">
            <div class="col-lg-12">
                <form action="{{ URL('/conference/edit/'.$token) }}" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="InputName">Name</label>
                        <input type="text" class="form-control" id="InputName" name="name" placeholder="Conference Name" value="{{ $conference['name'] }}">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required>{{ $conference['description'] }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="capacity">Capacity</label>
                        <input type="text" class="form-control" id="capacity" name="capacity" placeholder="Capacity" value="{{ $conference['capacity'] }}">
                    </div>
                    <div class="form-group">
                        <label for="open">Enable</label>
                        <select class="form-control" id="open" name="open">
                            <option value="1" {{  ($conference['open'] == 1 ? "selected":"") }}>Yes</option>
                            <option value="0" {{  ($conference['open'] == 0 ? "selected":"") }}>No</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
@endsection