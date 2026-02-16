<?php $title = 'Admin'; include __DIR__ . '/../_layout_top.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Dashboard</h3>
  <a class="btn btn-outline-dark" href="<?= BASE_URL ?>/admin/categories">Categories</a>
</div>

<div class="row g-3">
  <div class="col-md-6">
    <div class="card">
      <div class="card-body">
        <div class="text-muted">Nombre d'utilisateurs inscrits</div>
        <div class="display-6"><?= (int)$nb_users ?></div>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card">
      <div class="card-body">
        <div class="text-muted">Nombre d'echanges effectues</div>
        <div class="display-6"><?= (int)$nb_echanges ?></div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../_layout_bottom.php'; ?>
