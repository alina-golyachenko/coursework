<?php

namespace Controllers;

use Core\Controller;

class Recipes extends Controller
{
    # позволит создать поле, где будет храниться
    # модель users

    protected $user;
    protected $recipesModel;
    protected $usersModel;
    public function __construct()
    {
        $this -> $usersModel = new \Models\Users();
        $this -> recipesModel = new \Models\Recipes();
        $this -> user = $this -> $usersModel -> getCurrentUser();
    }

    /**
     * Будет отображать список рецептов
     */
    public function actionIndex(){
        $mainTitle = 'Рецепти';
        global $Config;
        $lastRecipes = $this ->recipesModel -> getLastRecipes($Config['RecipesCount']);
        return $this -> render('index', [
            'lastRecipes' => $lastRecipes
        ],
        [
           'MainTitle' => $mainTitle
        ]);
    }

    /**
     * Для просмотра определённого рецепта
     */

    public function actionView(){
        $id = $_GET['id'];
        $recipe = $this -> recipesModel -> getRecipeById($id);

        return $this -> render('view', ['model' => $recipe],
            [
            'MainTitle' => $recipe['title']
        ]);
    }

    /**
     * Проверка, не было ли добавлено постороннего
     * файла вместо картинки
     */

    public function checkPicture($id){
        $allowedTypes = ['image/jpg', 'image/jpeg', 'image/png'];
        if (is_file($_FILES['picture']['tmp_name']) && in_array($_FILES['picture']['type'], $allowedTypes)){
            switch ($_FILES['picture']['type']){

                case 'image/png' :
                    $extension = 'png';
                    break;
                case 'image/jpg' :
                    $extension = 'jpg';
                    break;
                default :
                    $extension = 'jpeg';
            }
            $name = $id.'_'.uniqid().'.'.$extension;
            move_uploaded_file($_FILES['picture']['tmp_name'], 'Files/Images/'.$name);
            $this -> recipesModel -> changePicture($id, $name);
        }
    }

    /**
     * Добавление рецепта
     */

    public function actionAdd(){

        $mainTitle = 'Додати рецепт';

        if (empty($this -> user)){

            return header( "refresh:0;url=\\users\\login" );
        }

        if ($this -> isPost()){

            $result = $this -> recipesModel -> addRecipes($_POST);
            if ($result['error'] === false){

                $this->checkPicture($result['id']);
                header( "refresh:1;url=\\recipes" );
                return $this -> renderMessage('ok', 'Рецепт успішно створено', null,
                    [
                        'MainTitle' => $mainTitle
                    ]
                );

            }
            else{
                $message = implode('</br>', $result['messages']);
                return $this -> render('form',
                [
                    // model - это откуда берутся данные
                    'model' => $_POST,
                    'PageTitle' => 'Додати рецепт'
                ],
                [
                   'MainTitle' => $mainTitle,
                   'MessageText' => $message,
                   'MessageClass' => 'danger'
                ]);
            }

        }
        else{
            $params = [
                'MainTitle' => $mainTitle
            ];
            return $this -> render('form', [
                'PageTitle' => 'Додати рецепт'
            ], $params);
        }

    }



    /**
     * Редактирование рецепта
     */

    public function actionEdit()
    {
        $id = $_GET['id'];
        $recipe = $this->recipesModel->getRecipeById($id);

        if (empty($this->user)) {

            return header("refresh:0;url=\\users\\login");
        }


        if ($this->isPost()) {
            $result = $this->recipesModel->editRecipe($_POST, $id);
            if ($result === true) {
                $this->checkPicture($id);
                header("refresh:2;url=\\recipes\\view?id={$recipe['id']}");
                return $this->renderMessage('ok', 'Рецепт успішно збережено', [
                    'PageTitle' => 'Редагувати рецепт'
                ],
                    [
                        'MainTitle' => $recipe['title']
                    ]);
            }
            else{
                $message = implode('<br/>', $result);
                return $this -> render('form', ['model' => $recipe],
                [
                    'MainTitle' => $recipe['title'],
                    'MessageText' => $message,
                    'MessageClass' => 'danger'
                ]);

            }
        }
        else {

return $this->render('form',
            [
                'model' => $recipe,
                'PageTitle' => 'Редагувати рецепт'
            ],
            [
                'MainTitle' => 'Редагувати рецепт'
            ]);
        }
    }

    /**
     * Удаление рецепта
     */

    public function actionDelete(){

        $id = $_GET['id'];
        if (isset($_GET['confirm']) && $_GET['confirm'] == 'yes'){
            if($this -> recipesModel -> deleteRecipe($id)){
                header('Location:\\recipes\\');
            }
            else{
                $recipe = $this -> recipesModel -> getRecipeById($id);
                return $this -> render('delete', ['model' => $recipe],
                    [
                        'MainTitle' => 'Видалити рецепт',
                        'MessageText' => 'Помилка видалення рецепта',
                        'MessageClass' => 'danger'
                    ]);
            }
        }
        $recipe = $this -> recipesModel -> getRecipeById($id);
        return $this -> render('delete', [
            'model' => $recipe
        ], [
            'MainTitle' => 'Видалення рецепту'
        ]);
    }

    public function actionFilterByCategory(){
        if (isset($_GET['category'])){
            $category = $_GET['category'];
            if($this -> recipesModel -> getRecipesByCategory($category)){
                $recipes = $this -> recipesModel -> getRecipesByCategory($category);
                return $this -> render('index', [
                    'lastRecipes' => $recipes
        ], [
                    'MainTitle' => 'Усі рецепти'
                ]);
            }
            else {
                $mainTitle = 'Рецепти';
                global $Config;
                $lastRecipes = $this->recipesModel->getLastRecipes($Config['RecipesCount']);
                return $this->render('index', [
                    'lastRecipes' => $lastRecipes
                ],
                    [
                        'MainTitle' => $mainTitle
                    ]);
            }
        }
//        $recipe = $this -> recipesModel -> getRecipeById($id);

    }

    public function actionFilterByUser(){
        if (isset($_GET['user_id'])){
            $id = $_GET['user_id'];
            if($this -> recipesModel -> getRecipesByUserId($id)){
                $recipes = $this -> recipesModel -> getRecipesByUserId($id);
                return $this -> render('index', [
                    'lastRecipes' => $recipes
                ], [
                    'MainTitle' => 'Рецепти юзерів'
                ]);
            }
            else {
                $mainTitle = 'Рецепти';
                global $Config;
                $lastRecipes = $this->recipesModel->getLastRecipes($Config['RecipesCount']);
                return $this->render('index', [
                    'lastRecipes' => $lastRecipes
                ],
                    [
                        'MainTitle' => $mainTitle
                    ]);
            }
        }
//        $recipe = $this -> recipesModel -> getRecipeById($id);

    }

    public function actionSort(){
        if (isset($_GET['sort'])){
            $sort = $_GET['sort'];
            if($this -> recipesModel -> getSortedRecipes($sort)){
                $recipes = $this -> recipesModel -> getSortedRecipes($sort);
                return $this -> render('index', [
                    'lastRecipes' => $recipes
                ], [
                    'MainTitle' => 'Усі рецепти'
                ]);
            }
            else {
                $mainTitle = 'Рецепти';
                global $Config;
                $lastRecipes = $this->recipesModel->getLastRecipes($Config['RecipesCount']);
                return $this->render('index', [
                    'lastRecipes' => $lastRecipes
                ],
                    [
                        'MainTitle' => $mainTitle
                    ]);
            }
        }
//        $recipe = $this -> recipesModel -> getRecipeById($id);

    }



}