<?php $title = 'Tableau de bord'; include __DIR__ . '/../_layout_top.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Tableau de bord</h3>
  <form method="post" action="<?= BASE_URL ?>/dispatch/simuler">
    <button class="btn btn-dark" type="submit">Simuler le dispatch</button>
  </form>
</div>

<?php if(empty($villes)): ?>
  <div class="alert alert-secondary">Aucune ville.</div>
<?php else: ?>
  <?php foreach($villes as $v): ?>
    <div class="card mb-3">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h5 class="mb-0"><?= htmlspecialchars($v['ville_nom']) ?></h5>
            <?php if(!empty($v['region'])): ?>
              <div class="text-muted small">Région: <?= htmlspecialchars((string)$v['region']) ?></div>
            <?php endif; ?>
          </div>
          <a class="btn btn-outline-dark btn-sm" href="<?= BASE_URL ?>/besoins?ville=<?= (int)$v['ville_id'] ?>">Voir besoins</a>
        </div>

        <div class="table-responsive mt-3">
          <table class="table table-sm align-middle">
            <thead>
              <tr>
                <th>Type</th>
                <th>Libellé</th>
                <th class="text-end">PU</th>
                <th class="text-end">Qté</th>
                <th class="text-end">Attribué</th>
                <th class="text-end">Reste</th>
              </tr>
            </thead>
            <tbody>
              <?php if(empty($v['besoins'])): ?>
                <tr><td colspan="6" class="text-muted">Aucun besoin.</td></tr>
              <?php else: ?>
                <?php foreach($v['besoins'] as $b): ?>
                  <tr>
                    <td><?= htmlspecialchars($b['type']) ?></td>
                    <td><?= htmlspecialchars($b['libelle']) ?></td>
                    <td class="text-end"><?= number_format((float)$b['prix_unitaire'], 2, '.', ' ') ?></td>
                    <td class="text-end"><?= number_format((float)$b['quantite'], 2, '.', ' ') ?></td>
                    <td class="text-end"><?= number_format((float)$b['quantite_attribuee'], 2, '.', ' ') ?></td>
                    <td class="text-end"><?= number_format((float)$b['quantite_restante'], 2, '.', ' ') ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>

<?php include __DIR__ . '/../_layout_bottom.php'; ?>
