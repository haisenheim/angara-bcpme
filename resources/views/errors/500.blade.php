@php
    /** @var \Throwable|null $exception */
    $exception = $exception ?? null;
    $debug = config('app.debug');
    $httpStatus = $httpStatus ?? ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface ? $exception->getStatusCode() : 500);
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Erreur serveur — ANGARA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bcpme-green: #88b824;
            --bcpme-red: #c41e3a;
            --bg: #0f1419;
            --surface: #1a222d;
            --border: rgba(255,255,255,.08);
            --text: #e8eaed;
            --muted: #9aa0a6;
            --code-bg: #0d1117;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Inter', system-ui, sans-serif;
            background: radial-gradient(ellipse 120% 80% at 50% -20%, rgba(136,184,36,.15), transparent 50%), var(--bg);
            color: var(--text);
            line-height: 1.5;
        }
        .wrap {
            max-width: 920px;
            margin: 0 auto;
            padding: 2.5rem 1.25rem 4rem;
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
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--bcpme-red);
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
            background: rgba(196, 30, 58, .15);
            color: #f5a0ad;
            border: 1px solid rgba(196, 30, 58, .35);
        }
        .pill.debug {
            background: rgba(136, 184, 36, .12);
            color: #c5e86c;
            border-color: rgba(136, 184, 36, .35);
        }
        .lead {
            font-size: 1.05rem;
            color: var(--muted);
            margin: 0 0 1rem;
        }
        .msg {
            font-size: 1rem;
            color: var(--text);
            margin: 0;
            word-break: break-word;
        }
        .panel {
            border: 1px solid var(--border);
            border-radius: 12px;
            background: var(--surface);
            overflow: hidden;
            margin-bottom: 1rem;
        }
        .panel-h {
            padding: .65rem 1rem;
            font-size: .75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--muted);
            border-bottom: 1px solid var(--border);
            background: rgba(0,0,0,.2);
        }
        .panel-b { padding: 1rem; }
        .mono {
            font-family: 'JetBrains Mono', ui-monospace, monospace;
            font-size: .8125rem;
            line-height: 1.6;
        }
        pre.trace {
            margin: 0;
            max-height: 420px;
            overflow: auto;
            padding: 1rem;
            background: var(--code-bg);
            color: #c9d1d9;
            border-radius: 0 0 12px 12px;
            white-space: pre-wrap;
            word-break: break-all;
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
            transition: transform .15s, opacity .15s;
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
        .btn-danger-outline {
            background: transparent;
            color: #f5a0ad;
            border: 1px solid rgba(196, 30, 58, .45);
        }
        .btn-danger-outline:hover { background: rgba(196, 30, 58, .12); }
        .hint {
            font-size: .8125rem;
            color: var(--muted);
            margin-top: 2rem;
        }
        @media (max-width: 540px) {
            .code-badge { font-size: 2rem; }
        }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="brand">
            <div class="brand-mark">BC</div>
            <div>
                <h1>ANGARA</h1>
                <span>BC-PME — Plateforme financière</span>
            </div>
        </div>

        <div class="hero">
            <div class="status-row">
                <span class="code-badge">{{ $httpStatus }}</span>
                <span class="pill">Erreur serveur</span>
                @if($debug)
                    <span class="pill debug">Debug</span>
                @endif
            </div>
            <p class="lead">Une erreur interne s’est produite. Les détails ci-dessous ne sont visibles qu’en environnement de développement.</p>
            @if($debug && $exception)
                <p class="msg"><strong class="mono">{{ get_class($exception) }}</strong><br>{{ $exception->getMessage() ?: '(aucun message)' }}</p>
            @elseif(!$debug)
                <p class="msg">Veuillez réessayer plus tard ou contacter l’administrateur.</p>
            @endif
        </div>

        @if($debug && $exception)
            <div class="panel">
                <div class="panel-h">Fichier &amp; ligne</div>
                <div class="panel-b mono">{{ $exception->getFile() }} : {{ $exception->getLine() }}</div>
            </div>

            <div class="panel">
                <div class="panel-h">Pile d’exécution</div>
                <pre class="trace mono">{{ $exception->getTraceAsString() }}</pre>
            </div>

            @if($previous = $exception->getPrevious())
                <div class="panel">
                    <div class="panel-h">Exception précédente</div>
                    <div class="panel-b mono">{{ get_class($previous) }} — {{ $previous->getMessage() }}</div>
                    <pre class="trace mono">{{ $previous->getTraceAsString() }}</pre>
                </div>
            @endif
        @endif

        <div class="actions">
            <a href="{{ url('/') }}" class="btn btn-primary">Retour à l’accueil</a>
            <button type="button" class="btn btn-outline" onclick="window.location.reload()">Recharger la page</button>
            @if(Route::has('logout'))
                <form action="{{ route('logout') }}" method="post" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger-outline">Se déconnecter</button>
                </form>
            @endif
        </div>

        <p class="hint">
            @if($debug)
                <strong>APP_DEBUG</strong> est activé : ne pas exposer cette page en production.
            @else
                En production, les détails techniques sont masqués.
            @endif
        </p>
    </div>
</body>
</html>
