@extends('superadm.layout.master')

@section('title', 'Add Marquee')

@section('content')
    <div class="row">
        <div class="col-lg-6 col-md-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    <h4>Add Marque</h4>
                    <form action="{{ route('marquee.save') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Message <span class="text-danger">*</span></label>
                            <input type="text" name="message" class="form-control @error('message') is-invalid @enderror" value="{{ old('message') }}">
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="is_active" class="form-control">
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <div class="form-group d-flex justify-content-end">
                            <a href="{{ route('marquee.list') }}" class="btn btn-secondary mr-2">Cancel</a>
                            <button type="submit" class="btn btn-sm btn-outline-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
