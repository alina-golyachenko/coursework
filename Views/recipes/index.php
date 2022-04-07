<link rel="stylesheet" href="..\Styles\recipes.css">

<div class="mainSection">
    <div class="diva1">

        <a href="" class="sectionName" id="sort">Сортування</a>

        <div class="buttonSection">
            <div class="buttons">
                <input class="btna btna1 input" type="button" value="Зверху новіші">
                <input class="btna btna2 input" type="button" value="Зверху старіші">
<!--                <input class="btna btna3 input" type="button" value="Закуски">-->
<!--                <input class="btna btna4 input" type="button" value="Випічка">-->
<!--                <input class="btna btna5 input" type="button" value="Десерти">-->
<!--                <input class="btna btna6 input" type="button" value="Напої">-->
<!--                <input class="btna btna7 input" type="button" value="Інше">-->
                <script>
                    let link = document.getElementById('sort');
                    let buttons = document.getElementsByClassName('btna');
                    for (let button of buttons) {
                        button.addEventListener('click', () => {
                            link.innerHTML = button.value;
                            link.href = '\\recipes\\sort?sort=' + button.value;
                        });
                    }
                </script>

            </div>
            <div class="emptySection"></div>
        </div>
    </div>
</div></br>
<a class="regularSection" href="\recipes\add">Додати рецепт</a>



<?php
$recipeIndex = 1;
$stringIndex = '';

$userModel = new \Models\Users();
$user = $userModel -> getCurrentUser();


$recipesModel = new \Models\Recipes();


# если пользователь не аутентифицирован,
# рецепт он добавить не сможет
foreach ($lastRecipes as $recipe) : ?>
    <div class="card-container">
        <div class="card u-clearfix">
            <div class="card-body">
                <?php
                if($recipeIndex < 10) {
                     $stringIndex = '0'."{$recipeIndex}";
                }
                else{
                    $stringIndex = $recipeIndex;
                }
                $recipeIndex++;
                ?>
                <span class="card-number card-circle"><?= $stringIndex?></span>
                <span class="card-author">@<?=$userModel -> getUserById($recipe['user_id'])['username'] ?></span>
                <h2 class="card-title"><?=$recipe['title']?></h2>
                <span class="card-description"><?=$recipe['shortDescription'] ?></span>
                <a class="regularSection" href="\recipes\view?id=<?=$recipe['id'] ?>" class="card-read regularSection">Переглянути</a>
                <a class="card-tag card-circle subtle" href="\recipes\filterByCategory?category=<?=$recipe['category'] ?>"><span class="inside-circle"><?=$recipe['category'] ?></span></a>
            </div>
            <div class="card-media">
                <? if (is_file('Files/Images/'.$recipe['picture'])) : ?>
                    <img src="/Files/Images/<?=$recipe['picture']?>">
                <? endif; ?>
            </div>
        </div>
        <div class="card-shadow"></div>
    </div>
<?php endforeach;
$recipeIndex = 1; ?>