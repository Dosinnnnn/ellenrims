<!-- Start Footer Area -->
<footer class="footer">
    <!-- Footer Top -->
    <div class="footer-top section">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-6 col-12">
                    <!-- Single Widget -->
                    <div class="single-footer about">
                        <div class="logo">
                            <a href="index.html"><img src="{{ asset('backend/img/logobwh1.png') }}" alt="#"></a>
                        </div>
                        @php
                            $settings = DB::table('settings')->get();
                        @endphp
                        <p class="text">
                            @foreach ($settings as $data)
                                {{ $data->short_des }}
                            @endforeach
                        </p>
                        <p class="call">Punya pertanyaan? Hubungi kami 24/7<span><a href="tel:123456789">
                                    @foreach ($settings as $data)
                                        {{ $data->phone }}
                                    @endforeach
                                </a></span></p>
                    </div>
                    <!-- End Single Widget -->
                </div>
                <div class="col-lg-2 col-md-6 col-12">
                    <!-- Single Widget -->
                    <div class="single-footer links">
                        <h4>Information</h4>
                        <ul>
                            <li><a href="{{ route('about-us') }}">Tentang Kami</a></li>
                            <li><a href="{{ route('contact') }}">Hubungi kami</a></li>
                        </ul>
                    </div>
                    <!-- End Single Widget -->
                </div>
                <div class="col-lg-2 col-md-6 col-12">
                    <!-- Single Widget -->
                    <div class="single-footer links">
                        <h4>Customer Service</h4>
                        <ul>
                            <li><a href="#">Cara Pembayaran</a></li>
                            <li><a href="#">Pengiriman</a></li>
                        </ul>
                    </div>
                    <!-- End Single Widget -->
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <!-- Single Widget -->
                    <div class="single-footer social">
                        <h4>Get In Ellenrims</h4>
                        <!-- Single Widget -->
                        <div class="contact">
                            <ul>
                                <li>
                                    @foreach ($settings as $data)
                                        {{ $data->address }}
                                    @endforeach
                                </li>
                                <li>
                                    @foreach ($settings as $data)
                                        {{ $data->email }}
                                    @endforeach
                                </li>
                                <li>
                                    @foreach ($settings as $data)
                                        {{ $data->phone }}
                                    @endforeach
                                </li>
                            </ul>
                        </div>
                        <!-- End Single Widget -->
                    </div>
                    <!-- End Single Widget -->
                </div>
            </div>
        </div>
    </div>
    <!-- End Footer Top -->
    <div class="copyright">
        <div class="container">
            <div class="inner">
                <div class="row">
                    <div class="col-lg-6 col-12">
                        <div class="left">
                            <p>Copyright © {{ date('Y') }} <a href="#" target="_blank">ellenrims</a> - All
                                Rights Reserved.</p>
                        </div>
                    </div>
                    <div class="col-lg-6 col-12">
                        <div class="right">
                            <img src="{{ asset('backend/img/payments.png') }}" alt="#">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- /End Footer Area -->

<!-- Jquery -->
<script src="{{ asset('frontend/js/jquery.min.js') }}"></script>
<script src="{{ asset('frontend/js/jquery-migrate-3.0.0.js') }}"></script>
<script src="{{ asset('frontend/js/jquery-ui.min.js') }}"></script>
<!-- Popper JS -->
<script src="{{ asset('frontend/js/popper.min.js') }}"></script>
<!-- Bootstrap JS -->
<script src="{{ asset('frontend/js/bootstrap.min.js') }}"></script>
<!-- Color JS -->
<script src="{{ asset('frontend/js/colors.js') }}"></script>
<!-- Slicknav JS -->
<script src="{{ asset('frontend/js/slicknav.min.js') }}"></script>
<!-- Owl Carousel JS -->
<script src="{{ asset('frontend/js/owl-carousel.js') }}"></script>
<!-- Magnific Popup JS -->
<script src="{{ asset('frontend/js/magnific-popup.js') }}"></script>
<!-- Waypoints JS -->
<script src="{{ asset('frontend/js/waypoints.min.js') }}"></script>
<!-- Countdown JS -->
<script src="{{ asset('frontend/js/finalcountdown.min.js') }}"></script>
<!-- Nice Select JS -->
<script src="{{ asset('frontend/js/nicesellect.js') }}"></script>
<!-- Flex Slider JS -->
<script src="{{ asset('frontend/js/flex-slider.js') }}"></script>
<!-- ScrollUp JS -->
<script src="{{ asset('frontend/js/scrollup.js') }}"></script>
<!-- Onepage Nav JS -->
<script src="{{ asset('frontend/js/onepage-nav.min.js') }}"></script>
{{-- Isotope --}}
<script src="{{ asset('frontend/js/isotope/isotope.pkgd.min.js') }}"></script>
<!-- Easing JS -->
<script src="{{ asset('frontend/js/easing.js') }}"></script>

<!-- Active JS -->
<script src="{{ asset('frontend/js/active.js') }}"></script>

{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}
{{-- <scr src="https://code.jquery.com/jquery-3.6.4.min.js"></script> --}}
<script>
    $(document).ready(function() {
        function formatRupiah(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount);
        }

        $("#province").change(function() {
            var selectedValue = $("#province").val();
            console.log(selectedValue);
            $("#city").find('option:not(:first)').remove();
            $("#city").append("<option value='' disabled selected>Loading...</option>");


            $.ajax({
                method: 'POST',
                url: '/checkout/getcity/' + selectedValue,
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $("#city option[value='']").remove();
                    for (var i = 0; i < response.city.length; i++) {
                        var option = "<option value='" + response.city[i].city_id + "'>" +
                            response.city[i].type + ' ' + response.city[i].city_name +
                            "</option>";
                        $("#city").append(option);
                    }
                    $('#city').niceSelect('destroy');
                    $('#city').niceSelect();
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        });

        $('#city').niceSelect();

        $("#city").change(function() {
            $("#pengiriman option[value='']").remove();
            $('#pengiriman').niceSelect('destroy');
            $('#pengiriman').niceSelect();
            $("#pengiriman").append("<option selected>Choose a pengiriman</option>");
            $("#pengiriman").append("<option value='jne'>JNE</option>");
            $("#pengiriman").append("<option value='pos'>POS INDONESIA</option>");
            $("#pengiriman").append("<option value='tiki'>TIKI</option>");
            $('#pengiriman').niceSelect('destroy');
            $('#pengiriman').niceSelect();
        });

        $('#pengiriman').niceSelect();

        $("#pengiriman").change(function() {
            var selectedValue = $("#pengiriman").val();
            var selectedValueCity = $("#city").val();
            var berat = $('#totalberat').data('totalberat');

            $("#jenis").find('option:not(:first)').remove();
            $("#jenis").append("<option value='' disabled selected>Loading...</option>");


            $.ajax({
                method: 'POST',
                url: '/checkout/check-ongkir',
                data: {
                    destination: selectedValueCity,
                    weight: berat,
                    pengiriman: selectedValue,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $("#jenis option[value='']").remove();
                    for (var i = 0; i < response.ongkir[0].costs.length; i++) {
                        var jenis = "<option value='" + response.ongkir[0].costs[i]
                            .service + '|' + response.ongkir[0].costs[i].cost[0].value +
                            "'>" + response.ongkir[0].costs[i].service + ' ' + formatRupiah(
                                response.ongkir[0].costs[i].cost[0].value) + ' ' + response
                            .ongkir[0].costs[i].cost[0].etd + ' Day' + "</option>";
                        $("#jenis").append(jenis);
                    }
                    $('#jenis').niceSelect('destroy');
                    $('#jenis').niceSelect();
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        });

        // $("#jenis").change(function() {
        //     var getongkos = $("#jenis").val();
        //     var explodedArray = getongkos.split('|');
        //     $('#ongkoskirim').text(formatRupiah(explodedArray[1]));
        // });
        $("#jenis").change(function() {
            var getongkos = $("#jenis").val();
            var explodedArray = getongkos.split('|');
            var ongkoskirim = parseFloat(explodedArray[1]); // Ambil biaya pengiriman
            $('#ongkoskirim').text(formatRupiah(ongkoskirim)); // Tampilkan biaya pengiriman

            // Ambil subtotal dari data harga subtotal
            var subtotal = parseFloat($("#order_subtotal").data("price"));

            // Ubah format subtotal menjadi menggunakan titik (.) sebagai pemisah ribuan
            var formattedSubtotal = subtotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");

            // Hitung total harga dengan menambahkan biaya pengiriman ke subtotal
            var total_price = subtotal + ongkoskirim;

            // Ubah format total_price menjadi menggunakan titik (.) sebagai pemisah ribuan
            var formattedTotalPrice = total_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");

            // Perbarui nilai total harga yang ditampilkan kepada pengguna dengan format titik (.)
            $("#order_total_price span").text("Rp. " + formattedTotalPrice);
        });
    });
</script>

{{-- <script type="text/javascript">
    // For example trigger on button clicked, or any time you need
    var payButton = document.getElementById('pay-button');
    payButton.addEventListener('click', function() {
        // Trigger snap popup. @TODO: Replace TRANSACTION_TOKEN_HERE with your transaction token.
        // Also, use the embedId that you defined in the div above, here.
        window.snap.embed('YOUR_SNAP_TOKEN', {
            embedId: 'snap-container',
            onSuccess: function(result) {
                /* You may add your own implementation here */
                alert("payment success!");
                console.log(result);
            },
            onPending: function(result) {
                /* You may add your own implementation here */
                alert("wating your payment!");
                console.log(result);
            },
            onError: function(result) {
                /* You may add your own implementation here */
                alert("payment failed!");
                console.log(result);
            },
            onClose: function() {
                /* You may add your own implementation here */
                alert('you closed the popup without finishing the payment');
            }
        });
    });
</script> --}}

<script>
    // Contoh penanganan callback setelah menerima respons dari Midtrans
    function handleMidtransCallback(response) {
        if (response.status_code === '200' && response.transaction_status === 'settlement') {
            // Redirect pengguna ke halaman order track setelah transaksi berhasil
            window.location.href = '/order/track';
        } else {
            // Lakukan tindakan lain jika diperlukan
        }
    }
</script>
