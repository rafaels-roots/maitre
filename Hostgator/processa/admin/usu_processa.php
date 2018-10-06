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
	$wrs = f_bancoquery($wbancoopen,'select usu.usu_nome, usu.usu_senha, usu.usu_modulos, usu.usu_ativo from usu usu order by usu.usu_nome');
	
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
		$wrs = f_bancoquery($wbancoopen,'insert into usu '.
		                                '(usu_nome,usu_senha, usu_modulos, usu_ativo)'.
										' values '.
										'(' . '"' . $wfield[0] . '"' . ','.
										'"' . $wfield[1] . '"' . ','.
										'"' . $wfield[2] . '"' . ','.
										'"' . $wfield[3] . '"' . ')' );
										
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
		$wrs = f_bancoquery($wbancoopen,' update usu set' .
										' usu_senha = ' . '"' . $wfield[0] . '"' . ',' .
										' usu_modulos = ' . '"' . $wfield[1] . '"' . ',' . 
										' usu_ativo = ' . '"' . $wfield[2] . '"' . 
										' where usu_nome = ' . '"' . $wfield[3] . '"' );
																			
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
		$wrs = f_bancoquery($wbancoopen,'delete from usu where usu_nome = ' . '"' .  $wfield[0] . '"' . ' and usu_nome <> ' . '"' . $wfield[1] . '"' );
									
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


function f_select_rel_usu() 
{
	$wbancoopen = f_bancoopen('localhost','sisle873_root','durango2014',$_GET['banco']);
	f_bancocharset($wbancoopen, "utf8");
	
	$wresult = array();
	$wrs = f_bancoquery($wbancoopen,'select usu.usu_nome from usu usu');
	
	while($row = mysqli_fetch_assoc($wrs))  
		{
			$wresult[] = $row;
		}
	echo $_GET['callback'] . '(' . json_encode($wresult) . ')';	
	mysqli_close($wbancoopen);
   
		
}

function f_print_rel_usu() 
{
	$wbancoopen = f_bancoopen('localhost','sisle873_root','durango2014',$_GET['banco']);
	f_bancocharset($wbancoopen, "utf8");
	
	$wresult = array();
	$wrs = f_bancoquery($wbancoopen," select " .
									$_GET['par1']  .
									" mov.usu_nome 'codigo', "  .
									" mov.usu_nome 'descricao', "  .
									" cast(sum(mov.mov_qtde*mov.mov_valorunitario) as decimal(8,2)) as mov_total, " .
									" sum(mov.mov_conta) 'Qtde' " .
									" from " . 
									" mov mov " .
									" where " . $_GET['par2'] .
									" group by " .
									$_GET['par3'] . "," .
									" mov.usu_nome " .
									" order by " . $_GET['par4']);
	
	while($row = mysqli_fetch_assoc($wrs))  
		{
			$wresult[] = $row;
		}
	echo $_GET['callback'] . '(' . json_encode($wresult) . ')';	
	mysqli_close($wbancoopen);
   
		
}



if ( isset($_GET['action']) && !empty($_GET['action']) )
{
	$waction = $_GET['action'];
	$wresult = $waction();
	exit;
}

