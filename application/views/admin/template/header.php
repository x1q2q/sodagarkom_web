<!DOCTYPE html>
<html :class="{ 'theme-dark': dark }" x-data="data()" lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $title; ?></title>
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
      rel="stylesheet" />
      
    <link rel="stylesheet" href="<?= base_url('assets'); ?>/css/tailwind.output.css" />
    <link rel="stylesheet" href="<?= base_url('assets'); ?>/css/custom.css" />

    <script
      src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"
      defer></script>
    <script src="<?= base_url('assets'); ?>/js/init-alpine.js"></script>
    <script src="<?= base_url('assets'); ?>/js/script.js"></script>
  </head>

