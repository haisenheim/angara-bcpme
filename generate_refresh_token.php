<?php

require 'vendor/autoload.php';

use Google\Client as GoogleClient; // 👈 Correction ici

// Étape 1 : Configuration du client
$client = new GoogleClient();
$client->setClientId('138211326223-thmb9nomiuv1oqvjag5bua907j7dnf9e.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('urn:ietf:wg:oauth:2.0:oob');
$client->setAccessType('offline');
$client->setPrompt('consent');
$client->setScopes([
    'https://www.googleapis.com/auth/drive',
]);

// Étape 2 : Générer l'URL d'autorisation
$authUrl = $client->createAuthUrl();
echo "👉 Ouvre ce lien dans ton navigateur pour autoriser l’accès à ton Google Drive :\n\n$authUrl\n\n";
echo "📝 Colle ici le code de confirmation : ";
$authCode = trim(fgets(STDIN));

// Étape 3 : Récupérer le token
$accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

if (isset($accessToken['refresh_token'])) {
    echo "\n✅ Ton REFRESH TOKEN est : \n\n";
    echo $accessToken['refresh_token'] . "\n";
} else {
    echo "\n❌ Le refresh_token n’a pas été généré.\n";
    if (isset($accessToken['error'])) {
        echo "Erreur : " . $accessToken['error_description'] . "\n";
    } else {
        echo "Vérifie que tu as bien coché l'option 'accès hors ligne' dans Google Cloud et que tu as bien autorisé l'application.\n";
    }
}



