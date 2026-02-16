<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bénéfices</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
    <header>
        <h1>Bénéfices par <?= htmlspecialchars($label ?? 'Jour') ?></h1>
        <a href="<?= BASE_URL ?>/">← Retour</a>
    </header>

    <div class="card">
        <a href="<?= BASE_URL ?>/benefices?type=jour" class="btn <?= ($type === 'jour') ? 'btn-active' : '' ?>">Par Jour</a>
        <a href="<?= BASE_URL ?>/benefices?type=mois" class="btn <?= ($type === 'mois') ? 'btn-active btn-secondary' : '' ?>">Par Mois</a>
        <a href="<?= BASE_URL ?>/benefices?type=annee" class="btn <?= ($type === 'annee') ? 'btn-active' : '' ?>">Par Année</a>
    </div>

    <main>
        <?php if (empty($benefices)): ?>
            <div class="card">
                <p>Aucun bénéfice trouvé.</p>
            </div>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th><?= htmlspecialchars($label ?? 'Période') ?></th>
                        <th>Nombre de Livraisons</th>
                        <th>Chiffre d'Affaires (Ar)</th>
                        <th>Bénéfice Total (Ar)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $total_ca_global = 0;
                        $total_benefice_global = 0;
                        $total_livraisons_global = 0;
                        foreach ($benefices as $b): 
                            // Calcul des totaux
                            $ca = $b['ca_total'] ?? 0;
                            $benefice = $b['benefice_total'] ?? 0;
                            $nb = $b['nb_livraisons'] ?? 0;

                            $total_ca_global += $ca;
                            $total_benefice_global += $benefice;
                            $total_livraisons_global += $nb;
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($b['jour'] ?? $b['mois'] ?? $b['annee'] ?? $b['periode'] ?? '') ?></td>
                            <td><?= (int)$nb ?></td>
                            <td><?= number_format($ca, 0, ',', ' ') ?> Ar</td>
                            <td style="color: <?= ($benefice > 0) ? 'green' : (($benefice < 0) ? 'red' : 'black') ?>; font-weight: bold;">
                                <?= number_format($benefice, 0, ',', ' ') ?> Ar
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr style="font-weight: bold; background-color: #f0f0f0;">
                        <td>TOTAL</td>
                        <td><?= $total_livraisons_global ?></td>
                        <td><?= number_format($total_ca_global, 0, ',', ' ') ?> Ar</td>
                        <td style="color: <?= ($total_benefice_global > 0) ? 'green' : (($total_benefice_global < 0) ? 'red' : 'black') ?>;">
                            <?= number_format($total_benefice_global, 0, ',', ' ') ?> Ar
                        </td>
                    </tr>
                </tbody>
            </table>
        <?php endif; ?>
    </main>

    <footer>
        <p>© <?= date('Y') ?> – Gestion des Livraisons</p>
    </footer>
</body>
</html>
