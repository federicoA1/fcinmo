<?php
session_start(); 
class Utiles {
    function miniatura($alto,$ancho,$archivo,$carpeta,$nombre_final,$addWater = 1){
           if (is_file($archivo)  ){
                        $orden++;
                        $image = @imagecreatefromjpeg($archivo);
                        // Get original width and height
                        $width = imagesx($image);
                        $height = imagesy($image);
                        // New width and height
                        $ratio = $ancho /$alto;
                        if ($width >= $height * $ratio){
                            $new_height = $alto;
                            $new_width = $width * ($new_height/$height);
                        }else{
                            $new_width = $ancho;
                            $new_height = $height * ($new_width/$width);
                        }                            
                         
                       
                            
                        $image_resized = imagecreatetruecolor($new_width, $new_height);
                        imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                        
                        if ($addWater){
                            $watermark = @imagecreatefrompng("images/marcaDeAgua.png");
                            $watermark_width = imagesx($watermark);  
                            $watermark_height = imagesy($watermark);
                            if ($alto > 300){
                                $factor=1;    
                            }else{
                               $factor=2.3;
                            }
                            $dest_x = $new_width/2 - $watermark_width/2/$factor+5;  
                            $dest_y = $new_height/2 - $watermark_height/2/$factor;
                            
                            imagecopyresampled($image_resized,$watermark, $dest_x,$dest_y,0,0,$watermark_width/$factor,$watermark_height/$factor,$watermark_width,$watermark_height); 
                        }
                        
                        $h22 = (($new_height  - $alto) /2);
                        $w22 = (($new_width  - $ancho) /2);
                        
                        $nombre_miniatura = $carpeta."/".$nombre_final;
                        $image_p = imagecreatetruecolor($ancho,$alto);
                        imagecopyresampled($image_p, $image_resized, 0, 0, $w22,$h22, $new_width, $new_height, $new_width, $new_height);  
                        imagejpeg($image_p,$nombre_miniatura,89);
           }//is file;
        
    }
    
    function limpiarArreglo($arr){
        return array_map('mysql_real_escape_string', $arr);
    }
    
    
        
}//end class
?>
