<html lang="en">
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
            <h1>Ajouter un compte</h1>
            <input type="text" name="firstname" placeholder="Saisir votre prénom">
            <input type="text" name="lastname" placeholder="Saisir votre nom">
            <input type="text" name="pseudo" placeholder="Saisir votre pseudo">
            <input type="email" name="email" placeholder="Saisir votre email">
            <input type="password" name="password" placeholder="Saisir votre mot de passe">
            <input type="password" name="confirm-password" placeholder="Confirmer votre mot de passe">
            <input type="submit" value="Inscription" name="submit">
            <p><?= $data["msg"] ?? ""  ?></p>
        </form>
    </main>
    <!-- Import du footer -->
    <?php include 'components/component_footer.php'; ?>
</body>
</html>
