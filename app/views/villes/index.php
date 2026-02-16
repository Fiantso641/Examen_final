<?php $title = 'Villes'; include __DIR__ . '/../_layout_top.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Villes</h3>
  <a class="btn btn-outline-dark" href="<?= BASE_URL ?>/dashboard">Dashboard</a>
</div>

<div class="card mb-3">
  <div class="card-body">
    <h5 class="card-title">Ajouter une ville</h5>
    <form class="row g-2" method="post" action="<?= BASE_URL ?>/villes/add">
      <div class="col-md-6">
        <input class="form-control" name="nom" placeholder="Nom de la ville" required>
      </div>
      <div class="col-md-4">
        <input class="form-control" name="region" placeholder="Région (optionnel)">
      </div>
      <div class="col-md-2">
        <button class="btn btn-dark w-100" type="submit">Ajouter</button>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-body">
    <h5 class="card-title">Liste</h5>
    <?php if(empty($villes)): ?>
      <div class="alert alert-secondary">Aucune ville.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-sm align-middle">
          <thead>
            <tr>
              <th>Nom</th>
              <th>Région</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($villes as $v): ?>
              <tr>
                <td>
                  <form class="row g-2" method="post" action="<?= BASE_URL ?>/villes/update/<?= (int)$v['id'] ?>">
                    <div class="col-md-6">
                      <input class="form-control form-control-sm" name="nom" value="<?= htmlspecialchars($v['nom']) ?>" required>
                    </div>
                    <div class="col-md-4">
                      <input class="form-control form-control-sm" name="region" value="<?= htmlspecialchars((string)($v['region'] ?? '')) ?>">
                    </div>
                    <div class="col-md-2">
                      <button class="btn btn-sm btn-outline-dark w-100" type="submit">OK</button>
                    </div>
                  </form>
                </td>
                <td><?= htmlspecialchars((string)($v['region'] ?? '')) ?></td>
                <td class="text-end">
                  <form method="post" action="<?= BASE_URL ?>/villes/delete/<?= (int)$v['id'] ?>" onsubmit="return confirm('Supprimer cette ville ?');" style="display:inline-block">
                    <button class="btn btn-sm btn-outline-danger" type="submit">Supprimer</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php include __DIR__ . '/../_layout_bottom.php'; ?>
