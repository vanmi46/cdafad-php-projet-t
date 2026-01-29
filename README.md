# cdafad-php-projet

## 1 cloner le repository (fork)

## 2 installer les dépendances :
```sh
composer install,
```
## 3 Créer la bdd avec le script db->quizz_cda.sql

## 4 Editer le fichier .env (avec vos valeurs) :
```env
DATABASE_NAME=quizz_cda
DATABASE_USERNAME=
DATABASE_PASSWORD=
DATABASE_HOST=localhost
```

## 5 Démarrer le projet avec PHP serveur :
```bash
php -S 127.0.0.1:8000 -t public
```

## 6 Ajouter les variables d'environnement suivantes :
```env
UPLOAD_DIRECTORY="assets/img/"
UPLOAD_SIZE_MAX=2097152
UPLOAD_FORMAT_WHITE_LIST='["png", "jpeg", "jpg", "webp"]'
```