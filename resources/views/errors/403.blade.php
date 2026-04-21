@php
    /** @var \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface|null $exception */
    $exception = $exception ?? null;
    $detail = '';
    if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface) {
        $detail = trim((string) $exception->getMessage());
    }
    $generic = ['Forbidden', 'This action is unauthorized.', ''];
    $showDetail = $detail !== '' && ! in_array($detail, $generic, true) && mb_strlen($detail) < 400;
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Accès refusé — {{ config('app.name', 'ANGARA') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bcpme-green: #88b824;
            --amber: #e8a317;
            --amber-soft: rgba(232, 163, 23, .14);
            --bg: #0f1419;
            --surface: #1a222d;
            --border: rgba(255,255,255,.08);
            --text: #e8eaed;
            --muted: #9aa0a6;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Inter', system-ui, sans-serif;
            background: radial-gradient(ellipse 120% 80% at 50% -20%, rgba(232, 163, 23, .12), transparent 50%), var(--bg);
            color: var(--text);
            line-height: 1.55;
        }
        .wrap {
            max-width: 640px;
            margin: 0 auto;
            padding: 2.5rem 1.25rem 3rem;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin-bottom: 2rem;
        }
        .brand-mark {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--bcpme-green), #5a7a18);
            display: grid;
            place-items: center;
            font-weight: 700;
            font-size: .85rem;
            color: #fff;
            letter-spacing: -.02em;
        }
        .brand h1 {
            margin: 0;
            font-size: 1.125rem;
            font-weight: 600;
        }
        .brand span { color: var(--muted); font-size: .8125rem; }
        .hero {
            border: 1px solid var(--border);
            border-radius: 16px;
            background: var(--surface);
            padding: 1.75rem 1.5rem;
            margin-bottom: 1.25rem;
        }
        .status-row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: .75rem 1rem;
            margin-bottom: 1rem;
        }
        .code-badge {
            font-size: 2.25rem;
            font-weight: 700;
            color: var(--amber);
            line-height: 1;
        }
        .pill {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .25rem .65rem;
            border-radius: 999px;
            font-size: .75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .04em;
            background: var(--amber-soft);
            color: #f5d08a;
            border: 1px solid rgba(232, 163, 23, .4);
        }
        .title {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0 0 .5rem;
            color: var(--text);
        }
        .lead {
            font-size: 1rem;
            color: var(--muted);
            margin: 0 0 1.25rem;
        }
        .reassure {
            font-size: .9375rem;
            color: var(--text);
            margin: 0 0 1rem;
            padding: .9rem 1rem;
            border-radius: 10px;
            background: rgba(136, 184, 36, .08);
            border: 1px solid rgba(136, 184, 36, .22);
        }
        .list {
            margin: 0;
            padding-left: 1.15rem;
            color: var(--muted);
            font-size: .9375rem;
        }
        .list li { margin-bottom: .5rem; }
        .list li:last-child { margin-bottom: 0; }
        .detail {
            margin-top: 1rem;
            padding: .75rem 1rem;
            border-radius: 10px;
            background: rgba(0,0,0,.2);
            border: 1px solid var(--border);
            font-size: .875rem;
            color: var(--muted);
        }
        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: .75rem;
            margin-top: 1.5rem;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            padding: .6rem 1.1rem;
            border-radius: 10px;
            font-size: .875rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: filter .15s, transform .15s;
        }
        .btn:active { transform: scale(.98); }
        .btn-primary {
            background: var(--bcpme-green);
            color: #111;
        }
        .btn-primary:hover { filter: brightness(1.08); }
        .btn-outline {
            background: transparent;
            color: var(--text);
            border: 1px solid var(--border);
        }
        .btn-outline:hover { background: rgba(255,255,255,.05); }
        .hint {
            font-size: .8125rem;
            color: var(--muted);
            margin-top: 2rem;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="brand">
            <div class="brand-mark">BC</div>
            <div>
                <h1>{{ config('app.name', 'ANGARA') }}</h1>
                <span>BC-PME — Plateforme financière</span>
            </div>
        </div>

        <div class="hero">
            <div class="status-row">
                <span class="code-badge">403</span>
                <span class="pill">Accès refusé</span>
            </div>
            <h2 class="title">Vous ne pouvez pas ouvrir cette page</h2>
            <p class="lead">
                Votre compte est bien connecté, mais cette ressource n’est pas disponible pour votre profil ou à cette étape du dossier.
                Il ne s’agit pas d’un dysfonctionnement de la plateforme.
            </p>
            <p class="reassure">
                <strong>Rien à signaler de votre côté</strong> : les droits d’accès sont définis selon votre rôle et le circuit de traitement des dossiers.
            </p>
            <p class="lead" style="margin-bottom: .5rem;">Cela peut arriver lorsque&nbsp;:</p>
            <ul class="list">
                <li>la fiche ou le dossier concerne un autre pôle ou une autre agence ;</li>
                <li>l’étape n’a pas encore été transmise à votre service (ex. instruction, validation) ;</li>
                <li>votre habilitation ne couvre pas cette action.</li>
            </ul>
            @if($showDetail)
                <div class="detail">{{ $detail }}</div>
            @endif
        </div>

        <div class="actions">
            @auth
                <a href="{{ route('home') }}" class="btn btn-primary">Mon espace (tableau de bord)</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary">Connexion</a>
            @endauth
            <button type="button" class="btn btn-outline" onclick="if (history.length > 1) { history.back(); } else { window.location.href = '{{ url('/') }}'; }">
                Page précédente
            </button>
            <a href="{{ url('/') }}" class="btn btn-outline">Accueil du site</a>
        </div>

        <p class="hint">
            Si vous pensez devoir accéder à ce contenu dans le cadre de vos missions, contactez votre hiérarchie ou l’administrateur de l’application pour vérifier votre profil ou l’avancement du dossier concerné.
        </p>
    </div>
</body>
</html>
