<?php $title = 'Echanges'; include __DIR__ . '/../_layout_top.php'; ?>

<h3 class="mb-3">Mes echanges</h3>

<?php if(empty($echanges)): ?>
  <div class="alert alert-secondary">Aucune proposition.</div>
<?php else: ?>
  <div class="table-responsive">
    <table class="table align-middle">
      <thead>
        <tr>
          <th>ID</th>
          <th>Objet demande</th>
          <th>Objet propose</th>
          <th>Statut</th>
          <th>Role</th>
          <th class="text-end">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($echanges as $e): ?>
          <?php $isOwner = ((int)$e['proprietaire_user_id'] === (int)$user_id); ?>
          <tr>
            <td>#<?= (int)$e['id'] ?></td>
            <td><?= htmlspecialchars($e['objet_demande_titre']) ?></td>
            <td><?= htmlspecialchars($e['objet_propose_titre']) ?></td>
            <td><span class="badge text-bg-secondary"><?= htmlspecialchars($e['statut']) ?></span></td>
            <td><?= $isOwner ? 'Proprietaire' : 'Proposeur' ?></td>
            <td class="text-end">
              <?php if($isOwner && $e['statut'] === 'propose'): ?>
                <form method="post" action="<?= BASE_URL ?>/echanges/accepter/<?= (int)$e['id'] ?>" style="display:inline-block">
                  <button class="btn btn-sm btn-success" type="submit">Accepter</button>
                </form>
                <form method="post" action="<?= BASE_URL ?>/echanges/refuser/<?= (int)$e['id'] ?>" style="display:inline-block">
                  <button class="btn btn-sm btn-danger" type="submit">Refuser</button>
                </form>
              <?php else: ?>
                <span class="text-muted">-</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<?php include __DIR__ . '/../_layout_bottom.php'; ?>
