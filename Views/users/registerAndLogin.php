<ul class="clouds">
    <li><img src="\Files\Images\cloud.png"></li>
    <li><img src="\Files\Images\cloud.png"></li>
    <li><img src="\Files\Images\cloud.png"></li>
    <li><img src="\Files\Images\cloud.png"></li>
    <li><img src="\Files\Images\cloud.png"></li>
    <li><img src="\Files\Images\cloud.png"></li>
    <li><img src="\Files\Images\cloud.png"></li>
    <li><img src="\Files\Images\cloud.png"></li>
    <li><img src="\Files\Images\cloud.png"></li>
    <li><img src="\Files\Images\cloud.png"></li>
</ul>

<div id="addingContainer" style="margin-bottom: 120px">
        <link rel="stylesheet" href="..\Styles\registrationForm.css">
        <script async src="..\JS\registrationForm.js"></script>
        <link rel="stylesheet" type="text/css" href="slide navbar style.css">

        <div class="main">
            <input type="checkbox" id="chk" aria-hidden="true">

            <div class="register">
                <form enctype="multipart/form-data" method="post" action="\users\register">
                    <label class="mainLabel" for="chk" aria-hidden="true">Реєстрація</label>
                    <input id="username" type="text" name="username" value="<?=$_POST['username'] ?>" placeholder="нік" required="">
                    <input id="email" type="email" name="email" placeholder="пошта" required="" value="<?=$_POST['email'] ?>">
                    <input id="password" type="password" name="password" placeholder="пароль" required="">
                    <input type="password" name="repeatPassword" placeholder="повторіть пароль" required="">

<!--                    <label class="addPictureLabel" style="font-size: 15px" for="chk" aria-hidden="true">Додати фото:</label>-->
                    <input type="file" accept="image/jpg, image/png, image/jpeg" class="picture" name="picture" id="picture">


                    <button type="submit">Летс гоу</button>
                </form>
            </div>

            <div class="login">
                <form method="post" action="\users\login">
                    <label class="mainLabel" for="chk" aria-hidden="true">Вхід</label>
                    <input name="email" placeholder="пошта" required="">
                    <input type="password" name="password" placeholder="пароль" required="">
                    <button type="submit">Увійти</button>
                </form>
            </div>
        </div>
</div>

