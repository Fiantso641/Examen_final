<?php
$title = $title ?? 'BNGRC - Dons';
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
    <a class="navbar-brand tk-brand" href="<?= BASE_URL ?>/dashboard">BNGRC - Dons</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbars" aria-controls="navbars" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbars">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/dashboard">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/villes">Villes</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/besoins">Besoins</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/dons">Dons</a></li>
      </ul>
    </div>
  </div>
</nav>

<main class="container py-4">
