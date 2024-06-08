<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Register</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ asset('assets/letter-w.png') }}" rel="icon">
    <link href="{{ asset('assets/letter-w.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/dashboard/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/dashboard/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/dashboard/css/style.css') }}" rel="stylesheet">

</head>

<body>

    <main>
        <div class="container">
            <section
                class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                            <div class="d-flex text-center justify-content-center py-4">
                                <a href="{{ route('home') }}" class="logo  align-items-center w-auto">
                                    <img src="{{ asset('assets/letter-w.png') }}" alt="">
                                </a>
                            </div><!-- End Logo -->
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Buat Akun</h5>
                                        <p class="text-center small">Masukkan data diri Anda untuk membuat akun</p>
                                    </div>
                                    <form class="row g-3" action="{{ route('doRegister') }}" method="POST">
                                        @csrf()
                                        <div class="col-12">
                                            <label for="yourName" class="form-label">Nama Lengkap</label>
                                            <input type="text" name="nama"
                                                class="form-control @error('nama') is-invalid @enderror" id="yourName">
                                            @error('nama')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label for="yourEmail" class="form-label">Alamat Email Anda</label>
                                            <input type="email" name="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                id="yourEmail">
                                            @error('email')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label for="yourPassword" class="form-label">Password</label>
                                            <input type="password" name="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                id="yourPassword">
                                            @error('password')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input @error('terms') is-invalid @enderror"
                                                    name="terms" type="checkbox" value="true" id="acceptTerms">
                                                <label class="form-check-label" for="acceptTerms">Saya setuju dan
                                                    menyetujui syarat dan ketentuan
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button class="btn btn-primary w-100" type="submit">
                                                <i class="bi bi-person me-1"></i>
                                                Buat Akun
                                            </button>
                                        </div>
                                        <div class="col-12">
                                            <p class="small mb-0">Sudah punya akun?
                                                <a href="{{ route('login') }}">Masuk</a>
                                            </p>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main><!-- End #main -->

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/dashboard/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

</body>

</html>
