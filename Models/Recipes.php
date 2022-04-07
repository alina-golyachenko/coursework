<?php

namespace Models;

class Recipes extends Model
{

    public function validate($row){
        $errors = [];

        if (empty($row['title'])){
            $errors [] = 'Назва не може бути порожньою';
        }
        if (empty($row['ingredients'])){
            $errors [] = 'Ви не заповнили інгредієнти';
        }
        if (empty($row['description'])){
            $errors [] = 'Не забудьте додати короткий опис';
        }
        if (empty($row['category'])){
            $errors [] = 'Категорія не може бути порожньою';
        }

        if (count($errors) > 0){
            return $errors;
        }
        else{
            return true;
        }
    }

    public function addRecipes($row){

        $userModel = new \Models\Users();
        $user = $userModel -> getCurrentUser();

        # если пользователь не аутентифицирован,
        # рецепт он добавить не сможет
        if ($user == null){
            return [
                'error' => true,
                'messages' => ['Користувач не аутентифікований']
            ];
        }

        $validationResult = $this -> validate($row);
        if (is_array($validationResult)){
            return [
                'error' => true,
                'messages' => $validationResult
            ];
        }

        $fields = ['title', 'category', 'shortDescription', 'description',
            'ingredients', 'picture'];

        $recipesRowFiltered = \Core\Utils::arrayFilter($row, $fields);

        # date() возвращает текущую дату и время
        $recipesRowFiltered['date_of_publication'] = date('Y-m-d H:i:s');
        $recipesRowFiltered['user_id'] = (int)$user['id'];
//        $recipesRowFiltered['picture'] = '...photo...';
        # 2021-01-13 10:00:00


        $id = \Core\Core::getInstance() -> getDB() ->
        insert('recipes', $recipesRowFiltered);
        return [
            'error' => false,
            'id' => $id
        ];
    }

    public function editRecipe($recipe, $id){
        $userModel = new \Models\Users();
        $user = $userModel -> getCurrentUser();

        # если пользователь не аутентифицирован,
        # рецепт он добавить не сможет
        if ($user == null){
            return false;
        }

        $validationResult = $this -> validate($recipe);

        if (is_array($validationResult)){
            return $validationResult;
        }

        $fields = ['title', 'category', 'shortDescription', 'description',
            'ingredients', 'picture'];

        $recipesRowFiltered = \Core\Utils::arrayFilter($recipe, $fields);
        # date() возвращает текущую дату и время
        # 2021-01-13 10:00:00
        \Core\Core::getInstance() -> getDB() -> update(
            'recipes', $recipesRowFiltered, ['id' => $id]
        );
        return true;
    }

    public function deleteRecipe($id){

        $userModel = new \Models\Users();
        $user = $userModel -> getCurrentUser();

        # если пользователь не аутентифицирован,
        # рецепт он добавить не сможет
        if ($user == null){
            return false;
        }

        $recipe = $this -> getRecipeById($id);
        if (($user['id'] !== $recipe['user_id'] || empty($recipe)) && $user['admin'] !== 1){
            return false;
        }

        \Core\Core::getInstance() -> getDB() -> delete(
            'recipes', ['id' => $id]
        );
        return true;
    }

    public function getLastRecipes($count){
        return \Core\Core::getInstance() -> getDB() -> select('recipes',
        '*', null, ['date_of_publication' => 'DESC'],
        $count);
    }

    public function getRecipes(){
        return \Core\Core::getInstance() -> getDB() -> select('recipes',
            '*', null, ['date_of_publication' => 'DESC']);
    }

    public function getRecipesByCategory($category){
        return \Core\Core::getInstance() -> getDB() -> select('recipes',
            '*', ['category' => $category], ['date_of_publication' => 'DESC']);
    }

    public function getRecipesByUserId($id){
        return \Core\Core::getInstance() -> getDB() -> select('recipes',
            '*', ['user_id' => $id], ['date_of_publication' => 'DESC']);
    }

    public function getSortedRecipes($sort){
        if ($sort == 'Зверху новіші'){
            return \Core\Core::getInstance() -> getDB() -> select('recipes',
                '*', null, ['date_of_publication' => 'DESC']);
        }
        elseif ($sort == 'Зверху старіші'){
            return \Core\Core::getInstance() -> getDB() -> select('recipes',
                '*', null, ['date_of_publication' => 'ASC']);
        }
    }

    public function getRecipeById($id){
        $recipes = \Core\Core::getInstance() -> getDB() -> select('recipes', '*', ['id' => $id]);
        if (!empty($recipes)){
            return $recipes[0];
        }
        else{
            return null;
        }
    }

    public function changePicture($id, $file){
        $folder = 'Files/Images/';
        $recipe = $this -> getRecipeById($id);

        if (is_file($folder.$recipe['picture']) && is_file($folder.$file)){
            unlink($folder.$recipe['picture']);
        }
        $recipe['picture'] = $file;
        $this -> editRecipe($recipe, $id);
    }

}