<?php
include_once '../../lib/initialize.php';


$xml = simplexml_load_file( theme_directory().'/theme.xml' , 'SimpleXMLElement', LIBXML_NOBLANKS );
$snippets = array();

if( isset($_GET['template_name']) ){
    foreach($xml->template as $item){
        if(strtolower($item->name) == strtolower($_GET['template_name'])){
            foreach ($item->snippet as $key) {
                $snippets[] = (string) $key;
            }
        }   
    }
}elseif( isset($_GET['category_name']) ){
    foreach($xml->category as $item){
        if(strtolower($item->name) == strtolower($_GET['category_name']) ){
            foreach ($item->snippet as $key) {
                $snippets[] = (string) $key;
            }
        }   
    }
}

echo json_encode( $snippets );
?>
