<?php

$servername = "localhost";
$username = "admin";
$password = "1234";
$dbname = "test";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$csvFile = 'test-data-import.csv';

if (($handle = fopen($csvFile, "r")) !== FALSE) {    
    $header = fgetcsv($handle, 1000, ",");
    
    $columnsToInsert = array('upc', 'sku', 'qty', 'brand', 'type', 'name', 'short_description', 'description', 'materials', 'colors');

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $values = [];
                
        foreach ($columnsToInsert as $column) {
            $values[] = "'" . $conn->real_escape_string($data[array_search($column, $header)]) . "'";
        }
        
        $sql = "INSERT INTO products_test (" . implode(', ', $columnsToInsert) . ") VALUES (" . implode(', ', $values) . ")";
        
        if ($conn->query($sql) !== TRUE) {
            echo "Error al insertar datos: " . $conn->error;
        }
    }

    fclose($handle);
} else {
    echo "No se pudo abrir el archivo CSV.";
}

$conn->close();

?>