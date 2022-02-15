<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="https://linkify.fr/public/content/images/icon.ico">
    <title>Europa - Page d'accueil</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(-45deg, rgba(13, 22, 46, 1) 0%, rgba(0, 51, 153, 1) 50%, rgba(13, 22, 46, 1) 100%);
            height: 100%;
            width: 100%;

        }

        .root,
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .root {
            margin: 15vw;
        }

        h1 {
            margin: 0;
            font-size: 3rem;
            color: #eee7c9;
            font-family: Raleway, monospace;
        }

        p,
        a {
            color: #aca795;
            font-family: Raleway;
        }

        p a {
            text-decoration: none;
            font-style: oblique;
        }

        code {
            font-style: normal;
        }

        code {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            background-color: rgba(20, 20, 20, 0.5);
        }

        pre {
            background-color: rgba(255, 255, 255, 0.7);
            padding: 20px;
            border-radius: 5px;
            filter: invert();
            width: 100%;
        }
    </style>
</head>

<body>

    <div class="root">
        <h1>Bienvenue sur Europa.</h1>
        <p>
            Europa est un framework MVC pour serveurs Apache basé sur PHP.<br /><br />
            <b>Commencez à développer votre application web dès maintenant</b> en créant une Route dans le fichier <a href="vscode://file/<?= \realpath("app/config/routes.json") ?>"><code>app/config/routes.json</code></a>, puis un Controller dans <a href="vscode://file/<?= \realpath("controller/routeControllers/AccueilController.php") ?>"><code>controller/routeControllers/</code></a>, et enfin une Vue dans <a href="vscode://file/<?= \realpath("vues/templates/welcome.php") ?>"><code>vues/templates/</code></a>.<br /><br /><br />

            Pour toute information, visitez le site <a href="https://linkify.fr" target="_blank">Linkify.fr</a>.
        </p>
        <?php
        $debug = array(
            "environment" => \Europa\Core\Kernel::$ENV,
            "options" => \Europa\Core\Kernel::$OPTIONS,
            "database" => \Europa\Core\Kernel::$DATABASE,
            "request" => array(
                "requestPath" => \Europa\Core\RequestHandler::$request,
                "currentDomain" => \Europa\Core\RequestHandler::$currentDomain,
                "defaultPath" => \Europa\Core\RequestHandler::$defaultPath
            ),
            "route" => array(
                "currentRoute" => \Europa\Core\RouteHandler::$currentRoute,
                "URLattributes" => \Europa\Core\RouteHandler::$URLattributes
            ),
            "debugStatus" => "VALID"
        );
        var_dump($debug);
        ?>
    </div>
</body>

</html>