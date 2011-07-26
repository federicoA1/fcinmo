<?php
function is_digito($digito)
{
    //       Comprueba si $digito es un digito o no
 if($digito=='0' || $digito=='1' || $digito=='2' || $digito=='3' || $digito=='4' ||
    $digito=='5' || $digito=='6' || $digito=='7' || $digito=='8' || $digito=='9'){return TRUE;
    }else{return FALSE;}
}

function is_vacio($string)
{
    //       Chequea si es un string vacio
    $str = trim($string);
    if(empty($str))
    {
        return(false);
    }
    else return(1);
    }

function _is_valid($string, $min_length, $max_length, $regex)
{
    //       Chequea si es un string vacio
    $str = trim($string);
    if(empty($str))
    {
        return(false);
    }

    //       Chequea si es un string con caracteres enteramente de tipos
    if(!ereg("^$regex$", $string))
    {
        return(false);
    }

    //      chequea por la entrada opcional de longitud
    $strlen = strlen($string);
    if(($min_length != 0 && $strlen < $min_length) || ($max_length != 0 && $strlen > $max_length))
    {
        return(false);
    }

    //      OK
    return(true);

}


function is_alpha($string, $min_length = 0, $max_length = 0)
//          is_alpha(string un_string, int min_long, int max_long)
//          Chequea si un_string esta compuesto por caracteres alfabeticos unicamente
//          chequea si posee una longitud entre min_long y max_long
{
    $ret = _is_valid($string, $min_length, $max_length, "[[:alpha:],.ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏĞÑ(){}=?¡¿!-_/ÒÓÔÕÖØÙÚÛÜİŞßàáâãäæçèéêëìíîïğñòóôõöøùúûüış[:space:]]+");

    return($ret);
}

function is_numerico($string, $min_length = 0, $max_length = 0)
//          is_numerico(string un_string, int min_long, int max_long)
//          Chequea si un_string esta compuesto por caracteres numericos unicamente
//          chequea si posee una longitud entre min_long y max_long
{
    $ret = _is_valid($string, $min_length, $max_length, "[[:digit:].]+");

    return($ret);
}

function is_alphanumeric($string, $min_length = 0, $max_length = 0)
//          is_numerico(string un_string, int min_long, int max_long)
//          Chequea si un_string esta compuesto por caracteres alfa_numericos
//          chequea si posee una longitud entre min_long y max_long
{
    $ret = _is_valid($string, $min_length, $max_length, "[a-z0-9A-Z,.ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏĞÑ(){}=?¡¿!-_/ÒÓÔÕÖØÙÚÛÜİŞßàáâãäæçèéêëìíîïğñòóôõöøùúûüış[:space:]]+");

    return($ret);
}

function is_email($user_email) {
	$chars = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i";
	if(strstr($user_email, '@') && strstr($user_email, '.')) {
		if (preg_match($chars, $user_email)) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}


function is_clean_text($string, $min_length = 0, $max_length = 0)
//          is_clean_text(string un_string, int min_long, int max_long)
//          chequea si un_string esta compuesto por  una linea de texto limpio
//          chequea si posee una longitud entre min_long y max_long
{
    $ret = _is_valid($string, $min_length, $max_length, "[a-zA-Z0-9[:space:].,ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏĞÑÒÓÔÕÖØÙÚÛÜİŞßàáâãäåæçèéêëìíîïğñòóôõöøùúûüış`´'&¿?!]+");

    return($ret);
}

function contains_bad_words($string)
//          comprueba que la entrada no contenga alguna palabra no deseada
{
    $bad_words = array(
                    'anal',           'ass',        'bastard',       'puta',
                    'bitch',          'blow',       'butt',          'trolo',
                    'cock',           'clit',       'cock',          'pija',
                    'cornh',          'cum',        'cunnil',        'verga',
                    'cunt',           'dago',       'defecat',       'cajeta',
                    'dick',           'dildo',      'douche',        'choto',
                    'erotic',         'fag',        'fart',          'trola',
                    'felch',          'fellat',     'fuck',          'puto',
                    'gay',            'genital',    'gosh',          'pajero',
                    'hate',           'homo',       'honkey',        'pajera',
                    'horny',          'vibrador',   'jew',           'lesbiana',
                    'jiz',            'kike',       'kill',          'eyaculacion',
                    'lesbian',        'masoc',      'masturba',      'anal',
                    'nazi',           'nigger',     'nude',          'mamada',
                    'nudity',         'oral',       'pecker',        'teta',
                    'penis',          'potty',      'pussy',         'culo',
                    'rape',           'rimjob',     'satan',         'mierda',
                    'screw',          'semen',      'sex',           'bastardo',
                    'shit',           'slut',       'snot',          'pito',
                    'spew',           'suck',       'tit',           'putito',
                    'twat',           'urinat',     'vagina',
                    'viag',           'vibrator',   'whore',
                    'xxx'
    );

    //      verifica
    for($i=0; $i<count($bad_words); $i++)
    {
        if(strstr(strtoupper($string), strtoupper($bad_words[$i])))
        {
            return(true);
        }
    }

    //      OK
    return(false);
}


function contains_phone_number($string)
//          comprueba que la entrada contenga algún numero telefónico
{
     //     verifica
     if(ereg("[[:digit:]]{3,10}[\. /\)\(-]*[[:digit:]]{6,10}", $string))
     {
        return(true);
     }

     //     OK
     return(false);
}
function es_anio($string,$a=1,$b=1)
{
    
	return  ereg("[1-2][0-9][0-9][0-9]",$string);
}
function es_alfanumerico($string, $min_length, $max_length)
{

 
 if (strlen($string) <= $max_length and strlen($string)>= $min_length)
 		  if (ereg("[a-z0-9A-Z,.áéíñóú[:space:]]+",$string)) 
 		          	return 1;
			
			return 0;
}

 function is_url($string){
     if (ereg("http://[a-z0-9A-Z,.áéíñóú[:space:]]+",$string)) 
                 return 1;
        
    return 0;
}
function is_decimal($numero){
     if (ereg("[,.0-9]+",$numero)) 
                 return 1;
        
    return 0;
}



?>