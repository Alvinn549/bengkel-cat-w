@extends('landing.layouts.main')

@section('css')
@endsection
@section('content')
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
            <div>
                <img src="{{ asset('storage/' . $settings->hero) }}" class="rounded" alt="">
            </div>
            <div>
                <img src="{{ asset('storage/' . $settings->hero) }}" class="rounded" alt="">
            </div>
            <div>
                <img src="{{ asset('storage/' . $settings->hero) }}" class="rounded" alt="">
            </div>
            <div>
                <img src="{{ asset('storage/' . $settings->hero) }}" class="rounded" alt="">
            </div>
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
                        <iframe class="rounded"
                            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d12097.433213460943!2d-74.0062269!3d40.7101282!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xb89d1fe6bc499443!2sDowntown+Conference+Center!5e0!3m2!1smk!2sbg!4v1539943755621"
                            frameborder="0" style="border:0; width: 100%; height: 290px;" allowfullscreen></iframe>
                    </div>
                </div>
                <div class="col-lg-7 mt-5 mt-lg-0 d-flex align-items-stretch">
                    <form action="" method="post" role="form" class="contact-form">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="name">Your Name</label>
                                <input type="text" name="name" class="form-control rounded" id="name" required>
                            </div>
                            <div class="form-group col-md-6 mt-3 mt-md-0">
                                <label for="name">Your Email</label>
                                <input type="email" class="form-control rounded" name="email" id="email" required>
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
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('.owl-carousel').owlCarousel({
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
