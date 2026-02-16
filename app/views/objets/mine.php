<?php $title = 'Mes objets'; include __DIR__ . '/../_layout_top.php'; ?>

<h3 class="mb-3">Mes objets</h3>

<div class="card mb-4">
  <div class="card-body">
    <h5 class="card-title">Ajouter un objet</h5>
    <form method="post" action="<?= BASE_URL ?>/mes-objets/add" enctype="multipart/form-data" class="row g-2">
      <div class="col-md-4">
        <label class="form-label">Categorie</label>
        <select class="form-select" name="categorie_id" required>
          <option value="">Choisir...</option>
          <?php foreach($categories as $c): ?>
            <option value="<?= (int)$c['id'] ?>"><?= htmlspecialchars($c['nom']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Titre</label>
        <input class="form-control" name="titre" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Prix estimatif (Ar)</label>
        <input class="form-control" name="prix_estime" type="number" step="0.01" value="0">
      </div>
      <div class="col-12">
        <label class="form-label">Description</label>
        <textarea class="form-control" name="description" rows="2"></textarea>
      </div>
      <div class="col-12">
        <label class="form-label">Photos (1 ou plusieurs)</label>
        <input class="form-control" name="photos[]" type="file" multiple accept="image/*">
      </div>
      <div class="col-12">
        <button class="btn btn-primary" type="submit">Ajouter</button>
      </div>
    </form>
  </div>
</div>

<?php if(empty($objets)): ?>
  <div class="alert alert-secondary">Tu n'as pas encore d'objets.</div>
<?php else: ?>
  <div class="accordion" id="accMine">
    <?php foreach($objets as $idx => $o): ?>
      <div class="accordion-item">
        <h2 class="accordion-header" id="h<?= (int)$o['id'] ?>">
          <button class="accordion-button <?= $idx>0 ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#c<?= (int)$o['id'] ?>">
            <?= htmlspecialchars($o['titre']) ?>
            <span class="ms-2 badge text-bg-secondary"><?= htmlspecialchars($o['categorie_nom']) ?></span>
          </button>
        </h2>
        <div id="c<?= (int)$o['id'] ?>" class="accordion-collapse collapse <?= $idx===0 ? 'show' : '' ?>" data-bs-parent="#accMine">
          <div class="accordion-body">
            <form method="post" action="<?= BASE_URL ?>/mes-objets/update/<?= (int)$o['id'] ?>" enctype="multipart/form-data" class="row g-2">
              <div class="col-md-4">
                <label class="form-label">Categorie</label>
                <select class="form-select" name="categorie_id" required>
                  <?php foreach($categories as $c): ?>
                    <option value="<?= (int)$c['id'] ?>" <?= ((int)$c['id']===(int)$o['categorie_id'])?'selected':'' ?>><?= htmlspecialchars($c['nom']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-4">
                <label class="form-label">Titre</label>
                <input class="form-control" name="titre" value="<?= htmlspecialchars($o['titre']) ?>" required>
              </div>
              <div class="col-md-4">
                <label class="form-label">Prix estimatif (Ar)</label>
                <input class="form-control" name="prix_estime" type="number" step="0.01" value="<?= htmlspecialchars((string)$o['prix_estime']) ?>">
              </div>
              <div class="col-12">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="2"><?= htmlspecialchars($o['description'] ?? '') ?></textarea>
              </div>
              <div class="col-12">
                <label class="form-label">Ajouter des photos</label>
                <input class="form-control" name="photos[]" type="file" multiple accept="image/*">
              </div>
              <div class="col-12 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Enregistrer</button>
              </div>
            </form>

            <form method="post" action="<?= BASE_URL ?>/mes-objets/delete/<?= (int)$o['id'] ?>" onsubmit="return confirm('Supprimer cet objet?')" class="mt-3">
              <button class="btn btn-danger" type="submit">Supprimer</button>
            </form>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php include __DIR__ . '/../_layout_bottom.php'; ?>
