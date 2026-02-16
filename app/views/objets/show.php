<?php $title = 'Fiche objet'; include __DIR__ . '/../_layout_top.php'; ?>

<div class="row g-4">
  <div class="col-lg-7">
    <div class="card">
      <div class="card-body">
        <h3 class="card-title mb-1"><?= htmlspecialchars($objet['titre']) ?></h3>
        <div class="text-muted mb-2">Categorie: <?= htmlspecialchars($objet['categorie_nom']) ?></div>
        <div class="mb-2">Prix estime: <strong><?= number_format((float)$objet['prix_estime'], 2, '.', ' ') ?> Ar</strong></div>
        <div class="text-muted mb-3">Proprietaire actuel: <?= htmlspecialchars($objet['owner_prenom'].' '.$objet['owner_nom']) ?></div>

        <?php if(!empty($objet['description'])): ?>
          <p><?= nl2br(htmlspecialchars($objet['description'])) ?></p>
        <?php endif; ?>

        <?php if(!empty($photos)): ?>
          <div class="row g-2">
            <?php foreach($photos as $p): ?>
              <div class="col-6 col-md-4">
                <img class="img-fluid rounded border" src="<?= BASE_URL . htmlspecialchars($p['file_path']) ?>" alt="photo">
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="alert alert-secondary">Aucune photo.</div>
        <?php endif; ?>
      </div>
    </div>

    <div class="card mt-3">
      <div class="card-body">
        <h5 class="card-title">Historique des proprietaires</h5>
        <?php if(empty($history)): ?>
          <div class="alert alert-secondary">Aucun historique.</div>
        <?php else: ?>
          <ul class="list-group list-group-flush">
            <?php foreach($history as $h): ?>
              <li class="list-group-item d-flex justify-content-between">
                <span><?= htmlspecialchars($h['prenom'].' '.$h['nom']) ?></span>
                <span class="text-muted"><?= htmlspecialchars($h['acquired_at']) ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="col-lg-5">
    <?php if((int)$objet['user_id'] === (int)$user_id): ?>
      <div class="alert alert-info">Ceci est ton objet. Tu ne peux pas proposer un echange sur ton objet.</div>
    <?php else: ?>
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Proposer un echange</h5>
          <?php if(empty($mes_objets)): ?>
            <div class="alert alert-secondary">Ajoute d'abord un objet dans "Mes objets".</div>
          <?php else: ?>
            <form method="post" action="<?= BASE_URL ?>/echanges/proposer">
              <input type="hidden" name="objet_demande_id" value="<?= (int)$objet['id'] ?>">
              <div class="mb-3">
                <label class="form-label">Choisir mon objet</label>
                <select class="form-select" name="objet_propose_id" required>
                  <?php foreach($mes_objets as $o): ?>
                    <option value="<?= (int)$o['id'] ?>"><?= htmlspecialchars($o['titre']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <button class="btn btn-primary w-100" type="submit">Envoyer la proposition</button>
            </form>
          <?php endif; ?>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php include __DIR__ . '/../_layout_bottom.php'; ?>
