<?php $title = 'Admin Login'; include __DIR__ . '/../_layout_top.php'; ?>

<div class="row justify-content-center">
  <div class="col-md-6 col-lg-5">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title mb-3">Connexion Admin</h4>
        <?php if(!empty($error)): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post" action="<?= BASE_URL ?>/admin/login">
          <div class="mb-3">
            <label class="form-label">Username</label>
            <input class="form-control" name="username" value="<?= htmlspecialchars($default_username ?? 'admin') ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Mot de passe</label>
            <input class="form-control" type="password" name="password" required>
          </div>
          <button class="btn btn-dark w-100" type="submit">Se connecter</button>
        </form>
      </div>
    </div>

    <div class="alert alert-secondary mt-3">Compte admin: admin / admin123</div>
  </div>
</div>

<?php include __DIR__ . '/../_layout_bottom.php'; ?>
