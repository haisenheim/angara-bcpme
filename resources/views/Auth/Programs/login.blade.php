<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-scheme="night">

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1">
    <meta name="description" content="The login page allows a user to gain access to an application by entering their username and password or by authenticating using a social media login.">
    <title>Login | Angara</title>
    <link rel="icon" type="image/svg" sizes="32x32" href="{{ asset('img/favicon.ico') }}">

    <!-- STYLESHEETS -->
    <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~--- -->

    <!-- Fonts [ OPTIONAL ] -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&family=Ubuntu:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS [ REQUIRED ] -->
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">

    <!-- Nifty CSS [ REQUIRED ] -->
    <link rel="stylesheet" href="{{ asset('assets/css/nifty.min.css') }}">

    <!-- Nifty Demo Icons [ OPTIONAL ] -->
    <link rel="stylesheet" href="{{ asset('assets/css/demo-purpose/demo-icons.min.css') }}">
</head>

<body class="">

    <!-- PAGE CONTAINER -->
    <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
    <div id="root" class="root front-container">
        <div style="padding: 0; height:100vh;" class="d-flex flex-wrap justify-content-between">
            <div class="flex-fill" style="background-image: url({{ asset('img/cover.jpg') }});height:100%; background-size:cover">
            </div>
            <div id="login" class="w-300px">
                <div style="" class="card shadow-lg w-100 h-100">
                    <div style="display:flex; flex-direction:column; justify-content:center; height:100%;" class="card-body">
                        <div class="text-center">
                            <div>
                                <img src="{{asset('img/logo.png')}}" class="rounded-circle" style="width: 100px; height: 100px; margin-bottom: 20px;" alt="">
                            </div>
                            <h5 class="text-dark text-center mb-2"><span class="fw-500 text-blue">Programme :  </span> {{ $program->name }}</h5>
                            <h1 class="h3">Connexion</h1>
                            <p class="lh-base">Connectez vous a votre compte</p>
                        </div>
                        <form method="POST" action="{{route('login')}}">
                            @csrf
                            <div class="mb-3">
                                <input type="email" name="email" class="form-control" v-model="user.email" placeholder="Email" autofocus>
                            </div>
                            <div class="mb-3">
                                <input type="password" name="password" v-model="user.password" class="form-control" placeholder="Password">
                            </div>
                            <div class="d-grid mt-5">
                                <button class="btn btn-primary btn-lg text-yellow-darken-2" type="submit">Se connecter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
    <!-- END - PAGE CONTAINER -->


    <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
    <!-- END - BOXED LAYOUT : BACKGROUND IMAGES CONTENT [ DEMO ] -->
    <!-- JAVASCRIPTS -->
    <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->

    <!-- Popper JS [ OPTIONAL ] -->
    <script src="./assets/vendors/popperjs/popper.min.js" defer></script>

    <!-- Bootstrap JS [ OPTIONAL ] -->
    <script src="./assets/vendors/bootstrap/bootstrap.min.js" defer></script>

    <!-- Nifty JS [ OPTIONAL ] -->
    <script src="./assets/js/nifty.js" defer></script>

    <!-- Nifty Settings [ DEMO ] -->
    <script src="./assets/js/demo-purpose-only.js" defer></script>

</body>

</html>
