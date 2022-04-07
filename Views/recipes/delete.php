<link rel="stylesheet" href="..\Styles\message.css">
<link rel="stylesheet" href="..\Styles\recipes.css">

<div class="alert alert-info" role="alert">
    Ви впевнені, що хочете видалити рецепт "<?=$model['title'] ?>"?
</div>

<div style="display: flex; justify-content: center; margin-top: 40px">
<a style="background-color: #c56f82; margin-right: 20px" class="regularSection" href="\recipes\delete?id=<?=$model['id'] ?>&confirm=yes" class="card-read regularSection">Видалити</a>
<a style="background-color: #ff9ab5" class="regularSection" href="<?=$_SERVER['HTTP_REFERER']?>" class="card-read regularSection">Відмінити</a>
</div>

<!--$_SERVER['HTTP-REFERER']-->
<!--Возвращает на предыдущую страницу-->