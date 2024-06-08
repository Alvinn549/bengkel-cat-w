<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Login</title>
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
    <link href="{{ asset('assets/landing/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/dashboard/css/style.css') }}" rel="stylesheet">

    <style>
        .input-group.has-validation .form-control.is-invalid {
            border-color: #dc3545;
            /* Bootstrap's red color for invalid state */
        }

        .input-group.has-validation .btn-outline-secondary {
            border: 1px solid #ced4da;
            /* Default border color */
            border-left: none;
            /* Remove left border to merge with input */
        }

        .input-group.has-validation .btn-outline-secondary.is-invalid {
            border-color: #dc3545;
            /* Bootstrap's red color for invalid state */
        }

        .input-group .btn-outline-secondary {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }
    </style>
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
                                <a href="{{ route('home') }}" class="logo align-items-center w-auto">
                                    <img src="{{ asset('assets/letter-w.png') }}" alt="">
                                </a>
                            </div><!-- End Logo -->
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="pt-4 pb-2">
                                        @if (session('error'))
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                {{ session('error') }}
                                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="Close"></button>
                                            </div>
                                        @endif
                                        <h5 class="card-title text-center pb-0 fs-4">Masuk ke Akun Anda</h5>
                                        <p class="text-center small">Masukkan email dan kata sandi Anda untuk masuk</p>
                                    </div>
                                    <form method="POST" action="{{ route('do-login') }}" class="row g-3" novalidate>
                                        @csrf
                                        <div class="col-12">
                                            <label for="yourEmail" class="form-label">Email</label>
                                            <div class="input-group has-validation">
                                                <input type="email" name="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    id="yourEmail" value="{{ old('email') ?? session('oldEmail') }}">
                                                @error('email')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label for="yourPassword" class="form-label">Password</label>
                                            <div class="input-group has-validation">
                                                <input type="password" name="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    id="yourPassword">
                                                <button
                                                    class="btn btn-outline-secondary @error('password') is-invalid @enderror"
                                                    type="button" id="togglePassword">
                                                    <i class="bi bi-eye-slash" id="togglePasswordIcon"></i>
                                                </button>
                                                @error('password')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember"
                                                    value="true" id="rememberMe">
                                                <label class="form-check-label" for="rememberMe">Ingat saya</label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button class="btn btn-primary w-100" type="submit"><i
                                                    class="bi bi-box-arrow-in-right me-1"></i> Login
                                            </button>
                                        </div>
                                        <div class="col-12">
                                            <p class="small mb-0">Tidak punya akun?
                                                <a href="{{ route('register') }}">Buat akun</a>
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

    <script>
        document.getElementById('togglePassword').addEventListener('click', function(e) {
            const password = document.getElementById('yourPassword');
            const icon = document.getElementById('togglePasswordIcon');

            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');
        });
    </script>

</body>

</html>
