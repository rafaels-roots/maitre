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
	$wrs = f_bancoquery($wbancoopen,'select gru.gru_codigo, gru.gru_descricao, gru.gru_ativo from gru gru order by gru.gru_codigo');
	
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
		$wrs = f_bancoquery($wbancoopen,'insert into gru '.
		                                '(gru_descricao,gru_ativo)'.
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
		$wrs = f_bancoquery($wbancoopen,' update gru set' .
										' gru_descricao = ' . '"' . $wfield[0] . '"' . ',' .
										' gru_ativo = ' . '"' . $wfield[1] . '"' . 
										' where gru_codigo = ' . $wfield[2] );
																			
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
		$wrs = f_bancoquery($wbancoopen,'delete from gru where gru_codigo = ' . $wfield[0] . ' and not gru_codigo in (select pro.gru_codigo from pro pro)' );
									
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



function f_select_rel_gru() 
{
	$wbancoopen = f_bancoopen('localhost','sisle873_root','durango2014',$_GET['banco']);
	f_bancocharset($wbancoopen, "utf8");
	
	$wresult = array();
	$wrs = f_bancoquery($wbancoopen,"select " .
									 "gru.gru_codigo, " .
									 "gru.gru_descricao " .
									 "from gru gru");
	
	while($row = mysqli_fetch_assoc($wrs))  
		{
			$wresult[] = $row;
		}
	echo $_GET['callback'] . '(' . json_encode($wresult) . ')';	
	mysqli_close($wbancoopen);
   
		
}

function f_print_rel_gru() 
{
	$wbancoopen = f_bancoopen('localhost','sisle873_root','durango2014',$_GET['banco']);
	f_bancocharset($wbancoopen, "utf8");
	
	$wresult = array();
	$wrs = f_bancoquery($wbancoopen," select " .
									$_GET['par1'] . 
									" gru.gru_codigo as codigo ," .
									" gru.gru_descricao as descricao ," .
									" cast(sum(mov.mov_qtde*mov.mov_valorunitario) as decimal(8,2)) as mov_total ," .
									" sum(mov.mov_qtde) 'Qtde' " .
									" from mov mov , pro pro, gru gru " .  
									" where " .
									" mov.pro_codigo = pro.pro_codigo and "  .
									" pro.gru_codigo = gru.gru_codigo and " . $_GET['par2'] .
									" group by " . $_GET['par3'] . "," . " gru.gru_codigo " .
									" order by mov.mov_datahorapagamento");
	
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

