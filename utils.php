<?php
function safeHtmlString($chaine,$ncaracteres=100, $stripHtmlEntities=true)  
{  
     $chaineres=wordwrap($chaine, $ncaracteres, " ", 1);  
     	 
  	 if($stripHtmlEntities)  
     {  
      	$chaineres=htmlentities($chaineres);  
     }  
     
     $chaineres = str_replace(">","&#62;",$chaineres);
  	 $chaineres = str_replace("<","&#60;",$chaineres); 
  	 $chaineres = str_replace("'","&#39;",$chaineres);
  	 $chaineres = str_replace('"',"&#34;",$chaineres);
  	      
     $chaineres=preg_replace("`\r\n`","<br>",$chaineres);  
     $chaineres=preg_replace("`\n`","<br>",$chaineres);  
     return $chaineres;       
}

function safeJavascriptString($chaine,$ncaracteres=100, $stripHtmlEntities=true)  
{  
     $chaineres=wordwrap($chaine, $ncaracteres, " ", 1);  
     	 
  	 if($stripHtmlEntities)  
     {  
      	$chaineres=htmlentities($chaineres);  
     }  
     
     $chaineres = str_replace(">","&#62;",$chaineres);
  	 $chaineres = str_replace("<","&#60;",$chaineres); 
  	 $chaineres = str_replace("'","\&#39;",$chaineres);
  	 $chaineres = str_replace('"',"\&#34;",$chaineres);
  	      
     $chaineres=preg_replace("`\r\n`","<br>",$chaineres);  
     $chaineres=preg_replace("`\n`","<br>",$chaineres);  
     return $chaineres;       
}

$gl_racine_data_tmp = 'tmp/';

function save_data($name,$data)
{
	global $gl_racine_data_tmp;
	$fs = fopen($gl_racine_data_tmp.$name,"w");		
	if($fs)
	{
		fwrite($fs,$data);
		fclose($fs);
	}		
}

function load_data($name)
{
	global $gl_racine_data_tmp;
	$data = '';
	if(file_exists($gl_racine_data_tmp.$name))
	{
		$data = file($gl_racine_data_tmp.$name);
	}				
			
	return $data[0];
}

?>
<SCRIPT TYPE="text/javascript">
function limite(zone,max) 
{ 
	if(zone.value.length>=max)
	{
		zone.value=zone.value.substring(0,max);
	} 
} 
function ouvrir_popup(title,contenu) 
{ 
	fenetre = open("",'popup','width=600,height=400,toolbar=no,scrollbars=yes,resizable=yes');
	fenetre.document.write("<html><head><title>");
	fenetre.document.write(title);
	fenetre.document.write("</title></head><body>");
	fenetre.document.write(contenu);
	fenetre.document.write("</body></html>");
	fenetre.document.close();
} 
</script> 