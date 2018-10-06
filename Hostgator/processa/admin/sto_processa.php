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

function f_select() 
{
	$wbancoopen = f_bancoopen('localhost','sisle873_root','durango2014',$_GET['banco']);
	f_bancocharset($wbancoopen, "utf8");
	
	$wresult = array();
	$wrs = f_bancoquery($wbancoopen,'select sto.sto_codigo, sto.sto_descricao, sto.sto_ativo from sto sto order by sto.sto_codigo');
	
	while($row = mysqli_fetch_assoc($wrs))  
		{
			$wresult[] = $row;
		}
	echo $_GET['callback'] . '(' . json_encode($wresult) . ')';	
	mysqli_close($wbancoopen);
   
		
}

function f_insert()
{	
	$wbancoopen = f_bancoopen("localhost","sisle873_root","durango2014",$_GET['banco']);
	$wsql = explode("©",$_GET['sql']);
	$queryrows = 0 ;
	$wresult = array();
	$size = Count($wsql);
	for($x = 0; $x < $size - 1; $x++) 
	{		
		$wfield = explode("®",$wsql[$x]);
		$wrs = f_bancoquery($wbancoopen,'insert into sto '.
		                                '(sto_descricao,sto_ativo)'.
										' values '.
										'(' . '"' . $wfield[0] . '"' . ','.
										'"' . $wfield[1] . '"' . ')' );
										
		if (! $wrs )
			{ 	
				$wresult[] =  array ('value' => 'sql_error_command','rows' => $queryrows);
			}	
		else
			{
				$queryrows += f_bancoqueryrows($wbancoopen);
				$wresult[0] =  array('value' => 'ok','rows' => $queryrows);
			}
	}
	echo $_GET['callback'] . '(' . json_encode($wresult) . ')';
	mysqli_close($wbancoopen);
	
	
}
function f_update()
{	
	$wbancoopen = f_bancoopen("localhost","sisle873_root","durango2014",$_GET['banco']);
	$wsql = explode("©",$_GET['sql']);
	$queryrows = 0 ;
	$wresult = array();
	$size = Count($wsql);
	for($x = 0; $x < $size - 1; $x++) 
	{		
		$wfield = explode("®",$wsql[$x]);
		$wrs = f_bancoquery($wbancoopen,' update sto set' .
										' sto_descricao = ' . '"' . $wfield[0] . '"' . ',' .
										' sto_ativo = ' . '"' . $wfield[1] . '"' . 
										' where sto_codigo = ' . $wfield[2] );
																			
		if (! $wrs )
			{ 	
				$wresult[] =  array ('value' => 'sql_error_command','rows' => $queryrows);
			}	
		else
			{
				
				$queryrows += f_bancoqueryrows($wbancoopen);
				$wresult[0] =  array('value' => 'ok','rows' => $queryrows);
			}
	}

	mysqli_close($wbancoopen);
	echo $_GET['callback'] . '(' . json_encode($wresult) . ')';
	
}
function f_delete()
{	
	$wbancoopen = f_bancoopen("localhost","sisle873_root","durango2014",$_GET['banco']);
	$wsql = explode("©",$_GET['sql']);
	$queryrows = 0 ;
	$wresult = array();
	$size = Count($wsql);
	for($x = 0; $x < $size - 1; $x++) 
	{		
		$wfield = explode("®",$wsql[$x]);
		$wrs = f_bancoquery($wbancoopen,'delete from sto where sto_codigo = ' . $wfield[0]);
									
		if (! $wrs )
			{ 	
				$wresult[] =  array ('value' => 'sql_error_command','rows' => $queryrows);
			}	
		else
			{
				
				$queryrows += f_bancoqueryrows($wbancoopen);
				$wresult[0] =  array('value' => 'ok','rows' => $queryrows);
			}
	}

	mysqli_close($wbancoopen);
	echo $_GET['callback'] . '(' . json_encode($wresult) . ')';
	
}

if ( isset($_GET['action']) && !empty($_GET['action']) )
{
	$waction = $_GET['action'];
	$wresult = $waction();
	exit;
}

