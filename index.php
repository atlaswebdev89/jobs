<?php
//КОНСТАНТЫ 
define('H2', 90);
define('LI', 130);
define('P', 130);

//Стартуем сессию
session_start();

//                                                      БЛОК ФУНКЦИЙ
                                                     
//Функция формирование массива сессии $_SESSION (введенный текст, количество символов с проблема и без) 
function data ($text) {
    //введенный текст в форму
    //использована функция trim для удаления пробелов в начале и конце строки
    $_SESSION['name'] = $text;
    //Количество символов с пробелами
    $_SESSION['length_space'] = mb_strlen($text, 'utf-8'); 
    //Количество символов в строке без пробелом. Используем функцию str_replace для удаления пробелом
    $text_nonspace=str_replace(array(" "), '', $text);
    $_SESSION['length_not_space'] = mb_strlen($text_nonspace, 'utf-8');
}

//Функция удаления из строки все html и php тегов(strip_tags). Удаление пробелов в начале и конце строки (trim)
function clear_str($str) {
         $str =  strip_tags(trim($str));
       return ($str);
}

function getLengthStr ($srt) {
    return mb_strlen($srt, 'utf-8');
}

function paragraph ($str) {
        //Разделение текста на абзацы
        $a=explode("\r\n", $str);
        
        
        
        //Удаление пустых элементов массива 
        $new_arr = array_diff($a, array(''));
        $array = array();
        
        //Получение значения ключа первого элемента массива
        //$keys = array_keys($new_arr);
        //$firstKey = $keys[0];
        
        $b=0;
        foreach ($new_arr as $key=>$item){
            if (($key-$b ) == 1 || ($key==$b)){
                  $array[$key] = $item;              
            }else {
                $array[$b+1] = 'stop';
                $array[$key] = $item;  
            }
            $b = $key;
        }
        ksort($array);
        $array =array_values($array);
    return $array;
}
//функция добавления html тегов согласно условию задания
function array_output (array $arr) {
    $d = array();
    $list = array();
    $list [] = '<ul>';
    
    foreach ($arr as $key=>$item) {
        if ($item != 'stop'){
                $array[] = $item;
        }else {    
            if ($length = count($array)>1){
                
                foreach ($array as $items) {
                    if (getLengthStr($items)>49 && getLengthStr($items) < 130) {
                        $list[] = '<li>'.$items.'</li>';
                    }else if (getLengthStr($items)<49) {
                        $d[] = '<h2>'.$items.'</h2>';
                    }else if (getLengthStr($items)>130) {
                        $d[] = '<p>'.$items.'</p>';
                    }
                }                 
            }else {
                if(getLengthStr($array[0])<90){
                        $d[] = '<h2>'.$array[0].'</h2>';
                        $array = [];
                }else  {
                        $d[] = '<p>'.$array[0].'</p>';                     
                }
            }
            if (count($list) > 1){ 
                    $list[] ='</ul>';               
            }
           
    
        } 
        
    }
    
            var_dump($d);
            
            echo implode('', $list);
            var_dump($list);exit;
    
    
    
    foreach ($array as $key=>&$item){
        //Получаем длину строки с учетом пробелом
        $length = mb_strlen($item, 'utf-8');
       
            if ($length < 90) {
                $item = '<h2>'.$item.'</h2>';
            }
        }
        var_dump($array);exit;
    
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//получаем текущий URI
$url = $_SERVER['REQUEST_URI'];

//Защита от повторной отправки формы (post redirect get)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_POST['name']) { 
            $data = clear_str($_POST['name']);
                 data($data);
                 $array = (paragraph($data));
                 array_output($array);
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

?>

<form name="text-form" method="post">
            <label>
                <textarea name="name" cols="120" rows="10" placeholder="Введите текст" id="myForm"></textarea><br/>
                <input type="submit" value="Подставить теги"/>
            </label>
</form>
                <p>Количество символов с пробелами: <?=$length_space=($_SESSION['length_space'])?$_SESSION['length_space']:0;?></p>
                <p>Количество сиволов без пробелов: <?=$length_not_space=($_SESSION['length_not_space'])?$_SESSION['length_not_space']:0;?></p>
        <br>
<form method="post" action=""> 
    <input type="text" name="text" value=""/>
    <input type="submit" value="готово"/>
</form>

<textarea name="text" cols="120" rows="20">
    <?php echo $_SESSION['name'];?>
</textarea>
        c 6 до 49 - тег h2       
        до 90 теги h2
        от 49 до 142 - списки
        от 90 до 200 - несколько абзацев одинаковой длины (-+30 символов) - списки
        
        c 142 - теги p