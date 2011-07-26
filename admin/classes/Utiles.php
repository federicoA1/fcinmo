<?php
session_start(); 
class Utiles {
    function miniatura($alto,$ancho,$archivo,$carpeta,$nombre_final){
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
                        
                        $h22 = (($new_height  - $alto) /2);
                        $w22 = (($new_width  - $ancho) /2);
                        
                        $nombre_miniatura = $carpeta."/".$nombre_final;
                        $image_p = imagecreatetruecolor($ancho,$alto);
                        imagecopyresampled($image_p, $image_resized, 0, 0, $w22,$h22, $new_width, $new_height, $new_width, $new_height);  
                        imagejpeg($image_p,$nombre_miniatura,90);
           }//is file;
        
    }
    
    function miniaturaR($alto,$ancho,$archivo,$carpeta,$nombre_final){
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
                        
                        $nombre_miniatura = $carpeta."/".$nombre_final;
                        imagejpeg($image_resized,$nombre_miniatura,90);
           }//is file;
        
    }
    
    function miniaturaC($alto,$ancho,$archivo,$carpeta,$nombre_final){
           if (is_file($archivo)  ){
                       $image = @imagecreatefromjpeg($archivo);
                        // Get original width and height
                        $width = imagesx($image);
                        $height = imagesy($image);
                        // New width and height
                        
                        
                        $h22 = 0;
                        $w22 = 0;
                        if ($width >= $height){
                           $new_width = $ancho;
                           $new_height = $height * ($new_width/$width);
                            $h22 = (($alto - $new_height) /2);
                        }else{
                            $new_height = $alto;
                            $new_width = $width * ($new_height/$height);
                            $w22 = (( $ancho-$new_width) /2);
                        }    
                            
                        $image_resized = imagecreatetruecolor($new_width, $new_height);
                        imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                        
                        
                       
                        
                        $nombre_miniatura = $carpeta."/".$nombre_final;
                        $image_p = imagecreatetruecolor($ancho,$alto);
                        $white = ImageColorAllocate($image, 255, 255, 255);
                        //Make the background black 
                        ImageFill($image_p, 0, 0, $white);
                        
                        imagecopyresampled($image_p, $image_resized, $w22, $h22, 0,0, $new_width, $new_height, $new_width, $new_height);  
                        imagejpeg($image_p,$nombre_miniatura,90);
           }//is file;
    }

        
}//end class
?>
