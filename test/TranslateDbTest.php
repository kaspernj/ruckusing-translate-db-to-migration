<?php

require_once dirname(__FILE__) . "/../src/RuckusingTranslateDbToMigration.php";

class TranslateDbTest extends PHPUnit_Framework_TestCase {
  function testTranslate(){
    require_once "/home/kaspernj/Dev/PHP/baza-php/src/Baza.php";
    $db = new Baza(array(
      "type" => "Mysqli",
      "host" => "localhost",
      "user" => "translate-db",
      "db" => "klimax-init-db"
    ));
    
    $ruckusing_translate = new RuckusingTranslateDbToMigration(array(
      "db" => $db
    ));
    
    $code = $ruckusing_translate->toPHPCode();
    
    echo $code . "\n";
  }
}