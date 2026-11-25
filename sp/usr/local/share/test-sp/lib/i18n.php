<?php

if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
} elseif (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    $locale = locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE']);
    $lang = locale_lookup(array('en', 'fr'), $locale, true, 'en');
} else {
    $lang = 'en';
}

$translations = array(
    "IdP tutorial test service" => array(
        "fr" => "Service de test du tutoriel IdP",
    ),
    "Home" => array(
        "fr" => "Accueil",
    ),
    "https://services.renater.fr/federation/en/outils/outils-de-test/sp-de-test" => array(
        "fr" => "https://services.renater.fr/federation/outils/outils-de-test/sp-de-test",
    ),
    "Help" => array(
        "fr" => "Aide",
    ),
    "Support" => array(
        "fr" => "Support",
    ),
    "Sign out" => array(
        "fr" => "Déconnexion",
    ),
    "To sign in and view attributes propagated by your identity provider, select it below." => array(
        "fr" => "Pour vous connecter et visualiser les attributs propagés par votre fournisseur d'identité, sélectionnez celui-ci ci-dessous.",
    ),
    "Advanced options" => array(
        "fr" => "Options avancées",
    ),
    "Protocol" => array(
        "fr" => "Protocole",
    ),
    "Requested authentication method(s)" => array(
        "fr" => "Méthode(s) d'authentification demandée(s)",
    ),
    "Comparison criteria" => array(
        "fr" => "Critère de comparaison",
    ),
    "Other options" => array(
        "fr" => "Autre options",
    ),
    "force authentication" => array(
        "fr" => "forcer l'authentification",
    ),
    "The user attributes provided by your identity provider are:" => array(
        "fr" => "Les attributs utilisateurs fournis par votre fournisseur d'identité sont:",
    ),
    "Attribute (a&darr;z)" => array(
        "fr" => "Attribut (a&darr;z)",
    ),
    "Variable (a&darr;z)" => array(
        "fr" => "Variable (a&darr;z)",
    ),
    "Value" => array(
        "fr" => "Valeur",
    ),
    "The Shibboleth variables are:" => array(
        "fr" => "Les variables liées à Shibboleth sont:",
    ),
    "Renater web site" => array(
        "fr" => "site web de Renater",
    ),
);

function i18n($token, $lang = null) {
    global $translations;
    if (   empty($lang)
        || !array_key_exists($token, $translations)
        || !array_key_exists($lang, $translations[$token])
    ) {
        return $token;
    } else {
        return $translations[$token][$lang];
    }
}

?>
