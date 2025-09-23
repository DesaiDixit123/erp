<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Industry</title>

    <link rel="icon" href="{{ asset('img/core-img/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>

<body class="login-area">

    <!-- Preloader -->
    <div id="preloader">
        <div class="scene">
            <div class="cube-wrapper">
                <div class="cube">
                    <div class="cube-faces">
                        <div class="cube-face shadow"></div>
                        <div class="cube-face bottom"></div>
                        <div class="cube-face top"></div>
                        <div class="cube-face left"></div>
                        <div class="cube-face right"></div>
                        <div class="cube-face back"></div>
                        <div class="cube-face front"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Preloader -->

    <!-- Page Wrapper Area Start -->
    <div class="main-content- h-100vh">
        <div class="container h-100">
            <div class="row h-100 align-items-center justify-content-center">
                <div class="col-sm-10 col-md-7 col-lg-5">
                    <!-- Middle Box -->
                    <div class="middle-box">
                        <div class="card">
                            <div class="card-body p-4">
                                <div class="log-header-area mb-5">
                                    <h4>Welcome Back!</h4>
                                    <p class="mb-0">Sign in to continue.</p>
                                </div>

                                <!-- Show Session Error -->
                                @if (session('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                <!-- Show Validation Errors -->
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <!-- Login Form -->
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="form-group mb-3">
                                        <label class="text-muted" for="emailaddress">Email address</label>
                                        <input class="form-control" type="email" id="emailaddress" name="email" placeholder="Enter your email" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="text-muted" for="password">Password</label>
                                        <input class="form-control" type="password" id="password" name="password" placeholder="Enter your password" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <button class="btn btn-primary btn-lg w-100" type="submit">Sign In</button>
                                    </div>
                                </form>
                                <!-- End Login Form -->

                            </div>
                        </div>
                    </div>
                    <!-- End Middle Box -->
                </div>
            </div>
        </div>
    </div>
    <!-- Page Wrapper Area End -->

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/default-assets/setting.js') }}"></script>
    <script src="{{ asset('js/default-assets/scrool-bar.js') }}"></script>
    <script src="{{ asset('js/todo-list.js') }}"></script>
    <script src="{{ asset('js/default-assets/active.js') }}"></script>

</body>
</html>
