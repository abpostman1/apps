<?php
header('Content-Type: text/html; charset=utf-8');
include('includes/conn_cycle.php');
ini_set('display_error',1);error_reporting(EALL);

$count = 0;
$count_test = 0;
$error_count = 0;
$today = date('Y-m-d');



if($_FILES["file"]["type"] != "application/vnd.ms-excel"){
	die("Ce n'est pas un fichier de type .csv");
}
elseif(is_uploaded_file($_FILES['file']['tmp_name'])){
	
	//Process the CSV file
	$handle = fopen($_FILES['file']['tmp_name'], "r");
}
	
while (($data = fgetcsv($handle, 850, ";")) !== FALSE) {
    
    // ref // sku
    $ref = htmlspecialchars(mysqli_real_escape_string($mysqli,$data[0]));
    // desc
    $desc = utf8_decode(htmlspecialchars(mysqli_real_escape_string($mysqli,$data[1])));

    echo $ref.' -> '.$desc.'<br>';
    
    $sql2 = $mysqli->query("UPDATE cycle SET Description = '$desc' WHERE parent = '$ref'");


    if (!$sql2) {
        printf('Error ,%s\n',$mysqli->error);
    }

    echo 'ref : '.$ref.' -> Parent :'.$desc.'<br>';

}



	

mysqli_close();
//header('Location: result.php');
?>



