<?php
set_time_limit(90);
include 'models/Item.php';
include 'lib/linkDataBase.php';
include 'models/Stock.php';

$db = new Connection();
$stock = new Stock($db);
$counter = 1;

if (($gestor = fopen("test.csv", "r")) !== FALSE) {
    while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
        $item  = Item::parseItem($datos[0], $datos[1], $datos[2], $datos[3], $datos[4], $datos[5] ,$datos[6] ,$datos[7] ,$datos[8],0);
        $stock->addItem($item);
        $counter++;
    }
    fclose($gestor);
    echo "agreagados" .$counter;
}


?>