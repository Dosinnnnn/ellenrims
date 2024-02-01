<!DOCTYPE html>
<html lang="en">

@include('backend.layouts.head')

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    @include('backend.layouts.sidebar')
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        @include('backend.layouts.header')
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        @yield('main-content')
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->
      @include('backend.layouts.footer')
      

     
     <!-- Inisialisasi Bootstrap Select -->
     <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
     <script>
       $(document).ready(function() {
         // Pastikan selectpicker diinisialisasi untuk elemen dengan kelas .selectpicker
         $('.selectpicker').selectpicker();
       });
     </script>
</body>

</html>
