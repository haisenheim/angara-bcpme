<!DOCTYPE html>
<html lang="fr">

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>
			@yield('title') | ANGARA FINANCE
		</title>
		<link href="{{ asset('img/favicon.ico') }}" rel="icon">
		<link href="{{ asset('img/apple-icon.png') }}" rel="apple-icon">
		<link href="https://fonts.cdnfonts.com/css/roboto" rel="stylesheet">
		<link href="https://fonts.cdnfonts.com/css/open-sans" rel="stylesheet">
		<!-- Bootstrap CSS [ REQUIRED ] -->
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
        <!-- Angara Custom Style -->
        <link rel="stylesheet" href="{{ asset('css/angara-style.css') }}">
		<link href="{{ asset('css/style.css') }}" rel="stylesheet">
		<link rel="stylesheet" href="{{ asset('css/nice-select2.css') }}">

	</head>

	<body>
		<div id="menu-overlay"></div>
			<div class="main-container"> 
                @php
                    $table = [
                        [
                            'src' => "img/new/slides/slide-1.jpg",
                            'alt' => "Angara agriculture",
                            'text' => "Le système expert en finances pour fédérer tous les secteurs d’activités"
                        ],
                        [
                            'src' => "img/new/slides/slide-2.jpg",
                            'alt' => "Angara Elevage",
                            'text' => "Recevez en temps réel les informations financières de votre secteur d’activité."
                        ],
                        [
                            'src' => "img/new/slides/slide-3.jpg",
                            'alt' => "Angara agriculture",
                            'text' => "Mettez à niveau votre entreprise au niveau structurel et financier"
            ],
            [
                            'src' => "img/new/slides/slide-4.jpg",
                            'alt' => "Angara agriculture",
                            'text' => "Optimisez votre entreprise pour vos besoins d'investissement"
                        ]
                    ];
                @endphp
				<form method="post" class="form" action="{{route('login')}}">
                    @csrf
                    <div class="login-container">
                        <aside>
                            <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                                <ol class="carousel-indicators">
                                    @for ($i = 0; $i < count($table); $i++)
                                        <li data-target="#carouselExampleIndicators" data-slide-to="{{ $i }}" class="{{ $i === 0 ? 'active' : '' }} indicator-item"></li>
                                    @endfor
                                </ol>
                                <div class="carousel-inner h-100">
                                    @for ($i = 0; $i < count($table); $i++)
                                        <div class="carousel-item h-100 {{ $i === 0 ? 'active' : '' }}">
                                            <div class="d-block w-100 h-100 mask" style="background-image: url({{ $table[$i]['src'] }});">
                                                <div class="d-flex flex-column justify-content-end carousel-text h-100 w-30">
                                                    <h3 class="color-primary bold">ANGARA</h3>
                                                    <h4 class="color-ligth bold">{{ $table[$i]['text'] }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </aside>
                        <div class="login-content d-flex flex-column align-items-center justify-content-center">
                            <img src="{{asset('img/logo.png')}}" alt="angara" class="margin-bottom">
                            <h3 class="bold margin-bottom">Connectez vous</h3>
                            <input type="email" name="email" class="margin-bottom" v-model="user.email" placeholder="Email" autofocus>
                            {{-- @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif --}}
                            <input type="password" name="password" v-model="user.password" class="margin-bottom" placeholder="Password">
                            {{-- @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                            @endif --}}
                            
                            <button class="btn btn-dark margin-bottom">Connexion</button>
                            {{-- @if ($errors->has('password') || $errors->has('email'))
                                @include('includes.flash-message')
                            @endif --}}
                        </div>
                    </div>
                </form>
			</div>
		</body>
		<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
		<script src="/js/app.js"></script>

	</html>

