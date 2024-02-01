@extends('backend.layouts.master')

@section('title','E-SHOP || Ukuran Produk Page')

@section('main-content')
<div class="card shadow mb-4">
    <div class="row">
        <div class="col-md-12">
            @include('backend.layouts.notification')
        </div>
    </div>
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary float-left">Daftar Ukuran Produk</h6>
        <a href="{{ route('ukuran.create') }}" class="btn btn-primary btn-sm float-right" data-toggle="tooltip" data-placement="bottom" title="Tambah Ukuran">
            <i class="fas fa-plus"></i> Tambah Ukuran
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            @if(count($ukuranBan) > 0)
            <table class="table table-bordered" id="ukuranBan-dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ukuran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ukuranBan as $ukuran)
                    <tr>
                        <td>{{ $ukuran->id }}</td>
                        <td>{{ $ukuran->ukuran }}</td>
                        <td>
                            <a href="{{ route('ukuran.edit', $ukuran->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('ukuran.destroy', $ukuran->id) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm dltBtn" data-id="{{ $ukuran->id }}" data-toggle="tooltip" data-placement="bottom" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <h6 class="text-center">Tidak ada ukuran produk.</h6>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
<style>
    div.dataTables_wrapper div.dataTables_paginate {
        display: none;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('backend/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script>
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#ukuranBan-dataTable').DataTable({
            "columnDefs": [
                {
                    "orderable": false,
                    "targets": [2]
                }
            ]
        });

        $('.dltBtn').click(function(e){
            var form = $(this).closest('form');
            var dataID = $(this).data('id');

            e.preventDefault();

            swal({
                title:"Apa kamu yakin?",
                text: "Setelah dihapus, Anda tidak akan dapat memulihkan data ini!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    form.submit();
                } else {
                    swal("Data Anda aman!");
                }
            });
        });
    });
</script>
@endpush
