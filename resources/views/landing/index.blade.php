@extends('landing.layouts.main')

@section('css')
@endsection
@section('content')
    @if (request()->has('kode_unik') && request('kode_unik') != '')
        <!-- ======= Hasil Pencarian Section ======= -->
        <section id="contact" class="contact">
            <div class="container">
                <div class="section-title">
                    <h2 id="title">Hasil Pencarian</h2>
                </div>
                <div class="row justify-content-center">
                    @if ($perbaikan !== null)
                        <div class="col-lg-4 d-flex align-items-stretch">
                            <div class="info">
                                <div class="">
                                    <i class="bi bi-geo-alt"></i>
                                    <h4>Kode</h4>
                                    <p>{{ $perbaikan->kode_unik }}</p>
                                </div>
                                <div class="">
                                    <i class="bi bi-car-front"></i>
                                    <h4>Nama Perbaikan</h4>
                                    <p>{{ $perbaikan->nama }}</p>
                                </div>
                                <div class="">
                                    <i class="bi bi-calendar-date"></i>
                                    <h4>Tanggal Masuk</h4>
                                    <p>{{ $perbaikan->created_at->format('d-m-Y') }}</p>
                                </div>
                                <div class="float-end phone">
                                    <a class="text-white" target="_blank"
                                        href="{{ route('home.detail-perbaikan', $perbaikan->id) }}">
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-lg-5">
                            <p class="text-center">Tidak ditemukan !</p>
                        </div>
                    @endif
                </div>
            </div>
        </section><!-- End Hasil Pencarian Section -->
    @else
        <!-- ======= About Us Section ======= -->
        <section id="about" class="about">
            <div class="container">

                <div class="section-title">
                    <h2 id="title">Tentang Kami</h2>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        {!! $settings->deskripsi !!}
                    </div>
                </div>

                <div class="row mt-5 justify-content-center">
                    <div class="col-lg-4 col-md-6 icon-box">
                        <div class="icon"><i class="bi bi-briefcase"></i></div>
                        <h4 class="title"><a href="#">Jam Operasional</a></h4>
                        <h5>{{ $settings->jam_operasional }}</h5>
                    </div>
                </div>
            </div>
        </section><!-- End About Us Section -->

        <!-- ======= Gallery Section ======= -->
        <section>
            <div class="section-title">
                <h2 id="title">Our Works</h2>
            </div>
            <div class="owl-carousel">
                @foreach ($galleries as $gallery)
                    <div>
                        <img src="{{ asset('storage/' . $gallery->foto) }}" class="rounded" alt="">
                    </div>
                @endforeach
            </div>
        </section>
        <!-- End Gallery Section -->

        <!-- ======= Contact Us Section ======= -->
        <section id="contact" class="contact">
            <div class="container">
                <div class="section-title">
                    <h2 id="title">Contact Us</h2>
                </div>
                <div class="row">
                    <div class="col-lg-5 d-flex align-items-stretch">
                        <div class="info">
                            <div class="address">
                                <i class="bi bi-geo-alt"></i>
                                <h4>Location:</h4>
                                <p>{{ $settings->alamat }}</p>
                            </div>
                            <div class="email">
                                <i class="bi bi-envelope"></i>
                                <h4>Email:</h4>
                                <p>{{ $settings->email }}</p>
                            </div>
                            <div class="phone">
                                <i class="bi bi-phone"></i>
                                <h4>Call:</h4>
                                <p>{{ $settings->telepon }}</p>
                            </div>
                            {!! $settings->map_google !!}
                        </div>
                    </div>
                    <div class="col-lg-7 mt-5 mt-lg-0 d-flex align-items-stretch">
                        <form action="" method="post" role="form" class="contact-form">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="name">Your Name</label>
                                    <input type="text" name="name" class="form-control rounded" id="name"
                                        required>
                                </div>
                                <div class="form-group col-md-6 mt-3 mt-md-0">
                                    <label for="name">Your Email</label>
                                    <input type="email" class="form-control rounded" name="email" id="email"
                                        required>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <label for="name">Message</label>
                                <textarea class="form-control rounded" name="message" rows="10" required></textarea>
                            </div>
                            <div class="my-3">
                                <div class="loading">Loading</div>
                                <div class="error-message"></div>
                                <div class="sent-message">Your message has been sent. Thank you!</div>
                            </div>
                            <div class="text-center"><button type="submit">Send Message</button></div>
                        </form>
                    </div>
                </div>
            </div>
        </section><!-- End Contact Us Section -->
    @endif
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('.owl-carousel').owlCarousel({
                center: true,
                loop: true,
                margin: 20,
                autoplay: true,
                autoplayTimeout: 5000,
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 1,
                    },
                    600: {
                        items: 3,
                    },
                    1000: {
                        items: 3,
                    }
                }
            })
        });
    </script>
@endsection
