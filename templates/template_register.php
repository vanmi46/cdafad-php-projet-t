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
        <form action="" method="post" enctype="multipart/form-data">
            <?php $errors = $data["errors"] ?? []; ?>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)($data["csrf_token"] ?? ""), ENT_QUOTES) ?>">
            <h1>Ajouter un compte</h1>
            <?php if (!empty($errors["_form"])) : ?>
                <p class="error"><?= htmlspecialchars((string)$errors["_form"], ENT_QUOTES) ?></p>
            <?php endif; ?>
            <input type="text" name="firstname" placeholder="Saisir votre prénom">
            <small class="error"><?= htmlspecialchars((string)($errors["firstname"] ?? ""), ENT_QUOTES) ?></small>
            <input type="text" name="lastname" placeholder="Saisir votre nom">
            <small class="error"><?= htmlspecialchars((string)($errors["lastname"] ?? ""), ENT_QUOTES) ?></small>
            <input type="text" name="pseudo" placeholder="Saisir votre pseudo">
            <small class="error"><?= htmlspecialchars((string)($errors["pseudo"] ?? ""), ENT_QUOTES) ?></small>
            <input type="email" name="email" placeholder="Saisir votre email">
            <small class="error"><?= htmlspecialchars((string)($errors["email"] ?? ""), ENT_QUOTES) ?></small>
            <input type="password" name="password" placeholder="Saisir votre mot de passe">
            <small class="error"><?= htmlspecialchars((string)($errors["password"] ?? ""), ENT_QUOTES) ?></small>
            <input type="password" name="confirm-password" placeholder="Confirmer votre mot de passe">
            <small class="error"><?= htmlspecialchars((string)($errors["confirm-password"] ?? ""), ENT_QUOTES) ?></small>
            <input type="file" name="img">
            <small class="error"><?= htmlspecialchars((string)($errors["img"] ?? ""), ENT_QUOTES) ?></small>
            <input type="submit" value="Inscription" name="submit">
            <p class="success"><?= htmlspecialchars((string)($data["msg"] ?? ""), ENT_QUOTES) ?></p>
        </form>
    </main>
    <!-- Import du footer -->
    <?php include 'components/component_footer.php'; ?>
</body>
</html>
