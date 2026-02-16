<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Livraisons</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>

<header>
    <h1>Liste des Livraisons</h1>
    <a href="<?= BASE_URL  ?>/">← Retour</a>
</header>

<main>

    <!-- FORMULAIRE D'AJOUT DE LIVRAISON -->
    <section>
        <h2>Ajouter une nouvelle livraison</h2>
        <form action="<?= BASE_URL  ?>/livraisons/add" method="post">
            <div>
                <label for="colis_id">Colis (ID) :</label>
                <input type="number" id="colis_id" name="colis_id" required min="1">
            </div>
            <div>
                <label for="chauffeur_id">Chauffeur (ID) :</label>
                <input type="number" id="chauffeur_id" name="chauffeur_id" required min="1">
            </div>
            <div>
                <label for="vehicule_id">Véhicule (ID) :</label>
                <input type="number" id="vehicule_id" name="vehicule_id" required min="1">
            </div>
            <div>
                <label for="zone_id">Zone (ID) :</label>
                <input type="number" id="zone_id" name="zone_id" min="0" value="0">
            </div>
            <div>
                <label for="adresse_destination">Adresse de destination :</label>
                <textarea id="adresse_destination" name="adresse_destination" required rows="2"></textarea>
            </div>
            <div>
                <label for="cout_vehicule">Coût véhicule :</label>
                <input type="number" id="cout_vehicule" name="cout_vehicule" required step="0.01" min="0">
            </div>
            <div>
                <label for="salaire_chauffeur">Salaire chauffeur :</label>
                <input type="number" id="salaire_chauffeur" name="salaire_chauffeur" required step="0.01" min="0">
            </div>
            <div>
                <label for="date_livraison">Date de livraison :</label>
                <input type="date" id="date_livraison" name="date_livraison" required>
            </div>
            <button type="submit">Ajouter la livraison</button>
        </form>
    </section>

    <hr>

    <?php $livraisons = $livraisons ?? []; ?>
    <?php if (empty($livraisons)): ?>
        <p>Aucune livraison trouvée.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Colis</th>
                    <th>Chauffeur</th>
                    <th>Véhicule</th>
                    <th>Adresse destination</th>
                    <th>Coût véhicule</th>
                    <th>Salaire chauffeur</th>
                    <th>Chiffre d'affaires</th>
                    <th>Statut</th>
                    <th>Date de livraison</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($livraisons as $livraison): ?>
                    <tr>
                        <td><?= htmlspecialchars($livraison['id']) ?></td>
                        <td><?= htmlspecialchars($livraison['colis_reference'] ?? $livraison['colis_id']) ?></td>
                        <td><?= htmlspecialchars($livraison['chauffeur'] ?? $livraison['chauffeur_id']) ?></td>
                        <td><?= htmlspecialchars($livraison['vehicule'] ?? $livraison['vehicule_id']) ?></td>
                        <td><?= htmlspecialchars($livraison['adresse_destination']) ?></td>
                        <td><?= number_format($livraison['cout_vehicule'], 0, ',', ' ') ?> Ar</td>
                        <td><?= number_format($livraison['salaire_chauffeur'], 0, ',', ' ') ?> Ar</td>
                        <td><?= number_format($livraison['chiffre_affaire_total'] ?? $livraison['chiffre_affaire'], 0, ',', ' ') ?> Ar</td>
                        <td><?= htmlspecialchars($livraison['statut']) ?></td>
                        <td><?= htmlspecialchars($livraison['date_livraison']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

<footer>
    <p>© <?= date('Y') ?> – Gestion des Livraisons</p>
</footer>

</body>
</html>
