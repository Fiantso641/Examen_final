<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Livraisons</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>

    <header>
        <h1>Système de Gestion des Livraisons</h1>
    </header>

    <nav>
        <a href="<?= BASE_URL ?>/">Accueil</a>
        <a href="<?= BASE_URL ?>/livraisons">Livraisons</a>
        <a href="<?= BASE_URL ?>/benefices">Bénéfices</a>
        <a href="<?= BASE_URL ?>/zones">Zones de livraison</a> <!-- LIEN AJOUTÉ -->
    </nav>

    <main>
        <section class="card">
            <h1>Bienvenue sur Template </h1>
        </section>  

        <section class="card">
            <h2>Accès rapide</h2>
            <a class="btn" href="<?= BASE_URL ?>/livraisons">Voir les livraisons</a>
            <a class="btn btn-secondary" href="<?= BASE_URL ?>/benefices">Voir les bénéfices</a>
            <a class="btn btn-tertiary" href="<?= BASE_URL ?>/zones">Voir les zones</a> <!-- LIEN ACCÈS RAPIDE AJOUTÉ -->
        </section>
    </main>

    <footer>
        <p>© <?= date('Y') ?> – Projet Examen S3 </p>
    </footer>

</body>
</html>
