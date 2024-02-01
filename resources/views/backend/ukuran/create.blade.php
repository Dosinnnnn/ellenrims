@extends('backend.layouts.master')
@section('title', 'Tambah Ukuran Barang')

@section('main-content')
    <div class="card">
        <h5 class="card-header">Tambah Ukuran Barang</h5>
        <div class="card-body">
            <form method="post" action="{{ route('ukuran.store') }}">
                @csrf
                <div class="form-group">
                    <label for="inputUkuran" class="col-form-label">Ukuran <span class="text-danger">*</span></label>
                    <input id="inputUkuran" type="text" name="ukuran" placeholder="Masukan Ukuran" value="{{ old('ukuran') }}" class="form-control">
                    @error('ukuran')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <button type="reset" class="btn btn-warning">Atur Ulang</button>
                    <button class="btn btn-success" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection