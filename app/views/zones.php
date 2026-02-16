<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Zones de Livraison</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>

<header>
    <h1>Zones de Livraison</h1>
    <a href="<?= BASE_URL ?>/">← Retour</a>
</header>

<main>
    <?php $zones = $zones ?? []; ?>

    <!-- Liste des zones -->
    <?php if (empty($zones)): ?>
        <p>Aucune zone trouvée.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom de la zone</th>
                    <th>Supplément (%)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($zones as $zone): ?>
                    <tr>
                        <td><?= htmlspecialchars($zone['id']) ?></td>
                        <td><?= htmlspecialchars($zone['nom_zone']) ?></td>
                        <td>
                            <?= number_format($zone['supplement_pourcentage'] ?? 0, 2, ',', ' ') ?>
                        </td>
                        <td>
                            <!-- Lien modifier -->
                            <a href="<?= BASE_URL ?>/zones/edit/<?= htmlspecialchars($zone['id']) ?>">Modifier</a> |
                            <!-- Formulaire suppression -->
                            <form action="<?= BASE_URL ?>/zones/delete/<?= htmlspecialchars($zone['id']) ?>" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer cette zone ?');">
                                <button type="submit" style="background:none; border:none; color:red; cursor:pointer;">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <hr>

    <!-- Formulaire Ajout / Modification -->
    <h2><?= isset($zoneToEdit) ? 'Modifier une zone' : 'Ajouter une nouvelle zone' ?></h2>
    <form action="<?= BASE_URL ?>/zones/<?= isset($zoneToEdit) ? 'update/' . htmlspecialchars($zoneToEdit['id']) : 'add' ?>" method="POST">
        <label for="nom_zone">Nom de la zone :</label><br>
        <input type="text" id="nom_zone" name="nom_zone" required value="<?= isset($zoneToEdit) ? htmlspecialchars($zoneToEdit['nom_zone']) : '' ?>"><br><br>

        <label for="supplement_pourcentage">Supplément en pourcentage :</label><br>
        <input type="number" id="supplement_pourcentage" name="supplement_pourcentage" step="0.01" min="0" required value="<?= isset($zoneToEdit) ? htmlspecialchars($zoneToEdit['supplement_pourcentage']) : '' ?>"><br><br>

        <button type="submit"><?= isset($zoneToEdit) ? 'Enregistrer la modification' : 'Ajouter la zone' ?></button>

        <?php if (isset($zoneToEdit)): ?>
            <a href="<?= BASE_URL ?>/zones" style="margin-left: 10px;">Annuler</a>
        <?php endif; ?>
    </form>
</main>

<footer>
    <p>© <?= date('Y') ?> – Gestion des Zones de Livraison</p>
</footer>

</body>
</html>
