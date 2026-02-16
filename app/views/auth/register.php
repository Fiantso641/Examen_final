<?php $title = 'Inscription'; include __DIR__ . '/../_layout_top.php'; ?>

<div class="row justify-content-center">
  <div class="col-md-7 col-lg-6">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title mb-3">Inscription</h4>
        <?php if(!empty($error)): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post" action="<?= BASE_URL ?>/register">
          <div class="row g-2">
            <div class="col-md-6">
              <label class="form-label">Nom</label>
              <input class="form-control" name="nom" value="<?= htmlspecialchars($old['nom'] ?? '') ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Prenom</label>
              <input class="form-control" name="prenom" value="<?= htmlspecialchars($old['prenom'] ?? '') ?>" required>
            </div>
          </div>
          <div class="mb-3 mt-2">
            <label class="form-label">Email</label>
            <input class="form-control" type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Mot de passe</label>
            <input class="form-control" type="password" name="password" required>
          </div>
          <button class="btn btn-primary w-100" type="submit">S'inscrire</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../_layout_bottom.php'; ?>
