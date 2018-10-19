<!DOCTYPE HTML>
<html>
    <head>
        <title>Установка модуля SimpleSEO</title>
        <link rel="stylesheet" type="text/css" href="http://store.alaev.info/style.css" />
        <style type="text/css">
            #header {width: 100%; text-align: center;}
            .module_image {float: left; margin: 0 15px 15px 0;}
            .box-cnt{width: 100%; overflow: hidden;}
        </style>
    </head>

    <body>
        <div class="wrap">
            <div id="header">
                <h1>SimpleSEO</h1>
            </div>
            <div class="box">
                <div class="box-t">&nbsp;</div>
                <div class="box-c">
                    <div class="box-cnt">
                        <?php

                            $output = module_installer();
                            echo $output;

                        ?>
                    </div>
                </div>
                <div class="box-b">&nbsp;</div>
            </div>
        </div>
    </body>
</html>

<?php

    function module_installer()
    {
        // Стандартный текст
        $output = '<h2>Добро пожаловать в установщик модуля SimpleSEO!</h2>';
        $output .= '<img class="module_image" src="/engine/skins/images/siseo.png" />';
        $output .= '<p><strong>Внимание!</strong> После установки модуля <strong>обязательно</strong> удалите файл <strong>siseo_installer.php</strong> с Вашего сервера!</p>';

        // Если через $_POST передаётся параметр siseo_install, производим инсталляцию, согласно параметрам
        if(!empty($_POST['siseo_install']))
        {
            // Подключаем config
            include_once ('engine/data/config.php');

            // Подключаем DLE API
            include ('engine/api/api.class.php');

            // Устанавливаем модуль в админку
            $dle_api->install_admin_module('siseo', 'SimpleSEO - это же элементарно, Ватсон!', 'Модуль позволяет настраивать все элементы и мета-теги, необходимые для успешной оптимизации. И еще чуть-чуть...', 'siseo.png');

            // Вывод
            $output .= '<p>';
            $output .= 'Модуль успешно установлен! Спасибо за Ваш выбор! Приятной работы!';
            $output .= '</p>';
        }

        // Если через $_POST ничего не передаётся, выводим форму для установки модуля
        else
        {
            // Вывод
            $output .= '<p>';
            $output .= '<form method="POST" action="siseo_installer.php">';
            $output .= '<input type="hidden" name="siseo_install" value="1" />';
            $output .= '<input type="submit" value="Установить модуль" />';
            $output .= '</form>';
            $output .= '</p>';
        }
        
        $output .= '<p>';
        $output .= '<a href="http://alaev.info/blog/post/5143?from=SimpleSEO">разработка и поддержка модуля</a>';
        $output .= '</p>';

        // Функция возвращает то, что должно быть выведено
        return $output;
    }

?>