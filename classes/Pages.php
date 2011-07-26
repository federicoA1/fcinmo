<?php
include_once("classes/Main.php");
include_once("includes/connect.php");
class Pages extends Main{
   
    
    
    function index(){
       set_file("content", "index.html");
       $fila = Main::getRow("textos",1);
       $fila2 = Main::getRow("textos",2);
       s("el_instituto",$fila[contenido]);
       s("nuestros_valores",$fila2[contenido]);
       
       return Main::wrapper(pp("content"), "Inicio",0);
    }
    
    function especialidad($idEspecialidad){
        set_file("content","especialidad.html");
        return Main::wrapper(pp("content"),$nombreCategoria,$idCategoria);
    }
    
    function elInstituto(){
        set_file("content","paginas.html");
        
        $fila = Main::getRow("paginas",2);
        s("titulo",$fila[titulo]);
        s("contenido",$fila[contenido]);
        return Main::wrapper(pp("content"),"El Instituto",0,$fila[meta],$fila[meta]);
    }
    function nuestrasEspecialidades(){
        set_file("content","paginas.html");
        
        $fila = Main::getRow("paginas",3);
        s("titulo",$fila[titulo]);
        s("contenido",$fila[contenido]);
        return Main::wrapper(pp("content"),"Nuestras Especialidades",0,$fila[meta],$fila[meta]);
    }
    function hpv(){
        set_file("content","paginas.html");
        $fila = Main::getRow("paginas",1);
        s("titulo",$fila[titulo]);
        s("contenido",$fila[contenido]);
        return Main::wrapper(pp("content"),"Nuestras Especialidades",0,$fila[meta],$fila[meta]);
    }
    
    function ubicacion(){
       set_file("content","ubicacion.html"); 
       return Main::wrapper(pp("content"),"Ubicación",0); 
    }
    
    function galeria(){
       set_file("content","galeria.html"); 
       return Main::wrapper(pp("content"),"Ubicación",0); 
    }
    
    function showContacto($mensaje = ''){
        set_file("content","showContacto.html"); 
        if ($mensaje){
            s("mensaje",Main::divW("$mensaje","info"));
        }else{
            s("mensaje","");
        }
        return Main::wrapper(pp("content"),"Formulario de contacto",0); 
    }
    
    function doContacto($arr){
        $cuerpo .= "<h3>Formulario de contacto</h3> <br>";
        $cuerpo .= "Nombre: $arr[nombre] <br>";
        $cuerpo .= "Email: $arr[email] <br>";
        $cuerpo .= "Telefono: $arr[telefono] <br>";
        $cuerpo .= "Consulta:<br> $arr[consulta] <br>";
        
        $from = "formulario@vinotecasantafe.com.ar";
        $to = "contacto@vinotecasantafe.com,marialerub@gmail.com";
        
        $titulo = "Formulario de consulta";
        Main::enviarHTMLMail($from,$to,$titulo,$cuerpo);
        
        set_file("content","mensaje.html"); 
        s("titulo","Muchas Gracias");
        s("mensaje","Nos contactaremos con usted a la brevedad");
        
        return Main::wrapper(pp("content"),"Muchas Gracias",0); 
    }
    
    function galeriaXML(){
        $xml = '<?xml version="1.0" encoding="utf-8"?>
                <slideshow>
                <settings>
                <!-- slideshow size(pixels) -->
                <width>615</width>
                <height>224</height>
                <!-- transition settings -->
                <transitionSpeed>2</transitionSpeed>
                <!-- preloader settings -->
                <preloaderColor>FFCC00</preloaderColor>
                <!-- control buttons settings -->
                <showButtons>false</showButtons>
                <buttonsBack>1E1E1E</buttonsBack>
                <buttonsSymbol>F7F7F7</buttonsSymbol>
                <buttonsBackRoll>FFCC00</buttonsBackRoll>
                <buttonsSymbolRoll>1E1E1E</buttonsSymbolRoll>
                <!-- slideshow play settings -->
                <slideshow>true</slideshow>
                <loop>true</loop>
                <random>false</random>
                <!-- caption settings -->
                <titleDefaultColor>FFFFFF</titleDefaultColor>
                <descriptionDefaultColor>FFFFFF</descriptionDefaultColor>
                <titleBackgroundColor>000000</titleBackgroundColor>
                <titleBackgroundOpacity>0</titleBackgroundOpacity>
                <descriptionBackgroundColor>000000</descriptionBackgroundColor>
                <descriptionBackgroundOpacity>0</descriptionBackgroundOpacity>
            </settings>
            <data>';
    
    $rs = sql("select * from fotos order by orden");
    $i =0;
    while($fila = mfa($rs)){
        $i++;
        if ($i % 2 == 0){
            $tipo = "BL;TR";
        }else{
            $tipo = "TR;BL";
        }
        $xml.='<image>
            <src>galeria/'.$fila[id].'.jpg</src>
            <burns>100;100;'.$tipo.'</burns>
            <time>7</time>
        </image>';
    }
    $xml .='</data></slideshow>';
    return $xml;    
    }
    
    function especialidades($id){
        set_file("content","especialidades.html"); 
        if ((int) $id){
            $fila = Main::getRow("especialidades",$id);
            s("nombre",$fila["nombre"]);
            s("doctores",$fila["doctores"]);
            s("texto1",$fila["texto1"]);
            s("texto2",$fila["texto2"]);
            return Main::wrapper(pp("content"),$fila["nombre"],$id);  
        }
    }
    
    function blogs(){
        set_file("content","blogs.html"); 
        //latest
        
        $rs = sql("select b.doctor,p.id, p.titulo, p.contenido
                    from blogs as b RIGHT JOIN blogs_posts as p ON p.id_blog = b.id
                    order by p.id
                    limit 5");
        while($fila = mfa($rs)){
            s("doctor",$fila[doctor]);
            s("titulo",$fila[titulo]);
            s("contenido","<p>".strip_tags(Main::mb_substrws($fila[contenido],100))."...</p>");
            s("url",cleanForShortURL($fila[doctor])."/".cleanForShortURL($fila[titulo])."_$fila[id].html");
            pp("posts");            
        }
        //all
       $rs = sql("select count(*) as cuenta,b.doctor,p.id, p.titulo, p.contenido
                    from blogs as b RIGHT JOIN blogs_posts as p ON p.id_blog = b.id
                    group by p.id_blog                    
                    order by b.doctor
       ");
        while($fila = mfa($rs)){
            s("doctor",$fila[doctor]);
            s("cuenta",$fila[cuenta]);
            s("url","blog/".cleanForShortURL($fila[doctor])."_$fila[id].html");
            pp("blogs");            
        }
       
        
        return Main::wrapper(pp("content"),"Blogs",0);  
    }
    
    function post($id){
       set_file("content","post.html"); 
       $fila = Main::getRow("blogs_posts",$id);
       $doctor = Main::getField("blogs","doctor",$fila[id_blog]);
       s("titulo",$fila[titulo]);
       s("contenido",$fila[contenido]);
       s("doctor",$doctor);
       return Main::wrapper(pp("content"),"Blogs",0);   
    }
    
    function blog($id){
        set_file("content","blog.html");  
        $rs = sql("select * from blogs_posts where id_blog = '$id'");
        $doctor = Main::getField("blogs","doctor",$id);
        s("doctor",$doctor);
        while($fila = mfa($rs)){
            s("titulo",$fila[titulo]);
            s("contenido","<p>".strip_tags(Main::mb_substrws($fila[contenido],100))."...</p>");
            s("url",cleanForShortURL($doctor)."/".cleanForShortURL($fila[titulo])."_$fila[id].html"); 
            pp("posts");
        }
        return Main::wrapper(pp("content"),"Blogs $doctor",0);   
    }
  
  
} 
?>