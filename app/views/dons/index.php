<?php $title = 'Dons'; include __DIR__ . '/../_layout_top.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Dons</h3>
  <a class="btn btn-outline-dark" href="<?= BASE_URL ?>/dashboard">Dashboard</a>
</div>

<div class="card mb-3">
  <div class="card-body">
    <h5 class="card-title">Saisir un don</h5>
    <form class="row g-2" method="post" action="<?= BASE_URL ?>/dons/add">
      <div class="col-md-2">
        <input class="form-control" type="date" name="date_don" value="<?= date('Y-m-d') ?>" required>
      </div>
      <div class="col-md-2">
        <select class="form-select" name="type" required>
          <option value="nature">Nature</option>
          <option value="materiaux">Matériaux</option>
          <option value="argent">Argent</option>
        </select>
      </div>
      <div class="col-md-3">
        <input class="form-control" name="libelle" placeholder="Libellé (riz, huile, argent...)" required>
      </div>
      <div class="col-md-2">
        <input class="form-control" type="number" step="0.01" min="0" name="prix_unitaire" placeholder="Prix unitaire" required>
      </div>
      <div class="col-md-2">
        <input class="form-control" type="number" step="0.01" min="0" name="quantite" placeholder="Quantité" required>
      </div>
      <div class="col-md-1">
        <button class="btn btn-dark w-100" type="submit">Ajouter</button>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-body">
    <h5 class="card-title">Liste des dons</h5>
    <div class="table-responsive mt-3">
      <table class="table table-sm align-middle">
        <thead>
          <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Libellé</th>
            <th class="text-end">PU</th>
            <th class="text-end">Qté</th>
            <th class="text-end">Reste</th>
            <th class="text-end">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if(empty($dons)): ?>
            <tr><td colspan="7" class="text-muted">Aucun don.</td></tr>
          <?php else: ?>
            <?php foreach($dons as $d): ?>
              <tr>
                <td><?= htmlspecialchars($d['date_don']) ?></td>
                <td><?= htmlspecialchars($d['type']) ?></td>
                <td><?= htmlspecialchars($d['libelle']) ?></td>
                <td class="text-end"><?= number_format((float)$d['prix_unitaire'], 2, '.', ' ') ?></td>
                <td class="text-end"><?= number_format((float)$d['quantite'], 2, '.', ' ') ?></td>
                <td class="text-end"><?= number_format((float)$d['quantite_restante'], 2, '.', ' ') ?></td>
                <td class="text-end">
                  <form method="post" action="<?= BASE_URL ?>/dons/delete/<?= (int)$d['id'] ?>" onsubmit="return confirm('Supprimer ce don ?');" style="display:inline-block">
                    <button class="btn btn-sm btn-outline-danger" type="submit">Supprimer</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../_layout_bottom.php'; ?>
