<link rel="stylesheet" href="..\Styles\profile.css">

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

<div class="container">
    <div class="cover-photo">
        <img data-fancybox="gallery"  src="\<?=$user['picture']?>" class="profile">
    </div>
    <div class="profile-name">@<?=$user['username']?></div>
<!--    <p class="about">User Interface Designer and<br>front-end developer</p>-->
    <a href="\recipes\filterByUser?user_id=<?=$user['id'] ?>" class="recipesButton">Рецепти</a>
<!--    <button class="follow-btn">Following</button>-->
    <div>
        <i class="fab fa-facebook-f"></i>
        <i class="fab fa-instagram"></i>
        <i class="fab fa-youtube"></i>
        <i class="fab fa-twitter"></i>
    </div>
</div>