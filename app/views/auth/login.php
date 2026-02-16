<?php $title = 'Login'; include __DIR__ . '/../_layout_top.php'; ?>

<div class="row justify-content-center">
  <div class="col-md-6 col-lg-5">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title mb-3">Connexion</h4>
        <?php if(!empty($error)): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post" action="<?= BASE_URL ?>/login">
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input class="form-control" type="email" name="email" value="<?= htmlspecialchars($default_email ?? '') ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Mot de passe</label>
            <input class="form-control" type="password" name="password" required>
          </div>
          <button class="btn btn-primary w-100" type="submit">Se connecter</button>
        </form>
        <div class="mt-3">
          <a href="<?= BASE_URL ?>/register">Creer un compte</a>
        </div>
      </div>
    </div>

    <div class="alert alert-secondary mt-3">
      Comptes test: jean@example.com / user123
    </div>
  </div>
</div>

<?php include __DIR__ . '/../_layout_bottom.php'; ?>
