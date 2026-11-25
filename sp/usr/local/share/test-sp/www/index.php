<!doctype html>
<?php
$top_level = dirname(__DIR__);
require_once($top_level . '/etc/config.php');
require_once($top_level . '/lib/functions.php');
require_once($top_level . '/lib/i18n.php');
?>
    <html lang="<?php echo $lang ?>">
    <head>
        <meta charset="utf-8" />
        <title><?php echo i18n('IdP tutorial test service', $lang) ?></title>
        <link type="text/css" rel="stylesheet" href="/libs/foundation/6.8.1/css/foundation.min.css">
        <link type="text/css" rel="stylesheet" href="css/style.css" />
        <link type="image/png" rel="icon" href="images/favicon.png" />
        <script type="text/javascript" src="/libs/jquery/3.7.1/jquery.min.js"></script>
        <script type="text/javascript" src="/libs/foundation/6.8.1/js/vendor/foundation.min.js"></script>
        <script type="text/javascript" src="js/test-sp.js"></script>
    </head>

    <body>
        <header role="banner">
            <div class="title-bar" data-responsive-toggle="main-menu" data-hide-for="medium">
                <button type="button" class="menu-icon top-bar-right" data-toggle></button>
                <div class="title-bar-title top-bar-right"></div>
                <div class="top-bar-left">
                <a href="/" title="<?php echo i18n('Home', $lang) ?>">
                    <img src="images/tuto-jres2026.svg" alt="<?php echo i18n('Logo of tutorial JRES 2026', $lang) ?>" data-options="disable_for_touch:true">
                    </a>
                </div>
            </div>
            <nav class="top-bar" id="main-menu">
                <div class="top-bar-left">
                    <ul class="menu" data-dropdown-menu>
                        <li class="menu-text">
                            <a href="/" title="<?php echo i18n('Home', $lang) ?>">
                                <img src="images/tuto-jres2026.svg" alt="Logo du tutoriel des JRES 2026" data-options="disable_for_touch:true">
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="top-bar-right">
                    <ul class="menu icon-top" data-back-button="<li class='js-drilldown-back'><a tabindex='0'>Back</a></li>" data-responsive-menu="drilldown medium-dropdown">
                        <li>
                            <a href="https://services.renater.fr/federation/en/outils/outils-de-test/sp-de-test" title="<?php echo i18n('Help', $lang) ?>" data-tooltip class="left" target="_blank">
                                <img class="hide-for-small-only support_img" src="images/support.svg" alt="<?php echo i18n('Help', $lang) ?>" data-options="disable_for_touch:true"/>
                                <span class="show-for-small-only"><?php echo i18n('Help', $lang) ?></span>
                            </a>
                        </li>
<?php if (is_connected($use_headers)) { ?>
                        <li>
                            <a href="<?php printf('/Shibboleth.sso/Logout?return=%s', $entityid) ?>" title="<?php echo i18n('Sign out', $lang) ?>" data-tooltip data-show-on="medium">
                                <img class="hide-for-small-only user_connected_img" src="images/connected_user.svg" alt="<?php echo i18n('Sign out', $lang) ?>" />
                                <span class="show-for-small-only"><?php echo i18n('Sign out', $lang) ?></span>
                            </a>
                        </li>
<?php } ?>
                    </ul>
                </div>
            </nav>
        </header>

        <main role="main">
            <h1 class="text-center"><?php echo i18n('IdP tutorial test service', $lang) ?></h1>
<?php if (!is_connected($use_headers)) { ?>
            <div class="home grid-container">
                <p class="text-center"><?php echo i18n('To sign in and view attributes propagated by your identity provider, select it below.', $lang) ?></p>
                <ul id="federations" class="home grid-x small-up-2 medium-up-3 large-up-4 text-center align-center" data-entityid="<?php echo $entityid ?>">
<?php foreach ($federations as $id => $federation) { ?>
                    <li class="cell">
                        <a href="<?php printf('/Shibboleth.sso/Login?target=%s', $entityid) ?>" data-federation="<?php echo $id ?>" title="Se connecter avec <?php echo $federation['name'][$lang] ?>">
                            <span><img src="<?php echo $federation['icon'] ?>" alt=""/></span>
                            <span class="fede-name"><?php echo $federation['name'][$lang] ?></span>
                        </a>
                    </li>
<?php } ?>
                </ul>

                <button class="button" type="button" data-toggle="options"><?php echo i18n('Advanced options', $lang) ?></button>

                <div id="options" class="grid-x grid-margin-x hide" data-toggler=".hide">
                    <fieldset id="classref" class="fieldset small-12 medium-4 cell">
                        <legend><?php echo i18n('Requested authentication method(s)', $lang) ?></legend>
<?php foreach ($authentications as $id => $authentication) { ?>
                        <input type="checkbox" id="classref.<?php echo $id ?>" name="classref.<?php echo $id ?>" value="<?php echo $id ?>">
                        <label for="classref.<?php echo $id ?>" title="<?php echo $id ?>"><?php echo $authentication[$lang] ?></label>
                        <br>
<?php } ?>
                    </fieldset>

                    <fieldset id="comparison" class="fieldset small-12 medium-4 cell">
                        <legend><?php echo i18n('Comparison criteria', $lang) ?></legend>
<?php foreach ($comparisons as $id => $comparison) { ?>
                        <input type="radio" id="comparison.<?php echo $id ?>" name="comparison" value="<?php echo $id ?>">
                        <label for="comparison.<?php echo $id ?>" title="<?php echo $id ?>"><?php echo $comparison[$lang] ?></label>
                        <br>
<?php } ?>
                    </fieldset>

                    <div class="small-12 medium-4 cell">
                        <fieldset id="other" class="fieldset">
                            <legend><?php echo i18n('Other options', $lang) ?></legend>
                            <input type="checkbox" id="forceauthn" name="forceauthn">
                            <label for="forceauthn" title="ForceAuthn"><?php echo i18n('force authentication', $lang) ?></label>
                        </fieldset>
                    </div>
                </div>

<?php } else { ?>
            <div class="content grid-container">
                <div id="attributes">
                    <table>
                        <caption><h2><?php echo i18n('The user attributes provided by your identity provider are:', $lang) ?></h2></caption>
                        <thead>
                        <tr>
                        <th><?php echo i18n('Attribute (a&darr;z)', $lang) ?></th><th><?php echo i18n('Value', $lang) ?></th>
                        </tr>
                        </thead>
                        <tbody>
            <?php display_table($attributes, $use_headers); ?>
                        </tbody>
                    </table>
                </div>

                <div id="variables">
                    <table>
                        <caption><h2><?php echo i18n('The Shibboleth variables are:', $lang) ?></h2></caption>
                        <thead>
                            <tr>
                                <th><?php echo i18n('Variable (a&darr;z)', $lang) ?></th><th><?php echo i18n('Value', $lang) ?></th>
                            </tr>
                        </thead>
                        <tbody>
            <?php display_table($variables, $use_headers); ?>
                        </tbody>
                    </table>
                </div>
<?php } ?>
            </div>
        </main>

        <footer role="contentinfo">
            <article class="hide-for-small-only">
                <p class="grid-x grid-margin-x">
                    <span class="shrink cell text-left"><img src="images/logo_renater_blanc.png" alt="RENATER"/></span>
                    <span class="auto cell text-left">Réseau National de télécommunications <br/>pour la Technologie l’Enseignement et la Recherche.</span>
                    <span class="auto cell text-right">
                        <a href="https://www.facebook.com/gip.renater" target="_blank">
                            <img src="images/facebook.svg" alt="Facebook" data-options="disable_for_touch:true"/>
                        </a>
                        <a href="https://twitter.com/RENATERnews" target="_blank">
                            <img src="images/twitter.svg" alt="Twitter" data-options="disable_for_touch:true"/>
                        </a>
                    </span>
                </p>
            </div>
            <aside class="grid-x grid-margin-x">
                    <div class="small-12 medium-5 cell text-left">RENATER - All rights reserved</div>
                    <div class="small-12 medium-7 cell text-right">
                        <ul class="grid-x">
                            <li><a href="https://services.renater.fr/federation/outils/outils-de-test/sp-de-test" title="<?php echo i18n('Help', $lang) ?>" target="_blank"><?php echo i18n('Help', $lang) ?></a> |</li>
                            <li><a href="https://assistance.renater.fr" title="<?php echo i18n('Support', $lang) ?>" target="_blank"><?php echo i18n('Support', $lang) ?></a> |</li>
                            <li><a href="https://www.renater.fr/" title="<?php echo i18n('Renater web site', $lang) ?>" target="_blank">www.renater.fr</a>
                        </ul>
                    </div>
                </p>
            </aside>
        </footer>
    </body>
</html>
