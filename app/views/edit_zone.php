<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier la Zone de Livraison</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<header>
    <h1>Modifier la Zone de Livraison</h1>
    <a href="<?= BASE_URL?>/zones">← Retour à la liste des zones</a>
</header>

<main>
    <form action="<?=  BASE_URL?>/zones/update/<?= htmlspecialchars($zone['id'] ?? '') ?>" method="post">
        <div>
            <label for="nom_zone">Nom de la zone :</label>
            <input type="text" id="nom_zone" name="nom_zone" required value="<?= htmlspecialchars($zone['nom_zone'] ?? '') ?>">
        </div>
        <div>
            <label for="supplement_pourcentage">Supplément en pourcentage :</label>
            <input type="number" step="0.01" min="0" id="supplement_pourcentage" name="supplement_pourcentage" value="<?= htmlspecialchars($zone['supplement_pourcentage'] ?? '') ?>">
        </div>
        <button type="submit">Mettre à jour</button>
    </form>
</main>

<footer>
    <p>© <?= date('Y') ?> – Gestion des Livraisons</p>
</footer>
</body>
</html>
