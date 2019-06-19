<?php
$mysqli = new mysqli ('127.0.0.1', 'abpostman15', 'rpnT&166', 'convert');
if ( $mysqli->connect_errno ) {
	printf( "Echec de la connexion: %s\n", $mysqli->connect_error );
	exit();
}