<?php


//Стартуем сессию
session_start();

//получаем текущий URI
$url = $_SERVER['REQUEST_URI'];

//Защита от повторной отправки формы (post redirect get)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_POST['name']) {
                data($_POST);
                //Редирект на этот же адрес только GET запросом
            header('Location:'.$url);
        }else {            
                unset($_SESSION['name']);
                session_unset();  
                $_SESSION['data']='date';
            header('Location:'.$url);
        }
}
echo $_SESSION['data'];

//Функция формирование массива сессии $_SESSION (введенный текст, количество символов с проблема и без) 
function data (array $data) {
    //введенный текст в форму
    //использована функция trim для удаления пробелов в начале и конце строки
    $_SESSION['name'] = trim($data['name']);
    //Количество символов с пробелами
    $_SESSION['length_space'] = mb_strlen(trim($data['name']), 'utf-8'); 
    //Количество символов в строке без пробелом. Используем функцию str_replace для удаления пробелом
    $text_nonspace=str_replace(array(" "), '', trim($data['name']));
    $_SESSION['length_not_space'] = mb_strlen($text_nonspace, 'utf-8');
}
?>

<form name="text-form" method="post">
            <label>
                <textarea name="text" cols="120" rows="10" placeholder="Введите текст" id="myForm"></textarea><br/>
                <input type="submit" value="Подставить теги"/>
            </label>
</form>
                <p>Количество символов с пробелами: <?=$length_space=($_SESSION['length_space'])?$_SESSION['length_space']:0;?></p>
                <p>Количество сиволов без пробелов: <?=$length_not_space=($_SESSION['length_not_space'])?$_SESSION['length_not_space']:0;?></p>
        <br>
<form method="post" action=""> 
    <input type="text" name="name" value=""/>
    <input type="submit" value="готово"/>
</form>

<textarea name="text" cols="120" rows="20">
    <?=$_SESSION['name'];?>
</textarea>
