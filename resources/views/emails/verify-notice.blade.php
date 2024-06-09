<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Email Verification</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ asset('assets/letter-w.png') }}" rel="icon">
    <link href="{{ asset('assets/letter-w.png') }}" rel="apple-touch-icon">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/dashboard/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

</head>

<body>
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
            <path
                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
        </symbol>
        <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
            <path
                d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
        </symbol>
        <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
            <path
                d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
        </symbol>
    </svg>

    <!-- Modal -->
    <div class="modal fade" id="changeEmailModal" tabindex="-1" aria-labelledby="changeEmailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="changeEmailModalLabel">Change Email</h5>
                </div>
                <div class="modal-body">
                    <form action="{{ route('verification.change-email') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Email</label>
                            <input required type="email" name="email" class="form-control "
                                id="exampleFormControlInput1" placeholder="{{ auth()->user()->email }}">
                        </div>
                        <div class="mb-3 d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5" style="margin-top: 100px;">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @if (session('success'))
                    <div class="alert alert-success d-flex align-items-center alert-dismissible fade show"
                        role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img"
                            aria-label="Success:">
                            <use xlink:href="#check-circle-fill" />
                        </svg>
                        <div>
                            {{ session('success') }}
                        </div>
                        <div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    </div>
                @endif

                @error('email')
                    <div class="alert alert-danger d-flex align-items-center alert-dismissible fade show" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img"
                            aria-label="Danger:">
                            <use xlink:href="#check-circle-fill" />
                        </svg>
                        <div>
                            {{ $message }}
                        </div>
                        <div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @enderror
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-md-4">
                                <img src="{{ asset('assets/landing/img/verification.png') }}" class="img-fluid"
                                    alt="Verification Image">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12 text-center">
                                <p>Before proceeding, please check your email for a verification link. If you did not
                                    receive
                                    the email,
                                    click the button below to request another.</p>
                                <p> {{ auth()->user()->email }}</p>
                                <div class="d-flex justify-content-center">
                                    <form method="POST" action="{{ route('verification.send') }}">
                                        @csrf
                                        <button class="btn btn-primary " type="submit">Request Another</button>
                                    </form>
                                    <button type="button" class="btn btn-secondary ms-2" data-bs-toggle="modal"
                                        data-bs-target="#changeEmailModal">
                                        Change Email
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/dashboard/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

</body>

</html>
