<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <link rel="stylesheet" href="assets/style/main.css">
    <title><?= $title ?? "" ?></title>
</head>
<body>
     <!-- Import du menu -->
    <?php include 'components/component_navbar.php'; ?>
    <main class="container-fluid">
        <form action="" method="post">
            <?php $errors = $data["errors"] ?? []; ?>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)($data["csrf_token"] ?? ""), ENT_QUOTES) ?>">
            <h1>Ajouter une categorie : </h1>
            <?php if (!empty($errors["_form"])) : ?>
                <p class="error"><?= htmlspecialchars((string)$errors["_form"], ENT_QUOTES) ?></p>
            <?php endif; ?>
            <input type="text" name="name" placeholder="Saisir le nom de la categorie">
            <small class="error"><?= htmlspecialchars((string)($errors["name"] ?? ""), ENT_QUOTES) ?></small>
            <input type="submit" value="Ajouter" name="submit">
            <p class="success"><?= htmlspecialchars((string)($data["msg"] ?? ""), ENT_QUOTES) ?></p>
        </form>
    </main>
    <!-- Import du footer -->
    <?php include 'components/component_footer.php'; ?>
</body>
</html>
