<?php $title = 'Besoins'; include __DIR__ . '/../_layout_top.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Besoins</h3>
  <a class="btn btn-outline-dark" href="<?= BASE_URL ?>/dashboard">Dashboard</a>
</div>

<div class="card mb-3">
  <div class="card-body">
    <h5 class="card-title">Saisir un besoin</h5>
    <form class="row g-2" method="post" action="<?= BASE_URL ?>/besoins/add">
      <div class="col-md-3">
        <select class="form-select" name="ville_id" required>
          <option value="">Ville...</option>
          <?php foreach($villes as $v): ?>
            <option value="<?= (int)$v['id'] ?>" <?= ($ville_id === (int)$v['id']) ? 'selected' : '' ?>><?= htmlspecialchars($v['nom']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2">
        <select class="form-select" name="type" required>
          <option value="nature">Nature</option>
          <option value="materiaux">Matériaux</option>
          <option value="argent">Argent</option>
        </select>
      </div>
      <div class="col-md-3">
        <input class="form-control" name="libelle" placeholder="Libellé (riz, huile, tôle...)" required>
      </div>
      <div class="col-md-2">
        <input class="form-control" type="number" step="0.01" min="0" name="prix_unitaire" placeholder="Prix unitaire" required>
      </div>
      <div class="col-md-1">
        <input class="form-control" type="number" step="0.01" min="0" name="quantite" placeholder="Qté" required>
      </div>
      <div class="col-md-1">
        <button class="btn btn-dark w-100" type="submit">Ajouter</button>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Liste des besoins</h5>
      <form method="get" action="<?= BASE_URL ?>/besoins" class="d-flex gap-2">
        <select class="form-select form-select-sm" name="ville">
          <option value="">Toutes les villes</option>
          <?php foreach($villes as $v): ?>
            <option value="<?= (int)$v['id'] ?>" <?= ($ville_id === (int)$v['id']) ? 'selected' : '' ?>><?= htmlspecialchars($v['nom']) ?></option>
          <?php endforeach; ?>
        </select>
        <button class="btn btn-sm btn-outline-dark" type="submit">Filtrer</button>
      </form>
    </div>

    <div class="table-responsive mt-3">
      <table class="table table-sm align-middle">
        <thead>
          <tr>
            <th>Ville</th>
            <th>Type</th>
            <th>Libellé</th>
            <th class="text-end">PU</th>
            <th class="text-end">Qté</th>
            <th class="text-end">Reste</th>
            <th class="text-end">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if(empty($besoins)): ?>
            <tr><td colspan="7" class="text-muted">Aucun besoin.</td></tr>
          <?php else: ?>
            <?php foreach($besoins as $b): ?>
              <tr>
                <td><?= htmlspecialchars($b['ville_nom']) ?></td>
                <td><?= htmlspecialchars($b['type']) ?></td>
                <td><?= htmlspecialchars($b['libelle']) ?></td>
                <td class="text-end"><?= number_format((float)$b['prix_unitaire'], 2, '.', ' ') ?></td>
                <td class="text-end"><?= number_format((float)$b['quantite'], 2, '.', ' ') ?></td>
                <td class="text-end"><?= number_format((float)$b['quantite_restante'], 2, '.', ' ') ?></td>
                <td class="text-end">
                  <form method="post" action="<?= BASE_URL ?>/besoins/delete/<?= (int)$b['id'] ?>" onsubmit="return confirm('Supprimer ce besoin ?');" style="display:inline-block">
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
