<?php

    $inputfile = 'tx_tbyouthoffers_download.csv';
    $outputfile = 'socialoffers_download.csv';
         
   
    $positions = array (1 => 'Jugendbeauftragter', 2 => 'Vorsitzender', 3 => 'Geschäftsführer');
    
    
    $row = array();
    
    
    if (($handle = fopen($inputfile, "r")) !== false) {
        
        $fp = fopen($outputfile, 'w');

        // Die erste Zeile mit den Spaltennamen auslesen
        $data = fgetcsv ($handle, 0, ';', "'");
        if(is_array($data)) {
            $count = 0;
            foreach($data AS $cellName) {
                $cellNamesArray[$count] .= $cellName;
                $count++;
                }
        }
        
    
        
        while (($data = fgetcsv($handle, 0, ";", "'")) !== false) {
            
            $row = array();
            
           $rowCounter = 1;
            
                $num = count ($data); 
                
                $csvArray = array();
            
                for ($c=0; $c < $num; $c++){                                
                    
                    $csvArray[$cellNamesArray[$c]] = $data[$c];      
                
                }
                
                
                // Konvertierten Datensatz zusammenstellen
                $row[] = $rowCounter;                                   // 1 - id
                $row[] = $csvArray['uid'];                              // 2 - offerId of dataset
                $row[] = $csvArray['title'];                            // 3 - title
                $row[] = $csvArray['download'];                         // 4 - Datei
                $row[] = $csvArray['category'];                         // 5 - Kategorie
                    
                // copy file
                copy ('images_old/'.$csvArray['download'],'files/'.$csvArray['download']);

           
                $rowCounter++;
           
                // Bildschirmausgabe vorbereiten
                $columnCounter = 1;
                $dataSet .= '<tr>';
                foreach ($row as $dataField) {
                    
                    $dataSet .=  '<td>'.$columnCounter.': '.$dataField.'</td> ';
                    $columnCounter++;
                }
                $dataSet .= '</tr>';
           
            
            
            
            
            // Konvertierten Datensatz als Zeile Ausgabedatei schreiben
            fputcsv($fp, $row, ';', '"');
          
            
            
            
        }
        
        echo '<table border="1">'.$dataSet.'</table>';
        
      
        
    }
                fclose($fp); 

    fclose($handle);



  
?>