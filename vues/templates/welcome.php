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

        html {
            height: 100%;
            width: 100%;
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
            margin: 20vw;
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
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            background-color: rgba(20, 20, 20, 0.5);
        }
    </style>
</head>

<body>

    <div class="root">
        <h1>Bienvenue sur Europa.</h1>
        <p>
            Europa est un framework MVC pour serveurs Apache basé sur PHP.<br /><br />
            <b>Commencez à développer votre application web dès maintenant</b> en créant une Route dans le fichier <code>app/config/routes.json</code>, puis un Controller dans <code>controller/routeControllers/</code>, et enfin une Vue dans <code>vues/templates/</code>.<br /><br /><br />

            Pour toute information, visitez le site <a href="https://linkify.fr" target="_blank">Linkify.fr</a>.
        </p>
    </div>
</body>

</html>