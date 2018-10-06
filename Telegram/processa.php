<?php

date_default_timezone_set('America/Fortaleza');


function f_bancoopen($whost,$wuser,$wpassword,$wdata)
{
	return mysqli_connect($whost,$wuser,$wpassword,$wdata);
}   

function f_bancocharset($wcon,$wcharset)
{
	return mysqli_set_charset($wcon,$wcharset);
}

function f_bancoautocommit($wcon,$wboolean)
{
	return mysqli_autocommit($wcon,$wboolean);
}

function f_bancocommit($wcon,$wboolean)
{
	return mysqli_commit($wcon,$wboolean);
}	
function f_bancoclose($wcon)
{
	mysqli_close($wcon);
}
function f_bancoqueryrows($wcon)
{
	return mysqli_affected_rows($wcon);
}

function f_bancoquery($wcon,$wsql)
{
         
	$myfile = fopen('sqlcomand.txt', 'w') or die('Unable to open file!');
	fwrite($myfile, $wsql);
	fclose($myfile);	
    return mysqli_query($wcon,$wsql); 

}

$token = "580031144:AAG6yE1-zAjDsCsT3UVOwz51VVf421ZTYRY";
$url = "https://api.telegram.org/bot".$token;

//captura a entrada
$update = file_get_contents("php://input");
//decode
$update_array = json_decode($update, TRUE);

$text = $update_array["message"]["text"];
$chat_id = $update_array["message"]["chat"]["id"];
$callback = $update_array["callback_query"];
$cbid = $update_array["callback_query"]["from"]["id"];
$cbdata = $update_array["callback_query"]["data"];

$wbancoopen = f_bancoopen('localhost','sisle873_root','durango2014','sisle873_maitrefood_usuarios');
f_bancocharset($wbancoopen,'utf8');

$wlog 	=	f_bancoquery($wbancoopen,	'delete from log' ) ;
$wrs   	= 	f_bancoquery($wbancoopen,	'select ' .
										'ide.ide_codigo, '.
										'ide.mes_codigo, '.
										'ide.blc_codigo, '.
										'blc.blc_descricao, '.
										'usu.usu_nome, '.
										'usu.usu_perfil, '.
										'usu.usu_senha, '.
										'emp.emp_codigo, '.
										'emp.emp_descricao, '.
										'emp.emp_banco, '.
										'(select blc.blc_codigo from blc blc '.
										'where blc.blc_codigo < ide.blc_codigo '.
										'order by blc.blc_codigo '.
										'desc limit 1) '.
										'as blc_codanterior, '.
										'(select blc.blc_descricao from blc blc '.
										'where blc.blc_codigo < ide.blc_codigo '.
										'order by blc.blc_codigo desc limit 1) '.
										'as blc_desanterior, '.
										'(select blc.blc_codigo from blc blc '.
										'where blc.blc_codigo > ide.blc_codigo '.
										'order by blc.blc_codigo asc limit 1) '.
										'as blc_codposterior, '.
										'(select blc.blc_descricao from blc blc '.
										'where blc.blc_codigo > ide.blc_codigo '.
										'order by blc.blc_codigo asc limit 1) '.
										'as blc_desposterior '.
										'from ide ide left join usu usu force index(ide_codigo) on ide.ide_codigo = usu.ide_codigo '.
										'left join blc blc on ide.blc_codigo = blc.blc_codigo '.
										'left join emp emp on usu.emp_codigo = emp.emp_codigo '.
										'where ide.ide_codigo = ' . "'" .$chat_id. "'" );


$wresult = array();
$wresult[] = mysqli_fetch_assoc($wrs);

if($callback) 
	{
		if($cbdata == "cadastros") 
			{
				$buttons =  array( 
									array( 
											array( "text" => "Produtos", "callback_data" => "pro" ) 
										 ) ,
								  	array( 
								  			array( "text" => "Formas de Pagamento" , "callback_data" => "fpg" ) 
								  		 ),
								  	array(
								  			array( "text" => "Parâmetros", "callback_data" => "par" )
								  		 )	  
								 ) ;
				inl_keyboard($cbid, "Menu >> Cadastros", $buttons);
				return true;
			}
	}
if ( $wresult[0]['ide_codigo'] == null) 
	{
		$wrs	= f_bancoquery($wbancoopen,	'insert into ide '.
											'(ide_codigo,blc_codigo) '.
											' values '.
											'('. '"' .$chat_id. '"' . ',1' .')' );	
		
		$wrs    = f_bancoquery($wbancoopen,	'select '.
											'blc.blc_codigo, '.
											'blc.blc_descricao '.
											'from blc blc '.
											'where blc.blc_codigo > 0 '.
											'order by blc.blc_codigo limit 1' );

		$row = mysqli_fetch_assoc($wrs);
		//sendMessage($chat_id, $row['blc_codigo'].chr(13).chr(10).$row['blc_descricao'] );
		//echo  "[". json_encode( array('resposta' => $row['blc_codigo'].chr(13).chr(10).$row['blc_descricao'] ) ). "]" ;
		sendMessage($chat_id, "<strong>Maitre</strong>\n<pre>&lt;&lt;Senha&gt;&gt;</pre>");
        return true;
	}
if(strtolower($text) == 'login')
	{
		$wrs       = f_bancoquery($wbancoopen,	'update ide set '.
												'blc_codigo = 1, '.
												'mes_codigo = null '.
												'where '.
												'ide_codigo = ' . '"' .$_POST['id']. '"' );
 								
		
		$wrs       = f_bancoquery($wbancoopen,	'select '.
												'blc.blc_codigo, '.
												'blc.blc_descricao '.
												'from blc blc '.
												'where blc.blc_codigo > 0 '.
												'order by blc.blc_codigo limit 1' );	
		
		$row = mysqli_fetch_assoc($wrs);
		sendMessage($chat_id, "<strong>Maitre</strong>\n<pre>&lt;&lt;Senha&gt;&gt;</pre>");
	}
if ( (int) $wresult[0]['blc_codigo'] == 1 )  //Login 
	{
		$wrs = f_bancoquery($wbancoopen, 'select '.
										'usu.usu_senha, '.
										'usu.usu_nome, '.
										'emp.emp_codigo, '.
										'emp.emp_descricao '.
										'from usu usu,emp emp '.
										'where '.
										'usu.emp_codigo = emp.emp_codigo and '.
										'usu.usu_senha = ' . '"' .strtolower($text). '"' );

		$row = mysqli_fetch_assoc($wrs);
		$wresult[0]['usu_nome'] = $row['usu_nome'];
		$wresult[0]['emp_descricao'] = $row['emp_descricao'];
		if ( $row['usu_senha'] == null ) 
			{
				$wrs = f_bancoquery($wbancoopen, 'select '.
												'blc.blc_codigo, ' .
												'blc.blc_descricao ' .
												'from blc blc '.
												'where blc.blc_codigo > 0 '.
												'order by blc.blc_codigo limit 1' ); 
				$row = mysqli_fetch_assoc($wrs);
				sendMessage($chat_id, "<pre>Usuário não cadastrado...</pre>\n<strong>Maitre</strong>\n<pre>&lt;&lt;Senha&gt;&gt;</pre>");
				return true;
			}

		else
			{
				/*$wrs	= f_bancoquery($wbancoopen,	'update usu set '.
													'ide_codigo = ' . '"' .$chat_id. '"'. ' '.
													'where '.
													'usu.usu_senha = ' . '"' .strtolower($text). '"' );

				$wrs	= f_bancoquery($wbancoopen,	'update ide set '.
													'blc_codigo = '.$wresult[0]['blc_desposterior'].' '.
													'where '.
													'ide_codigo = ' . '"' .$chat_id. '"' );*/

													
		
				//$blc_desposterior = str_replace("emp", $emp_descricao , $blc_desposterior);
				//$blc_desposterior = str_replace("usu", $usu_nome      , $blc_desposterior);
		

				//keyboard
				/*$buttons = array(array("Destaques","Campinas e RMC","esportes"));
				keyboard($chat_id, "MenuPrincipal", $buttons);
				return true;*/

				//inline_buttons obs: url é obrigatorio
				$buttons =  array( 
									array( 
											array( "text" => "Cadastros", "callback_data" => "cadastros" ) 
										 ) ,
								  	array( 
								  			array( "text" => "Movimentos" , "callback_data" => "movimentos" ) 
								  		 ) 
								 ) ;
				inl_keyboard($chat_id, "Menu", $buttons);
				return true; 
			}		
	} 
	
else 
	{
		sendMessage($chat_id, "<strong>Else</strong>");
	}

function sendMessage($chat_id, $message)
{
	//reply_markup
	$wsend = $GLOBALS[url] . "/sendMessage?chat_id=" . $chat_id . "&text=" . urlencode($message) . "&parse_mode=html";
	file_get_contents($wsend);
	
}

function keyboard($chat_id, $message, $buttons )
{
  	//ReplyKeyboardMarkup	
	$keyboard = array("keyboard" => $buttons,"resize_keyboard" => true,"one_time_keyboard" => true);
   	$keyboard_encode = json_encode($keyboard);
   		
	file_get_contents($GLOBALS[url] ."/sendMessage?chat_id=".$chat_id."&text=".urlencode($message)."&reply_markup=".$keyboard_encode);
}    
function inl_keyboard($chat_id, $text, $menu)
{
    //InlineKeyboardMarkup
    $inline = array("inline_keyboard" => $menu);
    $inline_encode = json_encode($inline);

    file_get_contents( $GLOBALS[url] ."/sendMessage?text=".urlencode($text)."&parse_mode=Markdown&chat_id=".$chat_id."&reply_markup=".$inline_encode );

}

//{"resize_keyboard":true,"one_time_keyboard":true,"0":{"keyboard":[["Destaques1","Destaques2","Destaques3"],["Destaques1","Destaques2","Destaques3"]] } }
//{"inline_keyboard":[ [{"text":"Cadastros"},{"text":"Movimentos"},{"text":"Relat\u00f3rios"},{"text":"Configura\u00e7\u00f5es"}] ]}

?>    