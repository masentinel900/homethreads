<?php 

    function downloadImagesFromCSV($csvFile, $outputFolder) {
        if (!file_exists($csvFile)) {
            return "El archivo CSV no existe.";
        }

        if (!is_dir($outputFolder)) {
            return "La carpeta de salida no existe.";
        }

        $csv = fopen($csvFile, 'r');
        $count = 0;

        while (($row = fgetcsv($csv)) !== false) {
            $count++;
            $imageUrls = array_slice($row, 31, 6);

            foreach ($imageUrls as $imageUrl) {
                $imageName = basename($imageUrl);
                $imagePath = $outputFolder . '/' . $imageName;

                if (!empty($imageUrl) && filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                    if (copy($imageUrl, $imagePath)) {
                        echo "Imagen descargada: $imageName<br>";
                    } else {
                        echo "Error al descargar la imagen: $imageName<br>";
                    }
                }
            }
        }

        fclose($csv);
        return "Descarga de imágenes completada. Total de filas procesadas: $count";
    }

    $csvFile = 'test-data-import.csv'; 
    $outputFolder = '/images';

    $result = downloadImagesFromCSV($csvFile, $outputFolder);
    echo $result;
    
?>