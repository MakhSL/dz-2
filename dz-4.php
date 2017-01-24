<?php

error_reporting(E_WARNING | E_NOTICE | E_ERROR | E_PARSE);
ini_set('display_errors', 1);

$ini_string='
[Игрушка мягкая мишка белый]
цена = '.  mt_rand(1, 10).';
количество заказано = '.  mt_rand(1, 10).';
осталось на складе = '.  mt_rand(0, 10).';
diskont = diskont'.  mt_rand(0, 2).';
    
[Одежда детская куртка синяя синтепон]
цена = '.  mt_rand(1, 10).';
количество заказано = '.  mt_rand(1, 10).';
осталось на складе = '.  mt_rand(0, 10).';
diskont = diskont'.  mt_rand(0, 2).';
    
[Игрушка детская велосипед]
цена = '.  mt_rand(1, 10).';
количество заказано = '.  mt_rand(1, 10).';
осталось на складе = '.  mt_rand(0, 10).';
diskont = diskont'.  mt_rand(0, 2).';
    
';

$bd =  parse_ini_string($ini_string, true);

//print_r($bd);
?>
<html>
    <head>
        
    </head>
    <body>
        <style>
            body{
                font-family: 'calibri', sans-serif;
            }
            .tovar{
                width: 350px;
                height: auto;
                border: 1px solid #000;
                margin: 0px 60px 20px 60px;
                padding: 5px;
                border-radius: 8px;
            }
            .korzina{
                width: 480px;
                float: left;
                position: relative;
                top: 0px;
                transition: all 0.3s;
                border: 1px solid #000;
                border-radius: 50px 8px 50px 8px;
                bottom: 30px;
            }
            .korzina:hover{
                top:10px;
            }
            .korz{
                margin: 10px 0 20px 80px;
            }
            .itogo{
                width: 400px;
                height: auto;
                float: left;
                margin-left: 30px;
                position: relative;
                top: 0px;
                transition: all 0.3s;
                border-radius: 50px 8px 50px 8px;
                border: 1px solid #000;
                padding: 10px;
            }
            .itogo:hover{
                top:10px;
            }
            .itog{
                margin: 10px 0 20px 130px; 
            }
            .Notification{
                width: 730px;
                float: left;
                position: relative;
                top: 15px;
                left:25px;
                transition: all 0.3s;
                border: 1px solid #000;
                border-radius: 50px 8px 50px 8px;
                padding: 5px;
            }
            .Notification:hover{
                top:40px;
            }
            .Not{
                margin: 10px 0 20px 130px; 
            }
        </style>
        <div class="korzina">
<?php
function parse_basket($basket){
    echo '<h1 class="korz">Содержание корзины: </h1>';
    foreach ($basket as $name => $params){
        
        $discount = discount($name,$params['цена'], $params['количество заказано'],$params['осталось на складе'] ,$params['diskont']);
        
       // $Not = Not($name, $params['количество заказано'],$params['diskont']);
        
        echo "<div class='tovar'>".'Наименование: '.$name.'<br>Цена за единицу товара: '.$params['цена'].' руб.'.'<br>Скидка: '.$discount['skidka'].'<br>Цена со скидкой:  '.$discount['price'].' руб.'.
             '<br>Количество заказано: '.$params['количество заказано'].' шт.'.'<br>На складе: '.$params['осталось на складе'].' шт.'.'<br>Стоимость по наличию: '.$discount['price_total'].' руб.'.'</div>';
        
    }
    
}
    parse_basket($bd);
?>
</div>
        
<div class="itogo">   
    
<?php
function itog($basket){
    $itog = 0;
    $kolvo = 0;
    echo '<h1 class="itog">Итог: </h1>';
    foreach ($basket as $name => $params){
        
        $discount = discount($name,$params['цена'], $params['количество заказано'],$params['осталось на складе'] ,$params['diskont']);
        
        $summ = count($basket);
        $itog += $discount['price_total']."<br><br>";
        $kolvo += $discount['kol_tov'];
       
    }
    
   echo 'Всего наименований: '.$summ.'<br>Итоговая сумма: '.$itog.' руб.<br>'.'Количество заказанных товаров: '.$kolvo.' шт.<br>';
}
 itog($bd);
?>
    </div>
        
    <div class="Notification">
    <?php
function Notification($basket){
   
    echo '<h1 class="Not">Уведомления: </h1>';
    foreach ($basket as $name => $params){
        
        $discount = discount($name,$params['цена'], $params['количество заказано'],$params['осталось на складе'] ,$params['diskont']);
        
   if($params['количество заказано'] > $params['осталось на складе']){echo 'К сожалению товара "'.$name.'" на складе меньше, чем вы заказали, на ' .($params['количество заказано'] - $params['осталось на складе']).' шт.<br>';}
   
   if($name == 'Игрушка детская велосипед' && $params['количество заказано'] > 2){
           echo 'При заказе "Игрушка детская велосипед" в колличестве '.$params['количество заказано'].'шт. вы получаете 30% скидку!!!';
          
    }
    
  }
    }
     Notification($bd);
    ?>
        </div>
        
<?php
function discount($name,$prise,$amount,$sklad,$diskont){
    $kol_tov = 0;
    
    $skidka = substr($diskont,7,1);
    
    if($skidka == 0){$skidka = 'Нет скидки';}
    
    if($skidka > 0){$skidka = $skidka * 10 .'%';}
  
    if($name == 'Игрушка детская велосипед' && $amount > 2){
         $skidka = 30 .'%';
          
    }
    // скидка за одну позицию
    $price_with_diskont_per_item = $prise - ($prise * ($skidka) / 100);
    
    // подсчёт общей цены с учетом количества товара и этой скидки
    
    if($amount > $sklad){ 
        $kol_tov = $sklad;
    }
    
    if($amount < $sklad){ 
        $kol_tov = $amount;
    }
    
    if($amount == $sklad){ 
        $kol_tov = $amount;
    }
      
    $total_price_all_items_with_diskont = $kol_tov * $price_with_diskont_per_item;
   
    
    return array('skidka'=>$skidka,
                 'price'=>$price_with_diskont_per_item,
                 'price_total'=>$total_price_all_items_with_diskont,
                 'kol_tov'=>$kol_tov     
        );
}
?>
            
    </body>
</html>