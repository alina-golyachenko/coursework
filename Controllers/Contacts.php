<?php

namespace Controllers;

class Contacts extends \Core\Controller
{
    public function actionIndex(){
        return $this -> render('index', null, [
            'MainTitle' => 'Witch\'s Kitchen']);
    }

}