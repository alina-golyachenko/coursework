<div style="background-color: pink; width: 200px; height: 200px">

<div id="container"></div>
</div>
<?php
global $Config;

$arr = json_decode(file_get_contents('Views\recipes.txt'), true);
foreach ($arr as $recipe){
    foreach ($recipe as $piece){
        if (strpos($piece, 'show') !== false ){
            echo 'it\'s recipe!';
        }
        elseif (strpos($piece, 'jpg') !== false){
            echo 'it\'s image!';
        }
    }
}

# Ссылон на бесплатный парсер: 'https://snipp.ru/tools/parser-html';

