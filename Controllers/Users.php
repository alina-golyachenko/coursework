<?php

namespace Controllers;

use Core\Controller;

class Users extends Controller
{
    protected  $usersModel;
    function __construct(){
        $this -> usersModel = new \Models\Users();
    }

    public function actionProfile(){
        if(!isset($_SESSION['user'])){
            header( "Location: /users/register" );

        }
        else{
            return $this -> render('profile', null, [
                'MainTitle' => 'Мій профіль'
            ]);
        }
    }

    function actionLogout(){
        header( "refresh:1;url=\\recipes" );

        unset($_SESSION['user']);
        return $this -> renderMessage('ok', 'Ви вийшли з акаунту', null,
            [
                'MainTitle' => 'Witch\'s Kitchen'
            ]);
    }

    function actionLogin(){
        $mainTitle = 'Witch\s Kitchen';

        if(isset($_SESSION['user'])){
            header( "refresh:1;url=\\" );
            return $this -> renderMessage('ok', 'Ви вже увійшли на сайт!', null,
                [
                    'MainTitle' => $mainTitle
                ]);
        }

        if ($this -> isPost()){

            $login = $_POST['email'];
            $password = $_POST['password'];

            $user = $this -> usersModel -> authUser($login, $password);

            if (!empty($user)){

                $_SESSION['user'] = $user;

                $result = $this -> renderMessage('ok', 'Ви успішно аутентифіковані!', null,
                [
                    'MainTitle' => $mainTitle
                ]);

                header( "refresh:1;url=\\recipes" );

                return $result;

            }
            else{
                return $this -> render('registerAndLogin', null, [
                    'MainTitle' => 'Вхід/реєстрація',
                    'MessageText' => 'Неправильний логін або пароль',
                    'MessageClass' => 'danger'
                ]);
            }

        }
        else{
            $params = [
                'MainTitle' => 'Вхід/реєстрація'
            ];
            return $this -> render('registerAndLogin', null, $params);
        }
    }

    function actionRegister(){

        if ($this -> isPost()){

            $result = $this -> usersModel -> addUser($_POST);

            if ($result === true){
                $result = $this -> renderMessage('ok', 'Користувач успішно зареєстрований', null,
                    [
                        'MainTitle' => 'Реєстрація'
                    ]);

                header( "refresh:1;url=\\recipes" );

                return $result;

            }
            else{
                $message = implode('<br>', $result);
                return $this -> render('registerAndLogin', null, [
                    'MainTitle' => 'Реєстрація',
                    'MessageText' => $message,
                    'MessageClass' => 'danger'
                ]);
            }
        }
        else{
            $params = [
                'MainTitle' => 'Реєстрація'
            ];
            return $this -> render('registerAndLogin', null, $params);
        }


    }
}