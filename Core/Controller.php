<?php

namespace Core;

/**
 * Базовый класс для всех контроллеров
 * @package Core
 */
class Controller
{
    public function isPost(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            return true;
        }
        else{
            return false;
        }
    }

    public function isGet(){
        if ($_SERVER['REQUEST_METHOD'] == 'GET'){
            return true;
        }
        else{
            return false;
        }
    }

    public function postFilter($fields){
        return Utils::arrayFilter($_POST, $fields);
    }

    public function render($viewName, $localParams = null, $globalParams = null){
        # localParams это в текущую страничку,
        # globalParams подставятся в глобальный шаблон

        $template = new Template();
        if (is_array($localParams)){
            $template -> setParams($localParams);
        }
        if (!is_array($globalParams)){
            $globalParams = [];
        }

        # getName() возвращает имя класса вместе с неймспейсом
        # ReflectionClass даёт информацию о классе

        $moduleName = strtolower((new \ReflectionClass($this)) -> getShortName());
        $globalParams['PageContent'] = $template -> render("Views/{$moduleName}/{$viewName}.php");
        return $globalParams;
    }

    public function renderMessage($type, $message, $localParams = null, $globalParams = null){

        $template = new Template();
        if (is_array($localParams)){
            $template -> setParams($localParams);
        }

        $template -> setParam('MessageText', $message);

        switch ($type){
            case 'ok':
                $template -> setParam('MessageClass', 'success');
                break;
            case 'error':
                $template -> setParam('MessageClass', 'danger');
                break;
            case 'info':
                $template -> setParam('MessageClass', 'info');
                break;
        }

        if (!is_array($globalParams)){
            $globalParams = [];
        }

        # getName() возвращает имя класса вместе с неймспейсом
        # ReflectionClass даёт информацию о классе

        $globalParams['PageContent'] = $template -> render("Views/Layout/message.php");
        return $globalParams;
    }


}