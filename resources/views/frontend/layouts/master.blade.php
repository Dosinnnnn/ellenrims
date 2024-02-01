<!DOCTYPE html>
<html lang="zxx">
<head>
	@include('frontend.layouts.head')	
</head>
<body class="js" style="background-color: #e9e9ee;">
	
	<!-- Preloader -->
	<div class="preloader">
		<div class="preloader-inner">
			<div class="preloader-icon">
				<span></span>
				<span></span>
			</div>
		</div>
	</div>
	<!-- End Preloader -->
	
	@include('frontend.layouts.notification')
	<!-- Header -->
	@include('frontend.layouts.header')
	<!--/ End Header -->
	@yield('main-content')
	
	@include('frontend.layouts.footer')

	
</body>
</html>