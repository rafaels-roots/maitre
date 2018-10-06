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
	$wrs = f_bancoquery($wbancoopen,'select pro.pro_codigo, pro.pro_descricao, pro.pro_unidade, pro.pro_valor, pro.pro_ativo, pro.pro_ordem, pro.sto_codigo, pro.gru_codigo, pro.pro_visto, pro.pro_produzido, pro.pro_comanda from pro pro order by pro.pro_codigo');
	
	while($row = mysqli_fetch_assoc($wrs))  
		{
			$wresult[] = $row;
		}
	echo $_GET['callback'] . '(' . json_encode($wresult) . ')';	
	mysqli_close($wbancoopen);
   	
}

function f_select_grusto() 
{
	$wbancoopen = f_bancoopen('localhost','sisle873_root','durango2014',$_GET['banco']);
	f_bancocharset($wbancoopen, "utf8");
	
	$wresult = array();
	$wrs = f_bancoquery($wbancoopen,"select * from ( " .
									"( select 'sto' as 'tb' , sto.sto_codigo 'codigo', sto.sto_descricao 'descricao' from sto sto where sto.sto_ativo = 'S'  )" .
									" union " .
									"( select 'gru' as 'tb' , gru.gru_codigo 'codigo', gru.gru_descricao 'descricao' from gru gru where gru.gru_ativo = 'S' )) as t order by t.tb, t.descricao ");
	
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
		$wrs = f_bancoquery($wbancoopen,'insert into pro '.
		                                '(pro_descricao, pro_unidade, pro_valor, pro_ativo, pro_ordem, sto_codigo, gru_codigo, pro_visto, pro_produzido, pro_comanda)'.
										' values '.
										'(' . '"' . $wfield[0] . '"' . ','.
										'"' . $wfield[1] . '"' . ','.
										'"' . $wfield[2] . '"' . ','.
										'"' . $wfield[3] . '"' . ','.
										'"' . $wfield[4] . '"' . ','.
										'"' . $wfield[5] . '"' . ','.
										'"' . $wfield[6] . '"' . ','.
										'"' . $wfield[7] . '"' . ','.
										'"' . $wfield[8] . '"' . ','.
										'"' . $wfield[9] . '"' . ')' );
										
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
		$wrs = f_bancoquery($wbancoopen,' update pro set' .
										' pro_descricao = ' . '"' . $wfield[0] . '"' . ',' .
										' pro_unidade = ' . '"' . $wfield[1] . '"' . ',' .
										' pro_valor = ' . '"' . $wfield[2] . '"' . ',' .
										' pro_ativo = ' . '"' . $wfield[3] . '"' . ',' . 
										' pro_ordem = ' . '"' . $wfield[4] . '"' . ',' .
										' sto_codigo = ' . '"' . $wfield[5] . '"' . ',' . 
										' gru_codigo = ' . '"' . $wfield[6] . '"' . ',' .
										' pro_visto = ' . '"' . $wfield[7] . '"' . ',' .
										' pro_produzido = ' . '"' . $wfield[8] . '"' . ',' .
										' pro_comanda = ' . '"' . $wfield[9] . '"' . 
										' where pro_codigo = ' . $wfield[10] );
																			
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
		$wrs = f_bancoquery($wbancoopen,'delete from pro where pro_codigo = ' . $wfield[0] . ' and not pro_codigo in (select mov.pro_codigo from mov mov)' );
									
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

function f_select_rel_pro() 
{
	$wbancoopen = f_bancoopen('localhost','sisle873_root','durango2014',$_GET['banco']);
	f_bancocharset($wbancoopen, "utf8");
	
	$wresult = array();
	$wrs = f_bancoquery($wbancoopen,"select " .
									 "pro.pro_codigo, " .
									 "pro.pro_descricao " .
									 "from pro pro ");
	
	while($row = mysqli_fetch_assoc($wrs))  
		{
			$wresult[] = $row;
		}
	echo $_GET['callback'] . '(' . json_encode($wresult) . ')';	
	mysqli_close($wbancoopen);
   	
}

function f_print_rel_pro() 
{
	$wbancoopen = f_bancoopen('localhost','sisle873_root','durango2014',$_GET['banco']);
	f_bancocharset($wbancoopen, "utf8");
	
	$wresult = array();
	$wrs = f_bancoquery($wbancoopen," select " .
									$_GET['par1'] .
									" pro.pro_codigo as codigo ," .
									" pro.pro_descricao as descricao ," .
									" cast(sum(mov.mov_qtde*mov.mov_valorunitario) as decimal(8,2)) as mov_total ," .
									" sum(mov.mov_qtde) 'Qtde' " .
									" from mov mov, pro pro " .
									" where " .
									" mov.pro_codigo = pro.pro_codigo and " .
									 $_GET['par2'] .
									" group by " . $_GET['par3'] . "," . " pro.pro_codigo " .
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

