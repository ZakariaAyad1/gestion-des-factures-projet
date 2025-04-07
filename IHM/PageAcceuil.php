<!doctype html>
<html class="no-js" lang="fr">
<head>
    <meta charset="utf-8">
    <title>FacturePro - Gestion des Factures</title>
    <meta name="description" content="Application de gestion des factures">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Raleway', sans-serif;
            background-color: #f4f6f9;
            color: #333;
        }

        #home {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #2a5298, #1e3c72);
        }

        .container {
            max-width: 1100px;
            padding: 60px 30px;
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
            text-align: center;
        }

        .home_text h2 {
            font-size: 2.2rem;
            color: #2a2a2a;
            margin-bottom: 10px;
        }

        .home_text h1 {
            font-size: 1.5rem;
            font-weight: 400;
            color: #666;
            margin-bottom: 40px;
        }

        .card-group {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }

        .features_item {
            flex: 1;
            min-width: 280px;
            background-color: #fdfdfd;
            padding: 30px 20px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.07);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .features_item:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.1);
        }

        .f_item_icon {
            font-size: 40px;
            margin-bottom: 20px;
            color: #2a5298;
        }

        .f_item_text h3 {
            font-size: 1.3rem;
            margin-bottom: 10px;
            color: #2a2a2a;
        }

        .f_item_text p {
            font-size: 0.95rem;
            color: #555;
        }

        .btn-access {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 24px;
            color: white;
            font-weight: 600;
            border-radius: 25px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .btn-client {
            background-color: #4285F4;
        }

        .btn-agent {
            background-color: #34A853;
        }

        .btn-supplier {
            background-color: #EA4335;
        }

        .btn-access:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .card-group {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>

    <script src="assets/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
</head>

<body data-spy="scroll" data-target=".navbar-collapse">

<section id="home">
    <div class="container">
        <div class="home_text">
            <h2>Bienvenue sur <strong>FacturePro</strong></h2>
            <h1>Gestion complète des factures pour clients, agents et fournisseurs</h1>
        </div>

        <div class="card-group">
            <!-- Client -->
            <div class="features_item">
                <div class="f_item_icon"><i class="fa fa-users"></i></div>
                <div class="f_item_text">
                    <h3>Espace Client</h3>
                    <p>Consultez et payez vos factures en ligne. Suivez votre historique.</p>
                    <a href="clients/login.php" class="btn-access btn-client">Accéder</a>
                </div>
            </div>

            <!-- Agent -->
            <div class="features_item">
                <div class="f_item_icon"><i class="fa fa-user-tie"></i></div>
                <div class="f_item_text">
                    <h3>Espace Agent</h3>
                    <p>Déposez le fichier (.txt) des consommations annuelles.</p>
                    <a href="agents/login_agent.php" class="btn-access btn-agent">Accéder</a>
                </div>
            </div>

            <!-- Fournisseur -->
            <div class="features_item">
                <div class="f_item_icon"><i class="fa fa-truck"></i></div>
                <div class="f_item_text">
                    <h3>Espace Fournisseur</h3>
                    <p>Envoyez vos factures et suivez les règlements. Gestion simplifiée.</p>
                    <a href="fournisseur/login.php" class="btn-access btn-supplier">Accéder</a>
                </div>
            </div>
        </div>
    </div>
</section>

</body>
</html>
