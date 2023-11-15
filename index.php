<?php

function badEmailValidate($email)
{
    if (strpos($email, '@') != false) {
        return true;
    }
    return false;
}

if ($_POST['count'] && $_POST['count'] > 0) {

    $users = array();

    for ($c = 0; $c < $_POST['count']; $c++) {

        if (!empty($_POST['user_name_' . $c]) && !empty($_POST['user_email_' . $c]) && badEmailValidate($_POST['user_email_' . $c])) {
            $users[] = array(
                'name' => preg_replace('/[^a-zA-Z0-9]/i', "", $_POST['user_name_' . $c]),
                'email' => $_POST['user_email_' . $c]
            );
        }
    }

    if (sizeof($users) < 2) {
        die("Указан только 1 участник. Должно быть не менее 2 участников.");
    }

    if (sizeof($users) > 10) {
        die("Максимальное число участников не может быть больше 10.");
    }

    $amount = (int)$_POST['amount'];

    require('Santa.class.php');

    $santa = new SecretSanta();

    if ($santa->run($users)) {
        echo 'Вы выбраны тайным Сантой для<br/>';
        $names = $santa->getNames();
        foreach ($names as $name) {
            echo $name . '<br/>';
        }
    }
    die();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Тайный Санта</title>
    <link href='http://fonts.googleapis.com/css?family=Cabin+Condensed:500' rel='stylesheet' type='text/css'>
    <style type='text/css'>
        body {
            background-color: #222;
        }
        #container {
            border: solid #ccc 2px;
            background-color: #fff;
            width: 680px;
            margin: 20px auto 2px;
        }
        .heading {
            width: 300px;
            padding: 2px 5px;
            float: left;
        }
        .block {
            margin: 6px;
            border: solid #666 2px;
        }
        .block .row {
            clear: left;
        }
        .block .row span {
            cursor: pointer;
        }
        .block .toprow {
            background-color: #f1f1f1;
            height: 24px;
        }
        form {
            margin: 0;
            padding: 0;
        }
        .block .row button {
            background-color: #f1f1f1;
            border: solid 1px #666;
            padding: 4px;
            *padding: 0px;
            cursor: pointer;
            margin: 2px;
        }
        .run {
            background-color: #f1f1f1;
            border: solid 1px #666;
            padding: 4px;
            margin: 6px;
            display: block;
            width: 668px;
            cursor: pointer;
        }
        .right_under a {
            color: #666;
        }
    </style>
    <script type='text/javascript'>

        var tmp_dom = null;
        var counter = 1;

        function addUser() {
            if (tmp_dom == null) tmp_dom = document.getElementById('insert_zone').children[0];

            new_node = tmp_dom.cloneNode(true);
            inputs = new_node.getElementsByTagName('input')
            for (var i = 0; i < inputs.length; i++) {
                inputs[i].value = '';
                inputs[i].name = inputs[i].name.slice(0, -1) + counter;
            }
            document.getElementById('insert_zone').appendChild(new_node);
            counter++;
            document.getElementById('count').value = counter;
            return false;
        }

        function removeRow(me) {
            if (tmp_dom == null) tmp_dom = document.getElementById('insert_zone').children[0];
            document.getElementById('insert_zone').removeChild(me.parentNode);
        }
    </script>

</head>
<body>
<div id='container'>
    <form method='post' action=''>
        <div class='header'>Тайный Санта</div>
        <div class='block'>
            <div class='toprow column'>
                <div class='heading'>Имя</div>
                <div class='heading'>Email</div>
            </div>
            <div id='insert_zone'>
                <div class='row'>
                    <input name='user_name_0' value=''/>
                    <input name='user_email_0' value=''/>
                    <br>
                    <br>
                    <button class="warning" onclick='removeRow(this);'>Удалить участника</button>
                    <br>
                    <br>
                </div>
            </div>
            <div class='row'>
                <button onclick='return addUser();'>Добавить участника</button>
            </div>
        </div>
        <input type='hidden' id='count' name='count' value='0'/>

        <input class='run' type='submit' value='Отправить'/>
    </form>

</div>
</body>
</html>