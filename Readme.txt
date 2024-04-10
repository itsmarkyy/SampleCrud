To test the php file.

1) open phpmyadmin on your browser. 
2) create new database "testdb".
3) click import. then locate the testdb.sql,. scroll down then press okay. 
    wait for it to import the table.
4) next open the backend.php
    on the first 4 line of code
    $servername = "localhost";
    $dbusername = "root";
    $dbpass = "";
    $dbname = "testdb";

    change the value of each to match your database credentials.
    for me im using default xampp so the root and pass is root and null

5) run the file.