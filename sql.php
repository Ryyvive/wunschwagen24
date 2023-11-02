<?php
$hostname="wunschwagen24-dbserver-dev.database.windows.net";
$username="PHPADMIN";
$password="340\$Uuxwp7Mcxo7Khy";
$sslmode="require";
$database ="phpprojekt";


$con = mysqli_init();
#mysqli_ssl_set($con,NULL,NULL, NULL, NULL, NULL);
mysqli_real_connect($con, $hostname,$username,$password,$database,"1433");

$drop_ma = "DELETE FROM MA";
$drop_ku = "DELETE FROM KU";
$drop_kategorie = "DELETE FROM KATEGORIE";
$drop_frage = "DELETE FROM FRAGE";

$ma_table = "CREATE TABLE MA (email VARCHAR(50) PRIMARY KEY
                            ,vorname VARCHAR(50) NOT NULL 
                            ,nachname VARCHAR(50) NOT NULL 
                            ,password VARCHAR(255) NOT NULL)";
$ku_table = "CREATE TABLE KU (email VARCHAR(50) PRIMARY KEY
                            ,vorname VARCHAR(50) NOT NULL 
                            ,nachname VARCHAR(50) NOT NULL 
                            ,password VARCHAR(255) NOT NULL)";
$kategorie_table = "CREATE TABLE KATEGORIE (kategorie_id INT PRIMARY KEY AUTO_INCREMENT
                                             ,kategorie VARCHAR(50))";
$frage_table = "CREATE TABLE FRAGE (frage_id INT PRIMARY KEY AUTO_INCREMENT
                                    ,titel varchar(255)
                                    ,frage TEXT
                                    ,status INT
                                    ,antwort TEXT
                                    ,kategorie_id INT
                                    ,ersteller VARCHAR(50))";

$insert_kategorie = "INSERT INTO KATEGORIE (kategorie)
                    VALUES 
                        ('Mobilfunk'),
                        ('Festnetz'),
                        ('Magenta Eins'),
                        ('Megnta TV'),
                        ('SmartHome'),
                        ('Abrechnungen')";

#$con->query($drop_kategorie);
#$con->query($drop_ma);
#$con->query($drop_ku);
#$con->query($drop_frage);
#$con->query($ma_table);
#$con->query($ku_table);
#$con->query($kategorie_table);
#$con->query($frage_table);
#$con->query($antwort_table);
#$con->query($insert_kategorie);
$con -> query("Select * FROM TABLE");
echo "Done"
?>