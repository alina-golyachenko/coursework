<?php

namespace Models;

use \Core\Model;
use Core\Utils;

class Users extends \Models\Model
{

    public function validate($formRow){
        $errors = [];

        if (empty($formRow['username'])){
            $errors [] = 'Заповніть поле \"нік\"';
        }
        else if(strpos($formRow['username'], ' ')){
            $errors [] = 'Не використовуйте пробіли для ніку';
        }
        if (empty($formRow['email']) || $formRow['email'] == ' '){
            $errors [] = 'Заповніть поле \"пошта\"';
        }
        else if(strpos($formRow['email'], ' ')){
            $errors [] = 'Не використовуйте пробіли для пошти';
        }
        if ($formRow['password'] != $formRow['repeatPassword']){
            $errors [] = 'Паролі не співпадають';
        }
        if(strpos($formRow['password'], ' ') || strpos($formRow['repeatPassword'], ' ')){
            $errors [] = 'Не використовуйте пробіли у паролях';
        }

        $user = $this -> getUserByLogin($formRow['username']);
        if (!empty($user)){
            $errors[] = 'Користувач з таким ніком вже є';
        }

        $user = $this -> getUserByEmail($formRow['email']);
        if (!empty($user)){
            $errors[] = 'Користувач з такою поштою вже є';
        }

        if (count($errors) > 0){
            return $errors;
        }
        else{
            return true;
        }
    }

    public function addUser($userRow){
        $validationResult = $this -> validate($userRow);

        if (is_array($validationResult)){
            return $validationResult;
        }

        $fields = ['username', 'email', 'password', 'picture'];
        $userRowFiltered = Utils::arrayFilter($userRow, $fields);
        $userRowFiltered['password'] = md5($userRowFiltered['password']);

        $folder = 'Files/Images/Users/';

        $allowedTypes = ['image/jpg', 'image/jpeg', 'image/png'];
        if (is_file($_FILES['picture']['tmp_name']) && in_array($_FILES['picture']['type'], $allowedTypes)) {
            switch ($_FILES['picture']['type']) {

                case 'image/png' :
                    $extension = 'png';
                    break;
                case 'image/jpg' :
                    $extension = 'jpg';
                    break;
                default :
                    $extension = 'jpeg';
            }
            $name = uniqid() . '.' . $extension;
            move_uploaded_file($_FILES['picture']['tmp_name'], 'Files/Images/' . $name);


            $userRowFiltered['picture'] = 'Files/Images/' . $name;

            \Core\Core::getInstance()->getDB()->
            insert('users', $userRowFiltered);
        }
    }

    public function authUser($login, $password){
        $password = md5($password);
        $users = \Core\Core::getInstance() -> getDB() -> select('users', '*',
        [
           'email' => $login,
           'password' => $password
        ]);

        if(count($users) > 0){
            $user = $users[0];
            return $user;
        }
        else{
            return false;
        }
    }

    public function isUserAuthentificated(){
        return isset($_SESSION['user']);
    }

    public function getCurrentUser(){
        if($this -> isUserAuthentificated()){
            return $_SESSION['user'];
        }
        else{
            return null;
        }
    }

    public function getUserByLogin($login){
        $rows = \Core\Core::getInstance() ->
        getDB() -> select(
            'users', '*', ['username' => $login]);
        if (count($rows) > 0){
            return $rows[0];
        }
        else{
            return null;
        }
    }

    public function getUserByEmail($email){
        $rows = \Core\Core::getInstance() ->
        getDB() -> select(
            'users', '*', ['email' => $email]);
        if (count($rows) > 0){
            return $rows[0];
        }
        else{
            return null;
        }
    }

    public function getUserById($id){
        $rows = \Core\Core::getInstance() ->
        getDB() -> select(
            'users', '*', ['id' => $id]);
        if (count($rows) > 0){
            return $rows[0];
        }
        else{
            return null;
        }
    }


}