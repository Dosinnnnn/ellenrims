@extends('backend.layouts.master')

@section('main-content')

<div class="card">
    <h5 class="card-header">Edit Ukuran Produk</h5>
    <div class="card-body">
        <form method="post" action="{{ route('ukuran.update', $ukuranBan->id) }}">
            {{ csrf_field() }}
            {{ method_field('PUT') }}

            <div class="form-group">
                <label for="inputSize" class="col-form-label">Ukuran <span class="text-danger">*</span></label>
                <input id="inputSize" type="text" name="ukuran" placeholder="Masukkan ukuran" value="{{ $ukuranBan->ukuran }}" class="form-control">
                @error('ukuran')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <button type="reset" class="btn btn-warning">Atur Ulang</button>
                <button class="btn btn-success" type="submit">Update</button>
            </div>
        </form>
    </div>
</div>

@endsection
