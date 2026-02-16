<?php $title = 'Objets'; include __DIR__ . '/../_layout_top.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Objets des autres utilisateurs</h3>
  <a class="btn btn-outline-primary" href="<?= BASE_URL ?>/mes-objets">Mes objets</a>
</div>

<div class="card mb-3">
  <div class="card-body">
    <form method="get" action="<?= BASE_URL ?>/objets" class="row g-2">
      <div class="col-md-6">
        <input type="text" class="form-control" name="q" placeholder="Titre..." value="<?= htmlspecialchars($q ?? '') ?>">
      </div>
      <div class="col-md-4">
        <select class="form-select" name="cat">
          <option value="">Toutes categories</option>
          <?php foreach($categories as $c): ?>
            <option value="<?= (int)$c['id'] ?>" <?= ($cat === (int)$c['id']) ? 'selected' : '' ?>><?= htmlspecialchars($c['nom']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2">
        <button class="btn btn-dark w-100" type="submit">Chercher</button>
      </div>
    </form>
  </div>
</div>

<?php if(empty($objets)): ?>
  <div class="alert alert-secondary">Aucun objet trouve.</div>
<?php else: ?>
  <div class="row g-3">
    <?php foreach($objets as $o): ?>
      <div class="col-md-6">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($o['titre']) ?></h5>
            <div class="text-muted mb-2">Categorie: <?= htmlspecialchars($o['categorie_nom']) ?></div>
            <div class="mb-2">Prix: <strong><?= number_format((float)$o['prix_estime'], 2, '.', ' ') ?> Ar</strong></div>
            <div class="text-muted">Proprietaire: <?= htmlspecialchars($o['owner_prenom'].' '.$o['owner_nom']) ?></div>
          </div>
          <div class="card-footer bg-white">
            <a class="btn btn-primary w-100" href="<?= BASE_URL ?>/objet/<?= (int)$o['id'] ?>">Voir</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php include __DIR__ . '/../_layout_bottom.php'; ?>
