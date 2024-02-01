<?php
$ukuranBanOptions = [];
?>
@extends('backend.layouts.master')

@section('main-content')

<div class="card">
    <h5 class="card-header">Edit Produk</h5>
    <div class="card-body">
      <form method="post" action="{{ route('product.update', $product->id) }}">
        @csrf 
        @method('PATCH')
        <div class="form-group">
          <label for="inputTitle" class="col-form-label">Judul <span class="text-danger">*</span></label>
          <input id="inputTitle" type="text" name="title" placeholder="Masukan Judul"  value="{{ $product->title }}" class="form-control">
          @error('title')
          <span class="text-danger">{{ $message }}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="summary" class="col-form-label">Ringkasan <span class="text-danger">*</span></label>
          <textarea class="form-control" id="summary" name="summary">{{ $product->summary }}</textarea>
          @error('summary')
          <span class="text-danger">{{ $message }}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="description" class="col-form-label">Deskripsi</label>
          <textarea class="form-control" id="description" name="description">{{ $product->description }}</textarea>
          @error('description')
          <span class="text-danger">{{ $message }}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="is_featured">Unggulan</label><br>
          <input type="checkbox" name='is_featured' id='is_featured' value='1' {{ ($product->is_featured ? 'checked' : '') }}> Yes                        
        </div>

        <div class="form-group">
          <label for="ukuran_ban">Ukuran Ban</label>
          <select name="ukuran_ban[]" class="form-control selectpicker" multiple data-live-search="true" data-none-selected-text="Pilih Ukuran Ban">
              @foreach($ukuranBan as $ukuran)
                  <option value="{{ $ukuran->id }}" {{ in_array($ukuran->id, $selectedUkuranBan) ? 'selected' : '' }}>
                      {{ $ukuran->ukuran }}
                  </option>
              @endforeach
          </select>
          @error('ukuran_ban')
              <span class="text-danger">{{ $message }}</span>
          @enderror
      </div>

      <div class="form-group">
        <label for="berat" class="col-form-label">Berat</label>
        <input id="berat" type="number" name="berat" placeholder="Enter berat" value="{{ number_format($product->weight, 0, ',', '.') }}" class="form-control">
        @error('berat')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    

        <div class="form-group">
          <label for="cat_id">Kategori <span class="text-danger">*</span></label>
          <select name="cat_id" id="cat_id" class="form-control">
              <option value="">--Pilih Kategori--</option>
              @foreach($categories as $key => $cat_data)
                  <option value='{{ $cat_data->id }}' {{ ($product->cat_id == $cat_data->id ? 'selected' : '') }}>{{ $cat_data->title }}</option>
              @endforeach
          </select>
        </div>

        <div class="form-group {{ ($product->child_cat_id ? '' : 'd-none') }}" id="child_cat_div">
          <label for="child_cat_id">Sub Kategori</label>
          <select name="child_cat_id" id="child_cat_id" class="form-control">
              <option value="">--SPilih sub Kategori--</option>
          </select>
        </div>

        <div class="form-group">
          <label for="price" class="col-form-label">Harga <span class="text-danger">*</span></label>
          <input id="price" type="number" name="price" placeholder="Enter price" value="{{ number_format($product->price, 0, ',', '.') }}" class="form-control">
          @error('price')
              <span class="text-danger">{{ $message }}</span>
          @enderror
      </div>

        <div class="form-group">
          <label for="discount" class="col-form-label">Diskon(%)</label>
          <input id="discount" type="number" name="discount" min="0" max="100" placeholder="Enter discount"  value="{{ $product->discount }}" class="form-control">
          @error('discount')
          <span class="text-danger">{{ $message }}</span>
          @enderror
        </div>
        
        <div class="form-group">
          <label for="brand_id">Brand</label>
          <select name="brand_id" class="form-control">
              <option value="">--Select Brand--</option>
             @foreach($brands as $brand)
              <option value="{{ $brand->id }}" {{ ($product->brand_id == $brand->id ? 'selected' : '') }}>{{ $brand->title }}</option>
             @endforeach
          </select>
        </div>

        <div class="form-group">
          <label for="condition">Kondisi</label>
          <select name="condition" class="form-control">
              <option value="">--Pilih Kodisi--</option>
              <option value="default" {{ ($product->condition == 'default' ? 'selected' : '') }}>Default</option>
              <option value="new" {{ ($product->condition == 'new' ? 'selected' : '') }}>New</option>
              <option value="hot" {{ ($product->condition == 'hot' ? 'selected' : '') }}>Hot</option>
          </select>
        </div>

        <div class="form-group">
          <label for="stock">Jumlah <span class="text-danger">*</span></label>
          <input id="quantity" type="number" name="stock" min="0" placeholder="Enter quantity"  value="{{ $product->stock }}" class="form-control">
          @error('stock')
          <span class="text-danger">{{ $message }}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="inputPhoto" class="col-form-label">Foto <span class="text-danger">*</span></label>
          <div class="input-group">
              <span class="input-group-btn">
                  <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary text-white">
                  <i class="fas fa-image"></i> Pilih
                  </a>
              </span>
          <input id="thumbnail" class="form-control" type="text" name="photo" value="{{ $product->photo }}">
        </div>
        <div id="holder" style="margin-top:15px;max-height:100px;"></div>
          @error('photo')
          <span class="text-danger">{{ $message }}</span>
          @enderror
        </div>
        
        <div class="form-group">
          <label for="status" class="col-form-label">Status <span class="text-danger">*</span></label>
          <select name="status" class="form-control">
            <option value="aktif" {{ ($product->status == 'aktif' ? 'selected' : '') }}>Aktif</option>
            <option value="tidak aktif" {{ ($product->status == 'tidak aktif' ? 'selected' : '') }}>Tidak Aktif</option>
          </select>
          @error('status')
          <span class="text-danger">{{ $message }}</span>
          @enderror
        </div>

        <div class="form-group mb-3">
           <button class="btn btn-success" type="submit">Update</button>
        </div>
      </form>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('backend/summernote/summernote.min.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
@endpush

@push('scripts')
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script src="{{ asset('backend/summernote/summernote.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script>
    $('#lfm').filemanager('image');

    $(document).ready(function() {
    $('#summary').summernote({
      placeholder: "Tulis deskripsi singkat.....",
        tabsize: 2,
        height: 150
    });
    });
    $(document).ready(function() {
      $('#description').summernote({
        placeholder: "Tulis Deskripsi detail.....",
          tabsize: 2,
          height: 150
      });
    });
</script>

<script>
  var child_cat_id = '{{ $product->child_cat_id }}';
  $('#cat_id').change(function(){
      var cat_id = $(this).val();

      if(cat_id != null){
          // ajax call
          $.ajax({
              url:"/admin/category/"+cat_id+"/child",
              type:"POST",
              data:{
                  _token: "{{ csrf_token() }}"
              },
              success:function(response){
                  if(typeof(response) != 'object'){
                      response = $.parseJSON(response);
                  }
                  var html_option = "<option value=''>--Select any one--</option>";
                  if(response.status){
                      var data = response.data;
                      if(response.data){
                          $('#child_cat_div').removeClass('d-none');
                          $.each(data,function(id, title){
                              html_option += "<option value='"+id+"' "+(child_cat_id == id ? 'selected ' : '')+">"+title+"</option>";
                          });
                      }
                      else{
                          console.log('tidak ada data respons');
                      }
                  }
                  else{
                      $('#child_cat_div').addClass('d-none');
                  }
                  $('#child_cat_id').html(html_option);

              }
          });
      }
      else{

      }

  });
  if(child_cat_id != null){
      $('#cat_id').change();
  }
</script>
@endpush
