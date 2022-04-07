<?php

namespace Core;

/**
 * Класс шаблонизатора
 * @package Core
 */
class Template
{
    protected $parameters;
    public function __construct(){
        $this -> parameters = [];
    }

    public function setParam($name, $value){
        $this -> parameters[$name] = $value;
    }

    public function getParam($name){
        return $this -> parameters[$name];
    }
    
    public function setParams($array){
        foreach ($array as $key => $value) {
            $this -> parameters[$key] = $value;
        }
    }

    /**
     * Для подстановки значений во вьюшку. В качестве параметра принимает путь ко вьюшке. Возвращает код страницы с подставленными значениями
     */
    public function render($path){
        # из ассоциативного массива делает обычные переменные
        extract($this -> parameters);

        # используем управление буферизацией, чтобы вьюшка не сразу
        # отображалась, а что бы передавался именно её код
        # это позволит делать вьюшку во вьюшке
        # буфер будет хранить инфу, которая потом отобразится в браузере

        # кидаем код страницы в буфер
        ob_start();
        include ($path);

        # получаем содержимое буфера и очищаем его (без вывода)
        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }

    /**
     * Принимает код страницы и отображает её в браузере
     * @param $html
     */
    public function display($path){
        echo $this -> render($path);
    }
}