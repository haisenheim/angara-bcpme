@php
    /** @var \App\Models\User|null $recipient */
    /** @var \App\Models\User|null $actor */
    /** @var array $payload */
@endphp
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $payload['subject'] ?? 'Notification' }}</title>
</head>
<body style="margin:0;padding:0;background:#f6f7fb;font-family:Arial,Helvetica,sans-serif;color:#1f2937;">
    <div style="max-width:720px;margin:0 auto;padding:24px;">
        <div style="background:#ffffff;border:1px solid #e5e7eb;border-radius:10px;overflow:hidden;">
            <div style="padding:18px 20px;background:#0f172a;color:#fff;">
                <div style="font-size:14px;opacity:.9;">Angara</div>
                <div style="font-size:18px;font-weight:700;margin-top:4px;">
                    {{ $payload['title'] ?? ($payload['subject'] ?? 'Notification') }}
                </div>
            </div>

            <div style="padding:18px 20px;">
                @if(!empty($payload['body']))
                    <div style="font-size:14px;line-height:1.6;white-space:pre-line;">{{ $payload['body'] }}</div>
                @endif

                @if(!empty($context))
                    <div style="margin-top:14px;padding:12px 14px;background:#f8fafc;border:1px solid #e5e7eb;border-radius:8px;">
                        @if(isset($context['entreprise']) && $context['entreprise'])
                            <div style="font-size:12px;color:#6b7280;margin-bottom:6px;">Entreprise</div>
                            <div style="font-size:14px;font-weight:700;">
                                {{ $context['entreprise']->name ?? ($context['entreprise_label'] ?? '—') }}
                            </div>
                        @endif

                        @if(isset($context['dossier']) && $context['dossier'])
                            <div style="margin-top:10px;font-size:12px;color:#6b7280;margin-bottom:6px;">Dossier</div>
                            <div style="font-size:14px;font-weight:700;">
                                {{ $context['dossier_label'] ?? ($context['dossier']->token ?? '—') }}
                            </div>
                            @if(!empty($context['programmes']))
                                <div style="font-size:13px;color:#374151;margin-top:4px;">
                                    Programmes : {{ $context['programmes'] }}
                                </div>
                            @endif
                        @endif
                    </div>
                @endif

                @if(!empty($payload['cta_url']) && !empty($payload['cta_label']))
                    <div style="margin-top:18px;">
                        <a href="{{ $payload['cta_url'] }}"
                           style="display:inline-block;background:#2563eb;color:#fff;text-decoration:none;padding:10px 14px;border-radius:8px;font-weight:700;font-size:14px;">
                            {{ $payload['cta_label'] }}
                        </a>
                    </div>
                @endif

                @if(!empty($actor))
                    <div style="margin-top:18px;font-size:12px;color:#6b7280;">
                        Expéditeur : {{ $actor->name ?? '—' }}@if(!empty($actor->email)) ({{ $actor->email }})@endif
                    </div>
                @endif

                @if(!empty($forced_to) && !empty($intended_to))
                    <div style="margin-top:10px;font-size:12px;color:#9ca3af;">
                        Mode test actif : email redirigé vers {{ $forced_to }} (destinataire prévu : {{ $intended_to }}).
                    </div>
                @endif
            </div>

            <div style="padding:12px 20px;border-top:1px solid #e5e7eb;background:#fafafa;font-size:12px;color:#6b7280;">
                Ceci est un message automatique. Si vous n’êtes pas concerné, ignorez cet email.
            </div>
        </div>
    </div>
</body>
</html>

