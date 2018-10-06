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

function f_bancoautocommit($wcon,$wboolean)
{	
	return mysqli_autocommit($wcon,$wboolean);
}	

function f_bancoquery($wcon,$wsql)
{	
	$myfile = fopen("sqlcomand.txt", "w") or die("Unable to open file!");
	fwrite($myfile, $wsql);
	fclose($myfile);	
        return mysqli_query($wcon,$wsql);
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
/* Seletor de Funções */
function f_insertupdatedelete($wbanco,$wsql)
{	
	$myfile = fopen("sql.txt", "w") or die("Unable to open file!");
	fwrite($myfile, $wsql);
	fclose($myfile);
	$wbancoopen = f_bancoopen("localhost","sisle873_root","durango2014",$wbanco);
	
	if (! $wbancoopen ) 
	{ 	
		return array ('value' => 'sql_error_open'); 	
	}
	
	if (! f_bancocharset($wbancoopen,"utf8") )	
	{		
		mysqli_close($wbancoopen);	
		return array ('value' => 'sql_error_charset');
	}
	if (! f_bancoautocommit($wbancoopen,false) )	
	{		
		mysqli_close($wbancoopen);	
		return array ('value' => 'sql_error_autocommit');	
	}
	
	$wsql = explode("©",$wsql);
	$queryrows = 0 ;
	for($x = 0; $x < Count($wsql); $x++) 
	{		
			$wrs = f_bancoquery($wbancoopen,$wsql[$x]);
		if (! $wrs )
			{ 	
				mysqli_rollback($wbancoopen);
				mysqli_close($wbancoopen);	
				return array ('value' => 'sql_error_command');
			}	
		else
			{
				$queryrows += f_bancoqueryrows($wbancoopen);
			}
	}

	
	if (! mysqli_commit($wbancoopen))
		{
			if (! mysqli_rollback($wbancoopen))
				{	
					mysqli_close($wbancoopen);
					return array ('value' => 'sql_error_rollback');
				}		
			else
				{		
					mysqli_close($wbancoopen);
					return array ('value' => 'sql_error_commit');
				}		
		}
	mysqli_close($wbancoopen);
	return  array('value' => 'ok','rows' => $queryrows);
	
}
function f_query($wbanco,$wsql) 
{	
	$myfile = fopen("sql.txt", "w") or die("Unable to open file!");
	fwrite($myfile, $wsql);
	fclose($myfile);
	$wbancoopen = f_bancoopen("localhost","sisle873_root","durango2014",$wbanco);
	$wresult = array();
	
	if (! $wbancoopen ) 
	{ 
		$wresult = array ('value' => 'sql_error_open'); 
	}
	elseif (! f_bancocharset($wbancoopen,"utf8") ) 
	{ 
		$wresult = array ('value' => 'sql_error_charset');
	} 
	elseif (! f_bancoautocommit($wbancoopen,false) ) 
	{
		$wresult = array ('value' => 'sql_error_autocommit');
	}
	else
	{
		$wrs = f_bancoquery($wbancoopen,$wsql);										
		while($row = mysqli_fetch_assoc($wrs))  
		{
			$wresult[] = $row;
		}
	}
	mysqli_close($wbancoopen);
	return   $wresult; 
}
function f_permutargarcon()
{
	$wbancoopen = f_bancoopen("localhost","sisle873_root","durango2014",$_GET["banco"]);
	
	if (! $wbancoopen ) 
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
	}
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
		
	
	if (! mysqli_commit($wbancoopen))
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
	}
	mysqli_close($wbancoopen);
	echo $_GET['callback'] . '(' . json_encode ( array('value' => 'ok','rows' => $queryrows) ) . ')' ;
	return true;
	
}

function f_select()
{  
  if ( isset($_GET["sql"]) && ! empty($_GET["sql"]) )
  {	


        $wsqlcomand['terminal'] = "select ". 
		                          "ter.ter_codigo, ". 
		                          "ter.emp_codigo, ". 
		                          "ter.emp_banco ". 
		                          "from ter ter ". 
		                          "where ". 
		                          "ter.ter_ativo = 'S' and ".
		                          "ter.ter_codigo = " . $_GET["par1"]  ;
		                          
		                          
		$wsqlcomand['empresa'] = "select ".
					 "emp.emp_codigo, ".
					 "emp.emp_descricao ". 
					 "from emp emp ".
					 "where ".
					 "emp.emp_codigo = " .$_GET["par1"]. " and ".
					 "emp.emp_ativa = 'S' ";

		$wsqlcomand['par'] = " select ".
							 "par.par_garconexclusivo, ".
							 "par.par_observacaopedido, ".
							 "par.par_ordemproduto, ".
							 "par.par_mensagemrecibo1,". 
							 "par.par_mensagemrecibo2, ".
							 "par.par_garconcomissao, ".
							 "par.par_horarioverao ".
							 "from par par		"; 	
							 
		$wsqlcomand['login'] = "select usu.usu_nome" .
								" from usu usu" . 
								" where usu.usu_ativo = 'S' " .
								" and usu.usu_nome = " . $_GET["par1"] . 
								" and usu.usu_senha = " . $_GET["par2"] .
								" and (position('Admin' in usu.usu_modulos)) ";
		
		$wsqlcomand['mes'] = "select mes.mes_codigo," .
							"mes.mes_descricao, " .
							"mes.mes_ativa " .
							" from mes mes" .
							" order by mes.mes_codigo ";
							
		$wsqlcomand['sto'] = "select sto.sto_codigo," . 
							"sto.sto_descricao, " . 
							"sto.sto_ativo, " .
							"sto.imp_codigo " .
							" from sto sto" .
							" order by sto.sto_codigo "	;
		
		$wsqlcomand['select_impsto'] = " select imp.imp_codigo, " .
									   " imp.imp_local " .
									   " from imp imp " .
									   " where imp.imp_ativa = 'S' " .
									   " order by imp.imp_codigo ";
							
		$wsqlcomand['gru'] = "select gru.gru_codigo," .
							"gru.gru_descricao, " .
							"gru.gru_ativo " .
							" from gru gru" .
							" order by gru.gru_codigo ";
							
		$wsqlcomand['pro'] = "select pro.pro_codigo, " .
							" pro.pro_descricao, " .
							" pro.pro_unidade, " .
							" pro.pro_valor, " .
							" pro.pro_ativo, " .
							" pro.pro_ordem, " .
							" pro.sto_codigo, " .
							" pro.gru_codigo, " .
							" pro.pro_visto, " .
							" pro.pro_produzido, " .
							" pro.pro_comanda " .
							" from pro pro " .
							" order by pro.gru_codigo , pro.pro_descricao, pro.pro_codigo "	;
							
		$wsqlcomand['imp'] = "select imp.imp_codigo," . 
							"imp.imp_ip, " .
							"imp.imp_porta," .
							"imp.imp_local, " .
							"imp.imp_ativa," .
							"imp.imp_avancorodape" .
							" from imp imp" .
							" order by imp.imp_codigo ";
							
		$wsqlcomand['fpg'] = "select fpg.fpg_codigo," . 
							"fpg.fpg_descricao, " .
							"fpg.fpg_ativa " .
							" from fpg fpg" .
							" order by fpg.fpg_codigo ";

		$wsqlcomand['grusto'] = "select * from ( " .
								"( select 'sto' as 'tb' , sto.sto_codigo 'codigo', sto.sto_descricao 'descricao' from sto sto where sto.sto_ativo = 'S'  )" .
								" union " .
								"( select 'gru' as 'tb' , gru.gru_codigo 'codigo', gru.gru_descricao 'descricao' from gru gru where gru.gru_ativo = 'S' )) as t order by t.tb, t.descricao ";
							 
		$wsqlcomand['usu'] = "select usu.usu_nome," . 
							"usu.usu_senha, " .
							"usu.usu_modulos," .
							"usu.usu_ativo " .
							" from usu usu" .
							" order by usu.usu_nome ";
							
		$wsqlcomand['ocupacao'] =   "select " . 
									"mes.mes_descricao, " .
									"format(sum(mov.mov_qtde*mov.mov_valorunitario),2,'de_DE') as mov_valortotal " .
									" from mov mov force index (mov_statusmesa), mes mes " .
									" where mov.mov_status in('P','D') " .
									" and mov.mes_codigo = mes.mes_codigo " .
									" group by mov.mes_codigo ";
									
		$wsqlcomand['permutar_mesas'] = "select mov.mes_codigo," . 			
										 " mov.usu_nome," .
										 " mes.mes_descricao" . 						
										 " from mov mov force index(mov_statusmesa), mes mes " . 	
										 " where mov.mov_status in ('P','D') " .		
										 " and mov.mes_codigo = mes.mes_codigo" .		
										 " group by mes.mes_codigo " .					
										 " order by mov.mes_codigo";
										 
		$wsqlcomand['permutar_garcon'] = " select usu.usu_nome " .
										  " from usu usu " .
										  " where usu.usu_modulos like '%Pedidos%' and " .
										  " usu.usu_nome not like '%" . $_GET['par1'] . "%' and " .
										  " usu.usu_ativo = 'S' ";
		
		$wsqlcomand['print_pro'] = "select " . 
									"gru.gru_codigo," .
									"gru.gru_descricao," .
									"pro.pro_codigo," .
									"SUBSTR(pro.pro_descricao, 1,30) 'pro_descricao'," .
									"pro.pro_unidade," .
									"format(pro.pro_valor,2,'de_DE') 'pro_valor'," .
									"pro.pro_ativo " .
									"from pro pro,gru gru " .
									"where pro.gru_codigo = gru.gru_codigo and " .
									"pro.pro_ativo = 'S' " . 
									"order by gru.gru_codigo,pro.pro_codigo";	
		
		$wsqlcomand['select_rel_fpg'] = "select " .
									 "fpg.fpg_codigo, " .
									 "fpg.fpg_descricao " .
									 "from fpg fpg ";	
		
		$wsqlcomand['print_rel_fpg'] = " select " .
										$_GET['par1'] . 
										" fpg.fpg_codigo as codigo ," .
										" fpg.fpg_descricao as descricao ," .
										" cast(sum(pgt.pgt_valor) as decimal(8,2)) as mov_total ," .
										" sum(1) 'Qtde' " .
										" from pgt pgt, fpg fpg  " .  
										" where " .
										" pgt.fpg_codigo = fpg.fpg_codigo "  .
										 $_GET['par2'] .
										" group by " . $_GET['par3'] . "," . " fpg.fpg_codigo " .
										" order by pgt.pgt_datahorapagamento";
										
		$wsqlcomand['select_rel_gru'] = "select " .
										 "gru.gru_codigo, " .
										 "gru.gru_descricao" .
										 "from gru gru ";

		$wsqlcomand['print_rel_gru'] = " select " .
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
										" order by mov.mov_datahorapagamento";
										
		$wsqlcomand['select_rel_mes'] = "select " .
										 "mes.mes_codigo, " .
										 "mes.mes_descricao " .
										 "from mes mes "; 								
		
		$wsqlcomand['print_rel_mes'] = " select " .
										$_GET['par1'] . 
										" mes.mes_codigo as 'codigo' ," .
										" mes.mes_descricao as 'descricao' ," .
										" cast(sum(mov.mov_qtde*mov.mov_valorunitario) as decimal(8,2)) as mov_total ," .
										" sum(mov.mov_conta) 'Qtde' " .
										" from mov mov, mes mes " .
										" where mov.mes_codigo = mes.mes_codigo and " . $_GET['par2'] .
										" group by " . $_GET['par3'] . "," . " mes.mes_codigo " .
										" order by mov.mov_datahorapagamento";
										
		$wsqlcomand['select_rel_pro'] = "select " .
										 "pro.pro_codigo, " .
										 "pro.pro_descricao " .
										 "from pro pro ";	

		$wsqlcomand['print_rel_pro'] = " select " .
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
										" order by " . $_GET['par4'] ;
		
		$wsqlcomand['select_rel_usu'] = "select " .
										 "usu.usu_nome " .
										 "from usu usu ";

		$wsqlcomand['print_rel_usu'] = " select " .
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
										" order by " . $_GET['par4'];

		$wresult = f_query($_GET['banco'], $wsqlcomand[ $_GET['sql'] ] );
		if (sizeof($wresult)>0)
		{		
			echo $_GET['callback'] . '(' . json_encode($wresult) . ')';
		}	
		else
		{
			 $wresult[] =  array ('value' => 'sql_record_null');
			 echo $_GET['callback'] . '(' . json_encode($wresult) . ')';
		}
   }	
	else	
	{
		$wresult[] = array ('value' => 'sql_command_empty');
		echo $_GET['callback'] . '(' . json_encode($wresult) . ')';
	}
}

function f_multquery()
{ 
    if ( isset($_GET["sql"]) && ! empty($_GET["sql"]) )
	{
		   $wresult = f_insertupdatedelete($_GET["banco"],$_GET["sql"]);
		   echo $_GET['callback'] . '(' . json_encode( $wresult ) . ')';
	}
	else	
	{	
	   $wresult[] = array ('value' => 'sql_command_empty');
	   echo $_GET['callback'] . '(' . json_encode( $wresult ) . ')';
	}
}
//  ******************* Bloco de inicio do processa **************************
if ( isset($_GET["action"]) && !empty($_GET["action"]) )
{	
	$myfile = fopen("sqltotal.txt", "w") or die("Unable to open file!");
	fwrite($myfile, $_GET["sql"]);
	fclose($myfile);
	$waction = $_GET["action"];
	$wresult = $waction();
	exit;
}