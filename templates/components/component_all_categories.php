<?php $categories = $data["categories"] ?? []; ?>
<select name="categories[]" multiple>
    <?php if (empty($categories)) : ?>
        <option disabled>Aucune categorie disponible</option>
    <?php else : ?>
        <?php foreach ($categories as $category) : ?>
            <option value="<?= $category->getId() ?>"><?= $category->getName() ?></option>
        <?php endforeach ?>
    <?php endif ?>
</select>
