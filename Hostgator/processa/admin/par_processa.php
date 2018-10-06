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
	$wrs = f_bancoquery($wbancoopen,'select par.par_garconexclusivo, par.par_observacaopedido, par.par_ordemproduto, par.par_mensagemrecibo1, par.par_mensagemrecibo2, par.par_garconcomissao, par.par_horarioverao from par par');
	
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
		$wrs = f_bancoquery($wbancoopen,'insert into par '.
		                                '(par_garconexclusivo,par_observacaopedido, par_ordemproduto, par_mensagemrecibo1, par_mensagemrecibo2, par_garconcomissao, par_horarioverao)'.
										' values '.
										'(' . '"' . $wfield[0] . '"' . ','.
										'"' . $wfield[1] . '"' . ','.
										'"' . $wfield[2] . '"' . ','.
										'"' . $wfield[3] . '"' . ','.
										'"' . $wfield[4] . '"' . ','.
										'"' . $wfield[5] . '"' . ','.
										'"' . $wfield[6] . '"' . ')' );
										
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
		$wrs = f_bancoquery($wbancoopen,' update par set' .
										' par_garconexclusivo = ' . '"' . $wfield[0] . '"' . ',' .
										' par_observacaopedido = ' . '"' . $wfield[1] . '"' . ',' . 
										' par_ordemproduto = ' . '"' . $wfield[2] . '"' . ',' .
										' par_mensagemrecibo1 = ' . '"' . $wfield[3] . '"' . ',' .
										' par_mensagemrecibo2 = ' . '"' . $wfield[4] . '"' . ',' . 
										' par_garconcomissao = ' . '"' . $wfield[5] . '"' . ',' .
										' par_horarioverao = ' . '"' . $wfield[6] . '"'  );
																			
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

