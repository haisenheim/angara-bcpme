<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil | {{ config('app.name', 'ANGARA') }}</title>
    <link href="{{ asset('img/favicon.ico') }}" rel="icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/angara-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nifty-override.css') }}">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <style>
        .accueil-wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
        }
        .accueil-card {
            max-width: 28rem;
            width: 100%;
            text-align: center;
            padding: 2.5rem 2rem;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        }
        .accueil-card img {
            max-width: 220px;
            height: auto;
            margin-bottom: 1.5rem;
        }
        .accueil-card h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
        }
        .accueil-card p {
            color: #5c6370;
            margin-bottom: 1.75rem;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="accueil-wrap">
        <div class="accueil-card">
            <img src="{{ asset('img/logo-bcpme.png') }}" alt="{{ config('app.name') }}">
            <h1 class="color-primary bold">Bienvenue</h1>
            <p>Accédez à votre espace en vous connectant.</p>
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Connexion</a>
        </div>
    </div>
</body>
</html>
