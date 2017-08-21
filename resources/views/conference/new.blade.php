@extends('layouts.partials')

@section('content')
    <div class="container">
        <div class="row">
            <h1>New Conference</h1>
        </div>
        <div class="row">
            @include('layouts.error')
        </div>
        <div class="row">
            <div class="col-lg-12">
                <form action="{{ URL('/conference/new') }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="owner" value="{{ Auth::user()->id }}">
                    <div class="form-group">
                        <label for="InputName">Name</label>
                        <input type="text" class="form-control" id="InputName" name="name" placeholder="Conference Name" value="{{ old('name') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="capacity">Capacity</label>
                        <input type="text" class="form-control" id="capacity" name="capacity" placeholder="Capacity" value="{{ old('capacity') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="open">Enable</label>
                        <select class="form-control" id="open" name="open">
                            <option value="1" {{  (old('open') == 1 ? "selected":"") }}>Yes</option>
                            <option value="0" {{  (old('open') == 0 ? "selected":"") }}>No</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Create</button>
                </form>
            </div>
        </div>
    </div>
@endsection