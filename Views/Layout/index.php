<?php $userModel = new \Models\Users() ?>

<!DOCTYPE html>
<html>
<head>
    <link rel="shortcut icon" href="Files\Icons\title_Icon.ico" />
    <link rel="stylesheet" href="..\Styles\template.css">
    <link rel="preload" href="Styles\Minecraft11.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="..\Styles\message.css">
    <link rel="stylesheet" href="..\Styles\footer.css">

    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
    <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css"
    />

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
    <title><?=$MainTitle ?></title>
</head>
<body>
<nav class="stroke fill">
    <ul>
        <li><a href="/recipes">Witch's Kitchen</a></li>
        <li class="hidenavli"><a href="/contacts">Контакти</a></li>
        <? if (!$userModel -> IsUserAuthentificated()) :?>
        <li class="hidenavli"><a href="/users/login">Вхід/реєстрація</a></li>
        <? else : ?>

        <li class="hidenavli logout-button"><a href="/users/logout">Вийти</a></li>
        <? endif; ?>
    </ul>
<!--    <span class="username">--><?//=$userModel -> getCurrentUser()['username']?><!--</span>-->
    <? if ($userModel -> IsUserAuthentificated()) :?>
    <a href="\users\profile" class="nav-button"><img src="..\Files\Icons\stars.png">
    <? endif; ?>
    </a>

    <!-- Добавить дропдаун-меню с профилем -->
<!--    <span style="display: inline-block; float: right">--><?//=$userModel -> getCurrentUser()['email'] ?><!--</span>-->
</nav>

<div>
    <? if(!empty($MessageText)) :  ?>

        <div style="margin-bottom: 70px" class="alert alert-<?= $MessageClass?>" ?>
            <?= $MessageText?>
        </div>

<? endif; ?>

</div>

<?=$PageContent ?>

<footer class="footer-distributed"">
    <div class="footer-right">
<!--        <a href="#"></a>-->
<!--        <a href="#"></a>-->
        <a href="https://gitlab.com/alina_golyachenko1/javascript/coursework">
            GitLab
        </a>

    </div>
    <p class="companyName">Company Name &copy; 2015</p>

</footer>
</body>

</html>
