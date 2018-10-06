<?php
// Script de funções
	
date_default_timezone_set('America/Fortaleza');

function f_datahora()
{	
    echo $_GET['callback'] . '(' . json_encode(array ('DataHora' => date("d-m-Y H:i"))) . ')';	
    return true;
}

function f_bancoopen($whost,$wuser,$wpassword,$wdata)
{	
	return mysqli_connect($whost,$wuser,$wpassword,$wdata);
}

function f_bancocharset($wcon,$wcharset)
{	
	return mysqli_set_charset($wcon,$wcharset);
}

function f_bancoquery($wcon,$wsql)
{	
	$myfile = fopen("sqlcomand.txt", "w") or die("Unable to open file!");
	fwrite($myfile, $wsql);
	fclose($myfile);	
    return mysqli_query($wcon,$wsql);
}
	
function f_bancoqueryrows($wcon)
{	
	return mysqli_affected_rows($wcon);
}
/* Seletor de Funções */

function f_select_mes() 
{
	$wbancoopen = f_bancoopen('localhost','sisle873_root','durango2014',$_GET['banco']);
	f_bancocharset($wbancoopen, "utf8");
	
	$wresult = array();
	$wrs = f_bancoquery($wbancoopen,"select mov.mes_codigo," . 			
									 " mov.usu_nome," .
									 " mes.mes_descricao" . 						
									 " from mov mov force index(mov_statusmesa), mes mes " . 	
									 " where mov.mov_status in ('P','D') " .		
									 " and mov.mes_codigo = mes.mes_codigo" .		
									 " group by mes.mes_codigo " .					
									 " order by mov.mes_codigo");
	
	while($row = mysqli_fetch_assoc($wrs))  
		{
			$wresult[] = $row;
		}
	echo $_GET['callback'] . '(' . json_encode($wresult) . ')';	
	mysqli_close($wbancoopen);
   	
}

function f_select_gar() 
{
	$wbancoopen = f_bancoopen('localhost','sisle873_root','durango2014',$_GET['banco']);
	f_bancocharset($wbancoopen, "utf8");
	
	$wresult = array();
	$wrs = f_bancoquery($wbancoopen," select usu.usu_nome " .
								  " from usu usu " .
								  " where usu.usu_modulos like '%Pedidos%' and " .
								  " usu.usu_nome not like '%" . $_GET['par1'] . "%' and " .
								  " usu.usu_ativo = 'S' ");
	
	while($row = mysqli_fetch_assoc($wrs))  
		{
			$wresult[] = $row;
		}
	echo $_GET['callback'] . '(' . json_encode($wresult) . ')';	
	mysqli_close($wbancoopen);
   	
}


function f_permutargarcon()
{
	$wbancoopen = f_bancoopen("localhost","sisle873_root","durango2014",$_GET["banco"]);
	
	/*if (! $wbancoopen ) 
	{ 
		echo $_GET['callback']. '(' . json_encode ( array ('value' => 'sql_error_open')  ) . ')';
		return true;
	}
	
	if (! f_bancocharset($wbancoopen,"utf8") )
	{
		mysqli_close($wbancoopen);
		echo $_GET['callback']. '(' . json_encode ( array ('value' => 'sql_error_charset') ) . ')';
		return true;
	}
	
	if (! f_bancoautocommit($wbancoopen,false) )
	{
		mysqli_close($wbancoopen);
		echo $_GET['callback']. '(' . json_encode ( array ('value' => 'sql_error_autocommit') ) . ')';
		return true;
	}*/
	$queryrows = 0 ;
	$wrs       = f_bancoquery($wbancoopen,	"update mov force index (mov_mesastatus) set " .
												"usu_nome = " . "'" . $_GET["gar"] ."' ".
												"where mov_status in ('P','D') and mes_codigo =        ".$_GET["mes"]	);						  
												
												
		
	if (! $wrs )
	{ 
		mysqli_rollback($wbancoopen);
		mysqli_close($wbancoopen);
		echo $_GET['callback'] . '(' . json_encode ( array ('value' => 'sql_error_command') ) . ')';
		return true;
	}
	else
	{
		$queryrows += f_bancoqueryrows($wbancoopen);
	}
		
	
	/*if (! mysqli_commit($wbancoopen))
	{
		if (! mysqli_rollback($wbancoopen))
		{
			mysqli_close($wbancoopen);
			echo $_GET['callback'] . '(' . json_encode ( array ('value' => 'sql_error_rollback') ) . ')';
			return true;
		}
		else
		{
			mysqli_close($wbancoopen);
			echo $_GET['callback'] . '(' . json_encode ( array ('value' => 'sql_error_commit') ) . ')';
			return true;
		}
	}*/
	echo $_GET['callback'] . '(' . json_encode ( array('value' => 'ok','rows' => $queryrows) ) . ')' ;
	mysqli_close($wbancoopen);
	return true;
	
}

if ( isset($_GET['action']) && !empty($_GET['action']) )
{
	$waction = $_GET['action'];
	$wresult = $waction();
	exit;
}

