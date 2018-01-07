<?php

    // test

    $inputfile = 'tx_tbyouthoffers_organisation.csv';
    $outputfile = 'socialoffers_organisation.csv';
    $contactfile = 'tx_tbyouthoffers_organisation_contacts.csv';
         
    
    $cities = array (1 => 'Aurich', 2 => 'Norden', 3 => 'Norderney', 4 => 'Wiesmoor',
                     5 => 'Hage', 6 => 'Krummhörn', 7 => 'Ihlow', 8 => 'Südbrookmerland',
                     9 => 'Dornum', 8 => 'Baltrum', 9 =>'Juist', 10 => 'Brookmerland',
                     11 => 'Großefehn', 12 => 'Großheide', 13 => 'Hinte', 14 => 'Hesel');
    
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
        
        // Kontakdaten laden
        $handle2 = fopen($contactfile, 'r');

        $data2 = fgetcsv ($handle2, 0, ';', "'");
        if(is_array($data2)) {
            $count = 0;
            foreach($data2 AS $cellName2) {
                $cellNamesArray2[$count] .= $cellName2;
                $count++;
                }

        }
        
        
        while (($data = fgetcsv($handle, 0, ";", "'")) !== false) {
            
            $row = array();
            
           
            
                $num = count ($data); 
                
                $csvArray = array();
            
                for ($c=0; $c < $num; $c++){                                
                    
                    $csvArray[$cellNamesArray[$c]] = $data[$c];      
                
                }
                
                // Konvertierten Datensatz zusammenstellen
                $row[] = $csvArray['uid'];                              // 1 - id
                $row[] = 0;                                             // 2 - enabled
                $row[] = 50;                                            // 3 - type
                $row[] = date("Y-m-d H:i:s", $csvArray['crdate']);      // 4 - dateSubmitted
                $row[] = date("Y-m-d H:i:s", $csvArray['tstamp']);      // 5 - dateChanged
                $row[] = $csvArray['title'];                            // 6 - title
                $row[] = $csvArray['street'];                           // 7 - street
                $row[] = $csvArray['zip'];                              // 8 - zip
                $row[] = utf8_decode($cities[$csvArray['city']]);       // 9 - city
                $row[] = $csvArray['address_line2'];                    // 10 - address_addtion
                $row[] = $csvArray['lat'];                              // 11 - lat
                $row[] = $csvArray['lon'];                              // 12 - lon
                

                // 13 - contact_person
                if ($csvArray['contact_persons'] > 0) {
                    $found = false;
                    while (($data2 = fgetcsv($handle2, 0, ";", "'")) !== false) {
    
                        $num2 = count ($data2); 
    
                    
                        $csvArray2 = array();
                   
                       for ($d=0; $d < $num2; $d++){                                
                           
                           $csvArray2[$cellNamesArray2[$d]] = $data2[$d];      
                       
                       }
                    
                        if ($csvArray2['parentid'] == $csvArray['contact_persons']) {
                            if (array_key_exists($csvArray2['position'],$positions)) {
                                 $row[] = $csvArray2['title'].' ('.utf8_decode($positions[$csvArray2['position']]).')';              
                                
                            } else {
                                $row[] = $csvArray2['title'];              
                            }
                            $found = true;
                            break;
                        }
                    
                    
                    }
                    if ($found == false) $row[] = '';
                    
                } else{
                    $row[] = '';              

                }
                
                $row[] = $csvArray['phone'];                            // 14 - phone
                $row[] = $csvArray['fax'];                              // 15 - fax
                $row[] = $csvArray['mobil'];                            // 16 - mobil
                $row[] = $csvArray['email'];                            // 17 - email
                $row[] = $csvArray['details_page'];                     // 18 - details-page
                $row[] = '';                                            // 19 - description
                $row[] = $csvArray['legal_form'];                       // 20 - legal_form
                    
           
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