<?php $title = 'Accueil'; include __DIR__ . '/_layout_top.php'; ?>

<div class="row g-4">
  <div class="col-lg-5">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Bienvenue sur Takalo-takalo</h5>
        <p class="card-text">Plateforme d'echange d'objets entre utilisateurs.</p>
        <?php if(!$user_id): ?>
          <a class="btn btn-primary" href="<?= BASE_URL ?>/login">Se connecter</a>
          <a class="btn btn-outline-primary" href="<?= BASE_URL ?>/register">S'inscrire</a>
        <?php else: ?>
          <a class="btn btn-primary" href="<?= BASE_URL ?>/objets">Voir les objets</a>
          <a class="btn btn-outline-primary" href="<?= BASE_URL ?>/mes-objets">Gerer mes objets</a>
        <?php endif; ?>
      </div>
    </div>

    <?php if($user_id): ?>
      <div class="card mt-3">
        <div class="card-body">
          <h5 class="card-title">Recherche</h5>
          <form method="get" action="<?= BASE_URL ?>/objets" class="row g-2">
            <div class="col-12">
              <input type="text" class="form-control" name="q" placeholder="Titre..." value="<?= htmlspecialchars($q ?? '') ?>">
            </div>
            <div class="col-12">
              <select class="form-select" name="cat">
                <option value="">Toutes categories</option>
                <?php foreach($categories as $c): ?>
                  <option value="<?= (int)$c['id'] ?>" <?= ($cat === (int)$c['id']) ? 'selected' : '' ?>><?= htmlspecialchars($c['nom']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-12">
              <button class="btn btn-dark w-100" type="submit">Rechercher</button>
            </div>
          </form>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <div class="col-lg-7">
    <?php if(!$user_id): ?>
      <div class="alert alert-info">Connecte-toi pour voir les objets des autres utilisateurs.</div>
    <?php else: ?>
      <h4 class="mb-3">Objets des autres utilisateurs</h4>
      <?php if(empty($objets)): ?>
        <div class="alert alert-secondary">Aucun objet trouve.</div>
      <?php else: ?>
        <div class="list-group">
          <?php foreach($objets as $o): ?>
            <a class="list-group-item list-group-item-action" href="<?= BASE_URL ?>/objet/<?= (int)$o['id'] ?>">
              <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1"><?= htmlspecialchars($o['titre']) ?></h6>
                <small><?= number_format((float)$o['prix_estime'], 2, '.', ' ') ?> Ar</small>
              </div>
              <small class="text-muted">Categorie: <?= htmlspecialchars($o['categorie_nom']) ?> | Proprietaire: <?= htmlspecialchars($o['owner_prenom'].' '.$o['owner_nom']) ?></small>
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</div>

<?php include __DIR__ . '/_layout_bottom.php'; ?>
