<?php

namespace Controllers;

use Core\Controller;
use Core\DB;

/**
 * Контроллер для главной страницы сайта
 */
class Main extends Controller
{
    public function actionIndex(){
        return $this -> render('index', null, [
            'MainTitle' => 'Witch\'s Kitchen']);
    }

}