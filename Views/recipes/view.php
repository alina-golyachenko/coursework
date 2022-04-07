<link rel="stylesheet" href="..\Styles\recipes.css">

<?php
$stringIndex = '';
$userModel = new \Models\Users();
$user = $userModel -> getCurrentUser();
$recipesModel = new \Models\Recipes();
$recipe = $recipesModel -> getRecipeById($_GET['id']);

$canEdit = false;
if ($recipe['user_id'] !== $user['id'] && $user['admin'] != 1) {
    $canEdit = false;
}
else{
    $canEdit = true;
}

?>
<div class="card-container">
    <div class="card u-clearfix">
        <div class="card-body">
            <span class="card-author">@<?=$userModel -> getUserById($recipe['user_id'])['username'] ?></span>
            <h2 class="card-title"><?=$recipe['title'] ?></h2>
            <span class="card-description">
                <h2>Інгредієнти:</h2>
            <?=$recipe['ingredients'] ?>
                <h2>Приготування:</h2>
                <?=$recipe['description'] ?>
            </span>
            <?php if ($canEdit) : ?>
            <a class="regularSection" href="\recipes\edit?id=<?=$recipe['id'] ?>" class="card-read regularSection">Редагувати</a>
            <a class="regularSection" href="\recipes\delete?id=<?=$recipe['id'] ?>" class="card-read regularSection">Видалити</a>
            <?php endif; ?>
            <a class="card-tag card-circle subtle" href="\recipes\filterByCategory?category=<?=$recipe['category'] ?>"><span class="inside-circle"><?=$recipe['category'] ?></span></a>
        </div>
        <div class="card-media">
            <? if (is_file('Files/Images/'.$recipe['picture'])) : ?>
                <img data-fancybox="gallery" src="/Files/Images/<?=$recipe['picture']?>">
            <? endif; ?>
        </div>
    </div>

    <div class="card-shadow"></div>
</div>