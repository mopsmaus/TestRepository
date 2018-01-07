<?php

    // test

    $inputfile = 'tx_tbyouthoffers_objects.csv';
    $outputfile = 'socialoffers.csv';
    $outputfileImage = 'socialoffers_images.csv';
    $createImages = false;
    $imageDataSetCounter = 1;          
    
    $cities = array (1 => 'Aurich', 2 => 'Norden', 3 => 'Norderney', 4 => 'Wiesmoor',
                     5 => 'Hage', 6 => 'Krummhörn', 7 => 'Ihlow', 8 => 'Südbrookmerland',
                     9 => 'Dornum', 8 => 'Baltrum', 9 =>'Juist', 10 => 'Brookmerland',
                     11 => 'Großefehn', 12 => 'Großheide', 13 => 'Hinte', 14 => 'Hesel');
    
    $row = array();
    
    
    if (($handle = fopen($inputfile, "r")) !== false) {
        
        $fp = fopen($outputfile, 'w');
        $fpi = fopen($outputfileImage, 'w');

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
            
           
            
                $num = count ($data); 
                
                $csvArray = array();
            
                for ($c=0; $c < $num; $c++){                                
                    
                    $csvArray[$cellNamesArray[$c]] = $data[$c];      
                
                }
                
                // Konvertierten Datensatz zusammenstellen
                $row[] = $csvArray['uid'];                              // 1 - id
                $row[] = 0;                                             // 2 - enabled
                $row[] = $csvArray['care_subtype'] == 0 ? 1 : 2;        // 3 - type
                $row[] = date("Y-m-d H:i:s", $csvArray['tstamp']);      // 4 - dateSubmitted
                $row[] = date("Y-m-d H:i:s", $csvArray['tstamp']);      // 5 - dateChanged
                $row[] = $csvArray['title'];                            // 6 - title
                $row[] = $csvArray['street'];                           // 7 - street
                $row[] = $csvArray['zip'];                              // 8 - zip
                $row[] = utf8_decode($cities[$csvArray['city']]);       // 9 - city
                $row[] = $csvArray['address_line2'];                    // 10 - address_addtion
                $row[] = $csvArray['lat'];                              // 11 - lat
                $row[] = $csvArray['lon'];                              // 12 - lon
                $row[] = $csvArray['supporting_organisation'];          // 13 - organsiation 
                $row[] = $csvArray['contact_person'];                   // 14 - contact_person
                $row[] = $csvArray['phone'];                            // 15 - phone
                $row[] = $csvArray['fax'];                              // 16 - fax
                $row[] = $csvArray['mobil'];                            // 17 - mobil
                $row[] = $csvArray['email'];                            // 18 - email
                $row[] = $csvArray['details_page'];                     // 19 - details-page
                $row[] = $csvArray['description'];                      // 20 - description
                $row[] = $csvArray['care_type'];                        // 21 - care_type
                $row[] = $csvArray['care_times'];                       // 22 - care_times
               
                // 23 - agekids
                if ($csvArray['care_type'] == 2) { 
                    // Krippe
                    $row[] = '0,1,2';                                 
                } else if ($csvArray['care_type'] == 3) { 
                    // Kita
                    $row[] = '3,4,5,6';                                 
                } else if ($csvArray['care_type'] == 4) {
                    // Spielkreis
                    $row[] = '3,4,5,6';                                 
                } else if ($csvArray['care_type'] == 1) {
                    // Hort
                    $row[] = '6,7,8,9,10';                                 
                } else {
                    $row[] = '';
                }
                
                $row[] = $csvArray['care_seats'];                       // 24 - care_seats
                $row[] = $csvArray['care_openinghours'];                // 25 - care_openginghours
                $row[] = '';                                            // 26 - cirumstances
                $row[] = $csvArray['care_orientation'];                 // 27 - care_orientation
                $row[] = $csvArray['care_day'];                         // 28 - care_dailyroutine
                $row[] = $csvArray['care_cooperation'];                 // 29 - care_cooperation
                $row[] = $csvArray['care_school'];                      // 30 - care_school
                $row[] = $csvArray['care_catering'];                    // 31 - care_catering
                $row[] = $csvArray['qualityseal'];                      // 32 - care_qualityseal
                $row[] = $csvArray['care_integrated'];                  // 33 - care_integrated
                $row[] = $csvArray['care_house'];                       // 34 - care_house
                $row[] = $csvArray['care_qualification'];               // 35 - care_qualification

   
                // Bilder in neues Verzeichnis kopieren und CSV-Datei für Bilder erstellen
                if (!empty($csvArray['images'])) {
                
                    $images = explode(',',$csvArray['images']);
           
                    $imageCounter = 1;
           
                    foreach ($images as $image) {
                        
                        
                        if ($createImages == true) {
                            // copy orgiginal image
                            copy ('images_old/'.$image,'images_new/'.$image);
                            
                            // create thumb of image
                            smart_resize_image('images_old/'.$image, null, 500, 320, false, 'thumbs/'.$image);
                        }
                                                    
                        // generate image-csv-file
                        $row_image = array();
                       
                        $row_image[] = $imageDataSetCounter;                 // id
                        $row_image[] = $csvArray['uid'];                // offerId of dataset
                        $row_image[] = 'Bild '.$imageCounter;           // Bezeichnung
                        $row_image[] = $image;                          // Bilddatei
                        $row_image[] = $imageCounter;                   // SortOrder
                        
                        $imageCounter++;
                        $imageDataSetCounter++;
                        
                        fputcsv($fpi, $row_image, ';', "'");
                    }
           
           
                }
           
                // Bildschirmausgabe vorbereiten
                $dataset = '';
                foreach ($row as $dataField) {
                    
                    $dataSet .=  $dataField.', ';
                    
                }
                $dataSet .= '<br>';
           
            
            
            
            
            // Konvertierten Datensatz als Zeile Ausgabedatei schreiben
            fputcsv($fp, $row, ';', '"');
          
            
            
            
        }
        
        echo $dataSet;
        
      
        
    }
                fclose($fp); 
                fclose($fpi); 

    fclose($handle);



    /**
     * easy image resize function
     * @return boolean|resource
     */
    function smart_resize_image($file,
                                $string             = null,
                                $width              = 0,
                                $height             = 0,
                                $proportional       = false,
                                $output             = 'file',
                                $delete_original    = false,
                                $use_linux_commands = false,
                                $quality = 100
    ) {

        if ( $height <= 0 && $width <= 0 ) return false;
        if ( $file === null && $string === null ) return false;

        # Setting defaults and meta
        $info                         = $file !== null ? getimagesize($file) : getimagesizefromstring($string);
        $image                        = '';
        $final_width                  = 0;
        $final_height                 = 0;
        list($width_old, $height_old) = $info;
        $cropHeight = $cropWidth = 0;

        # Calculating proportionality
        if ($proportional) {
            if      ($width  == 0)  $factor = $height/$height_old;
            elseif  ($height == 0)  $factor = $width/$width_old;
            else                    $factor = min( $width / $width_old, $height / $height_old );

            $final_width  = round( $width_old * $factor );
            $final_height = round( $height_old * $factor );
        }
        else {
            $final_width = ( $width <= 0 ) ? $width_old : $width;
            $final_height = ( $height <= 0 ) ? $height_old : $height;
            $widthX = $width_old / $width;
            $heightX = $height_old / $height;

            $x = min($widthX, $heightX);
            $cropWidth = ($width_old - $width * $x) / 2;
            $cropHeight = ($height_old - $height * $x) / 2;
        }

        # Loading image to memory according to type
        switch ( $info[2] ) {
            case IMAGETYPE_JPEG:  $file !== null ? $image = imagecreatefromjpeg($file) : $image = imagecreatefromstring($string);  break;
            case IMAGETYPE_GIF:   $file !== null ? $image = imagecreatefromgif($file)  : $image = imagecreatefromstring($string);  break;
            case IMAGETYPE_PNG:   $file !== null ? $image = imagecreatefrompng($file)  : $image = imagecreatefromstring($string);  break;
            default: return false;
        }


        # This is the resizing/resampling/transparency-preserving magic
        $image_resized = imagecreatetruecolor( $final_width, $final_height );
        if ( ($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG) ) {
            $transparency = imagecolortransparent($image);
            $palletsize = imagecolorstotal($image);

            if ($transparency >= 0 && $transparency < $palletsize) {
                $transparent_color  = imagecolorsforindex($image, $transparency);
                $transparency       = imagecolorallocate($image_resized, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
                imagefill($image_resized, 0, 0, $transparency);
                imagecolortransparent($image_resized, $transparency);
            }
            elseif ($info[2] == IMAGETYPE_PNG) {
                imagealphablending($image_resized, false);
                $color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
                imagefill($image_resized, 0, 0, $color);
                imagesavealpha($image_resized, true);
            }
        }
        imagecopyresampled($image_resized, $image, 0, 0, $cropWidth, $cropHeight, $final_width, $final_height, $width_old - 2 * $cropWidth, $height_old - 2 * $cropHeight);
        # Taking care of original, if needed
        if ( $delete_original ) {
            if ( $use_linux_commands ) exec('rm '.$file);
            else @unlink($file);
        }

        # Preparing a method of providing result
        switch ( strtolower($output) ) {
            case 'browser':
                $mime = image_type_to_mime_type($info[2]);
                header("Content-type: $mime");
                $output = NULL;
                break;
            case 'file':
                $output = $file;
                break;
            case 'return':
                return $image_resized;
                break;
            default:
                break;
        }

        # Writing image according to type to the output destination and image quality
        switch ( $info[2] ) {
            case IMAGETYPE_GIF:   imagegif($image_resized, $output);    break;
            case IMAGETYPE_JPEG:  imagejpeg($image_resized, $output, $quality);   break;
            case IMAGETYPE_PNG:
                $quality = 9 - (int)((0.9*$quality)/10.0);
                imagepng($image_resized, $output, $quality);
                break;
            default: return false;
        }

        return true;
    }
  






?>