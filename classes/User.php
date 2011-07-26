<?php
/*Ultimate Register*/
include_once("includes/connect.php"); 
include_once("includes/frame.php");
include_once("classes/Main.php");
 
session_start();  
class User extends Main{
     
     
     function showLogin(){
        set_file("all","admin/login.html");
        return Main::wrapper(parse("all"),"Please login",0);
     }
     function doLogin($username,$password){
        include_once("includes/connect.php"); 
        $rs = mysql_query("select * from sc_usuarios where username = '$username' and password= '$password'");
        echo mysql_error();
        if (mysql_num_rows($rs))  {
            $fila = mysql_fetch_assoc($rs);
            $_SESSION[user] = $fila;
            $_SESSION[id_user] = $fila[id];
            set_file("all","admin/bienvenido.html");
            return Main::wrapper(pp("all"),"Bienvenido",1); 
        } 
        else{
            set_file("all","admin/message.html");
            //set_var("message","There is a error in the login <br> Please verify all the information and try again");
            set_var("message","Ocurrió un error <br> Por favor verifique toda la información e inténtelo denuevo");
            s("link","index.php");
            s("texto","Volver");
            return Main::wrapper(pp("all"),"Bienvenido",0); 
        }
    }
    
    function changePass($password,$id){
        $rs= mysql_query("Update admins set password = '$password' where id = '$id'") ;
    }           
    
    function doForgot($email){

        $rs=mysql_query("select * from admins where email ='$email'");
        if ($linea = mysql_fetch_assoc($rs)){
            
            $headers  .= 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .=  "From: ".$frommail." \n\r";
            $messageb = "
                        <center>This mail was sent to you because you clicked on forgot password on ". $galname ."</center><br>
                        <br>Your password is: $linea[password]
                        <br>
                        ";


            $toemail="$email";

            mail($toemail,"De ".$mailer_name ,$messageb,$headers);
            header("location:gracias.php");
            
        
        }else{
            set_file("all","message.html");
            set_var("message","There is a error in the login <br>".'<a href="index.php">Back</a>');
            return parse("all");
        }
    }

    
    
    
    function showLoged(){
         return Main::wrapper("Bienvenido","Bienvenido");
        
    }
    
    

}//end class User
?>