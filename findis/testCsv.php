<?php

if (($handle = fopen("source/exportArticles.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 0, ";")) !== FALSE) {
	    $sku =  $data[9];
        $reappro = intval ($data[16]);		
		$stock = intval ($data[8]);
		
		$finalQty = $reappro + $stock;		        
        
       
        echo $sku.'-->reap = '.$reappro.' -- stock =  '.$stock.' -- Total = '.$finalQty.'<br>';
        
    }
    fclose($handle);
    
}