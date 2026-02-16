<?php $title = 'Admin - Categories'; include __DIR__ . '/../../_layout_top.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Gestion des categories</h3>
  <a class="btn btn-outline-dark" href="<?= BASE_URL ?>/admin">Retour dashboard</a>
</div>

<div class="card mb-3">
  <div class="card-body">
    <form method="post" action="<?= BASE_URL ?>/admin/categories/add" class="row g-2">
      <div class="col-md-8">
        <input class="form-control" name="nom" placeholder="Nom categorie" required>
      </div>
      <div class="col-md-4">
        <button class="btn btn-dark w-100" type="submit">Ajouter</button>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-body">
    <?php if(empty($categories)): ?>
      <div class="alert alert-secondary">Aucune categorie.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-sm align-middle">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nom</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($categories as $c): ?>
              <tr>
                <td><?= (int)$c['id'] ?></td>
                <td>
                  <form method="post" action="<?= BASE_URL ?>/admin/categories/update/<?= (int)$c['id'] ?>" class="d-flex gap-2">
                    <input class="form-control form-control-sm" name="nom" value="<?= htmlspecialchars($c['nom']) ?>" required>
                    <button class="btn btn-sm btn-primary" type="submit">OK</button>
                  </form>
                </td>
                <td class="text-end">
                  <form method="post" action="<?= BASE_URL ?>/admin/categories/delete/<?= (int)$c['id'] ?>" onsubmit="return confirm('Supprimer?')" style="display:inline-block;">
                    <button class="btn btn-sm btn-danger" type="submit">Supprimer</button>
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

<?php include __DIR__ . '/../../_layout_bottom.php'; ?>
