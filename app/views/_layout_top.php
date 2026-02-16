<?php
use app\utils\Auth;
$title = $title ?? 'Takalo-takalo';
$userId = Auth::userId();
$adminId = Auth::adminId();
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/css/takalo.css?v=2" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark tk-navbar">
  <div class="container">
    <a class="navbar-brand tk-brand" href="<?= BASE_URL ?>/">Takalo-takalo</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbars" aria-controls="navbars" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbars">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php if($userId): ?>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/objets">Objets</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/mes-objets">Mes objets</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/echanges">Echanges</a></li>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav ms-auto">
        <?php if($adminId): ?>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin">Admin</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/logout">Logout admin</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/login">Admin</a></li>
        <?php endif; ?>

        <?php if($userId): ?>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/logout">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/login">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/register">Inscription</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<main class="container py-4">
