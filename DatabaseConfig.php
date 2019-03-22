<?php
define("DBHOST","localhost");
define("DBNAME","pkps");
define("DBUSER","root");
define("DBPASS","toor");


$connect_DB = new mysqli(DBHOST,DBUSER,DBPASS,DBNAME);

if($connect_DB ->connect_error) {
    die("Database Connection failed: ". $connect_DB->connect_error. " ".$connect_DB->connect_errno);
} 
?>