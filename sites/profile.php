<?php
if(!defined('directly')){
    echo "not allowed :(";
  exit();  
}?>
Zmiana hasła i uzupełnienie danych potrzebnych do faktury już w krótce :)

<?php
// Code to convert php array to xml document
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Creating an array
function arrayToXml($array, $rootElement = null, $xml = null) {
  $_xml = $xml;
    
  // If there is no Root Element then insert root
  if ($_xml === null) {
      $_xml = new SimpleXMLElement($rootElement !== null ? $rootElement : '<root/>');
  }
    
  // Visit all key value pair
  foreach ($array as $k => $v) {
        
      // If there is nested array then
      if (is_array($v)) { 
            
          // Call function for nested array
          arrayToXml($v, $k, $_xml->addChild($k));
          }
            
      else {
            
          // Simply add child element. 
          $_xml->addChild($k, $v);
      }
  }
    
  return $_xml->asXML();
}

// Creating an array for demo
$my_array = array (
'TOWARY' => array (
  'TOWAR' => array (
    'KOD' => '61912',
    'NAZWA' => 'SOLVERX MEGA ZESTAW KOSMETYKI DO SKÓRY ATOPOWEJ',
    'GRUPA' => 'solverx',
    'STAWKA_VAT' => array (
      'STAWKA' => '23'
    ),
    'DOSTAWCA'=> array (
      'KOD_U_DOSTAWCY' => 'O4>Zestaw03-Atopic'
    ),
    'CENY' => array (
      'CENA' => array (
        'WARTOSC' => '292,67'
      ),
      'JM' => 'szt.'
    ),

  )
  ),
'RECEPTURY' => array (
  'RECEPTURA' => array(
    'KOD'=> 'O4>Zestaw03-Atopic',
    'ILOSC'=>'1',
    'KOD_PRODUKTU'=>'61912'
  ),
  'SKLADNIKI' => array(
    'SKLADNIK1' => array(
      'ILOSC' => '1',
      'JM' => 'szt.',
      'MAGAZYN_SYMBOL'=>'MAGAZYN',
      'KOD_SKLADNIKA'=>'45275'
    ),
    'SKLADNIK2' => array(
      'ILOSC' => '1',
      'JM' => 'szt.',
      'MAGAZYN_SYMBOL'=>'MAGAZYN',
      'KOD_SKLADNIKA'=>'44296'
    ),
    'SKLADNIK3' => array(
      'ILOSC' => '1',
      'JM' => 'szt.',
      'MAGAZYN_SYMBOL'=>'MAGAZYN',
      'KOD_SKLADNIKA'=>'44300'
    ),
    'SKLADNIK4' => array(
      'ILOSC' => '1',
      'JM' => 'szt.',
      'MAGAZYN_SYMBOL'=>'MAGAZYN',
      'KOD_SKLADNIKA'=>'49582'
    ),
    'SKLADNIK5' => array(
      'ILOSC' => '1',
      'JM' => 'szt.',
      'MAGAZYN_SYMBOL'=>'MAGAZYN',
      'KOD_SKLADNIKA'=>'44299'
    ),
    'SKLADNIK6' => array(
      'ILOSC' => '1',
      'JM' => 'szt.',
      'MAGAZYN_SYMBOL'=>'MAGAZYN',
      'KOD_SKLADNIKA'=>'44298'
    ),
    'SKLADNIK7' => array(
      'ILOSC' => '1',
      'JM' => 'szt.',
      'MAGAZYN_SYMBOL'=>'MAGAZYN',
      'KOD_SKLADNIKA'=>'44295'
    ),
    'SKLADNIK8' => array(
      'ILOSC' => '1',
      'JM' => 'szt.',
      'MAGAZYN_SYMBOL'=>'MAGAZYN',
      'KOD_SKLADNIKA'=>'44297'
    )
  )
)
);

// Calling arrayToxml Function and printing the result
echo arrayToXml($my_array);