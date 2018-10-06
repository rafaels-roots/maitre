<?php
// Script de funções operacional
	
date_default_timezone_set('America/Fortaleza');


function f_datahora()
{
    echo $_GET['callback'] . '(' . json_encode(array ('DataHora' => date('d-m-Y H:i'))) . ')';	
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
         
    $myfile = fopen('sqlcomand.txt', 'w') or die('Unable to open file!');
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

function f_insertpedidos()
{
	$wbancoopen = f_bancoopen('localhost','sisle873_root','durango2014',$_GET['banco']);
	f_bancocharset($wbancoopen,'utf8');
	$wsql = explode('©',$_GET['sql']);
	$queryrows = 0 ;
	$wresult = array();
	$size = Count($wsql);
	for($x = 0; $x < $size - 1; $x++) 
	{
		$wfield = explode("®",$wsql[$x]);
		$wrs  = f_bancoquery($wbancoopen, 'insert into mov' .
										'('.
										'mes_codigo,'.
										'usu_nome,'.
										'pro_codigo,'.
										'mov_qtde,'.
										'mov_valorunitario,'.
										'mov_observacao,'.
										'mov_print,'.
										'mov_produzido,'.
										'mov_datahoravenda,'.
										'ter_codigo'.
										')'.
										' values '. 
										'('.
										 $_GET['mes'] . ','.
										'"' . $_GET['usu'] . '"' . ','. 
										'"' . $wfield[0] . '"' . ','. 
										'"' . $wfield[1] . '"' . ','. 
										'"' . $wfield[2] . '"' . ','. 
										'"' . $wfield[3] . '"' . ','. 
										'"' . $wfield[4] . '"' . ','. 
										'"' . $wfield[5] . '"' . ','. 
										'"' . date('Y-m-d H:i:s') . '"' . ',' .
										'"' . $_GET['ter'] . '"' . ')' );
								  
											  
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
	return true;
	
}
function f_despacharpedidos()
{
	$wbancoopen = f_bancoopen('localhost','sisle873_root','durango2014',$_GET['banco']);
	f_bancocharset($wbancoopen,'utf8');
	$wsql = explode('©',$_GET['sql']);
    $queryrows = 0 ;
	$wresult = array();
	$size = Count($wsql);
	for($x = 0; $x < $size - 1; $x++) 
	{
		$wfield    = explode("®",$wsql[$x]);
		$wrs       = f_bancoquery($wbancoopen,	'update mov force index(mov_statusmesa) set ' .
												'mov_datahoradespacho = ' . '"' . date('Y-m-d H:i:s') . '"' . ',' .
												'mov_status = "D",'.
												'mov_print = "S",'.
												'mov_produzido = "S" '.
												'where mov_status = "P" and '.
												'mes_codigo = '.  $wfield[0]  . ' and '.
												'mov_codigo = '. $wfield[1] );

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
	echo $_GET['callback'] . '(' . json_encode(array('value' => 'ok','rows' => $queryrows)) . ')';
	mysqli_close($wbancoopen);
	return true;
	
}
function f_permutarpedidos()
{
	$wbancoopen = f_bancoopen('localhost','sisle873_root','durango2014',$_GET['banco']);
	f_bancocharset($wbancoopen,'utf8');
	$wsql = explode('©',$_GET['sql']);
	$queryrows = 0 ;
	$wresult = array();
	$size = Count($wsql); 
	for($x = 0; $x < $size - 1; $x++) 
	{
		$wfield    = explode("®",$wsql[$x]);
		$_sql = !($x % 2) ? 'insert into mov '.
							'('.
							'mes_codigo,'.
							'usu_nome,'.
							'pro_codigo,'.
							'mov_qtde,'.
							'mov_valorunitario,'.
							'mov_datahoravenda,'.
							'mov_datahoradespacho,'.
							'mov_status,'.
							'mov_print,'.
							'mov_produzido,'.
							'mov_observacao'.
							')'.
							' values '. 
							'('.	
							$_GET['mes'] .','.
							'"' . $_GET['usu'] . '"' . ','. 
							'"' . $wfield[0] . '"' . ',' .
							'"' . $wfield[1] . '"' . ',' .
							'"' . $wfield[2] . '"' . ',' .
							'"' . $wfield[3] . '"' . ',' .
							'"' . $wfield[4] . '"' . ',' .
							'"' . $wfield[5] . '"' . ',' .
							'"' . $wfield[6] . '"' . ',' .
							'"' . $wfield[7] . '"' . ',' .
							'"' . $wfield[8] . '"' . ')'
							: 
		                    ' delete from mov where mov_status in ("P","D") and ' . $wfield[9] . $wfield[10] ;
		
		$wrs       = f_bancoquery($wbancoopen,$_sql);
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
	echo $_GET['callback'] . '(' . json_encode(array('value' => 'ok','rows' => $queryrows)) . ')';
	mysqli_close($wbancoopen);
	return true;
	
}
function f_cancelarpedidos()
{
	$wbancoopen = f_bancoopen('localhost','sisle873_root','durango2014',$_GET['banco']);
	f_bancocharset($wbancoopen,'utf8');
	$wsql = explode('©',$_GET['sql']);
	$queryrows = 0 ;
	$wresult = array();
	$size = Count($wsql);
	for($x = 0; $x < $size - 1; $x++) 
	{
		$wfield    = explode("®",$wsql[$x]);
		$wrs  = f_bancoquery($wbancoopen,'update mov force index (mov_statusmesa) set ' .
										'mov_status = "C" '.
										'where mov_status in ("P","D") and mes_codigo = '. $_GET['mes'] . ' and '.
										'mov_codigo = '. $wfield[0] );						  
												
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
	echo $_GET['callback'] . '(' . json_encode(array('value' => 'ok','rows' => $queryrows)) . ')';
	mysqli_close($wbancoopen);
	return true;
	
}

function f_pagamento()
{
	$wbancoopen = f_bancoopen('localhost','sisle873_root','durango2014',$_GET['banco']);
	f_bancocharset($wbancoopen,'utf8');

	$wsql = explode('©',$_GET['sql']);
    $queryrows = 0 ;
	$wdata = date('Y-m-d H:i:s');
	$wresult = array();
	$size = Count($wsql);
	for($x = 0; $x < $size - 1; $x++) 
	{
		$wfield    = explode("®",$wsql[$x]);
		$wrs    =  f_bancoquery($wbancoopen,	' insert into pgt (mes_codigo,'.
									   'usu_nome,'.
									   'pgt_datahorapagamento,'.
									   'fpg_codigo,'.
									   'pgt_valor)'.
									   ' values '.
									   '(' . $_GET['mes'] . ',' .
                                       '"' . $_GET['usu'] . '"' . ','. 
									   '"' . $wdata . '"' . ',' .
									   $wfield[0] . ',' . 
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

	$wsql = explode('©',$_GET['cod']);
	//$wconta = 1;
	$wcontasize = Count($wsql); 
	for($x = 0; $x < $wcontasize - 1 ; $x++) 
	{
		$wfield    = explode("®",$wsql[$x]);
		$wrs       = f_bancoquery($wbancoopen,	' update mov force index (mov_statusmesa) set ' .
												' mov_status = "G", '.
												' mov_print  = "S", '.
												' mov_produzido = "S", '.  
												' mov_datahorapagamento = '.'"'.$wdata.'" ,'. 
												' mov_conta = '. 1 . 
												' where mov_status in ("P","D") '.
												' and mes_codigo = '. $_GET['mes'] . ' and '.
												'mov_codigo = ' . $wfield[0] );
						  
        //$wconta = 0;
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
	
	echo $_GET['callback'] . '(' . json_encode(array('value' => 'ok','rows' => $queryrows)) . ')';
	mysqli_close($wbancoopen);
	return true; 
	
}

function f_vistoproduzido()
{
	$wbancoopen = f_bancoopen('localhost','sisle873_root','durango2014',$_GET['banco']);
	f_bancocharset($wbancoopen,'utf8');
	 
	$wsql = explode('©',$_GET['sql']);
	$queryrows = 0 ; 
	$size = Count($wsql);
	for($x = 0; $x < $size; $x++) 
	{	
		
		
		
		$wv         = substr($wsql[$x] ,strpos($wsql[$x],'v')+1,1) ;    		
		$wp			= substr($wsql[$x] ,strpos($wsql[$x],'p')+1,1) ;
		$wm         = substr($wsql[$x] ,strpos($wsql[$x],'m')+1,3) ;		
		$wc         = substr($wsql[$x] ,strpos($wsql[$x],'c')+1,strpos($wsql[$x],'v')-strpos($wsql[$x],'c')-1) ;
	    	

		
//f_bancoquery($wbancoopen,		
		$wrs    =  f_bancoquery($wbancoopen,	' update mov force index (mov_statusmesa) set ' .
												'mov_print = '.
												'"'.
												$wv.												
												'",'.
												'mov_produzido = '.
												'"'.												
												$wp.												
												'"'.
												' where  mov_status = "P" and mes_codigo = '.
												'"'.												
												$wm.												
												'"'.
												' and '.
												'mov_codigo = '.
												$wc)	;
		
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
	
	echo $_GET['callback'] . '(' . json_encode(array('value' => 'ok','rows' => $queryrows)) . ')';
	mysqli_close($wbancoopen);
	return true; 
	
}


function f_query($wbanco,$wsql) 
{
	$myfile = fopen('sql.txt', 'w') or die('Unable to open file!');
	fwrite($myfile, $wsql);
	fclose($myfile);
	
	$wbancoopen = f_bancoopen('localhost','sisle873_root','durango2014',$wbanco);
	$wresult = array();
	
	if (! $wbancoopen ) { $wresult = array ('value' => 'sql_error_open'); }
	            elseif (! f_bancocharset($wbancoopen,'utf8') ) { $wresult = array ('value' => 'sql_error_charset') ;} 
					elseif (! f_bancoautocommit($wbancoopen,false) ) { $wresult = array ('value' => 'sql_error_autocommit') ;}
							else	{
										$wrs       = f_bancoquery($wbancoopen,$wsql);
										
										while($row = mysqli_fetch_assoc($wrs))  
										{
											$wresult[] = $row;
										}
									}
	mysqli_close($wbancoopen);	
	return   $wresult; 
}
function f_select()
{
    if ( isset($_GET['sql']) && ! empty($_GET['sql']) )
	{
               $wsqlcomand['terminal'] = 'select '. 
		                          'ter.ter_codigo, '. 
		                          'ter.emp_codigo, '. 
		                          'ter.emp_banco '. 
		                          'from ter ter '. 
		                          'where '. 
		                          'ter.ter_ativo = "S" and '.
		                          'ter.ter_codigo = "' . $_GET['par1'] . '"'  ;
		                          
		                          
		$wsqlcomand['empresa'] = 'select '.
					 'emp.emp_codigo, '.
					 'emp.emp_descricao '. 
					 'from emp emp '.
					 'where '.
					 'emp.emp_codigo = "' .$_GET['par1']. '" and '.
					 'emp.emp_ativa = "S" ';
					 
		$wsqlcomand['par'] =     ' select '.
				         'par.par_garconexclusivo, '.
					 'par.par_observacaopedido, '.
					 'par.par_ordemproduto, '.
					 'par.par_mensagemrecibo1,'. 
					 'par.par_mensagemrecibo2, '.
					 'par.par_garconcomissao, '.
					 'par.par_horarioverao '.
					 'from par par		';                          
			

		$wsqlcomand['login'] =  'select '.
				        'usu.usu_nome, '.
				        'usu.usu_modulos '.  
				        'from usu usu '.
				        'where '.
				        'usu.usu_ativo = "S"  and '.
				        'usu.usu_nome = "' . $_GET['par1'] . '" and '.
				        'usu.usu_senha = "' . $_GET['par2'] . '" and '.
				        ' (position("Pedidos" in usu.usu_modulos) or '.
				        '  position("Chef" in usu.usu_modulos) or  '.
				        '  position("Caixa" in usu.usu_modulos)) ' ;		                          
							                          
		$wsqlcomand['pedidos_mesas'] =  'select ' .
			                        'mes.mes_codigo, '.
			                        'mes.mes_descricao, '.
			                        'mes.mes_ativa '.
			                        'from mes mes '.
			                        'where '.
			                        'mes.mes_ativa = "S" ' . 
			                        ($_GET['par1']=="S"? ' and '.
			                        'mes.mes_codigo not in (select mov.mes_codigo from mov mov '.
			                        'force index(mov_statusmesa) '  .
			                        'where mov.mes_codigo = mes.mes_codigo and mov.mov_status in ("P", "D") and '.
			                        'mov.usu_nome <> "' .$_GET['par2']. '")' : '') . 
									' order by mes.mes_codigo '	;
			
		$wsqlcomand['mesa_aberta_fechada'] = 'select '.
						     'mov.mes_codigo, '.
						     'mov.usu_nome '.
						     'from mov mov force index (mov_mesastatus) '.
						     'where mov.mov_status in ("P", "D") and '.
						     'mov.mes_codigo = ' . $_GET['par1'] . ' limit 1';
							
		$wsqlcomand['mov_maxcod'] = 'select ' .
									'IFNULL(max(mov.mov_codigo), 0) as mov_codigo ' .
									'from mov mov force index (mov_mesastatus) ' .
									'where mov.mes_codigo = ' . $_GET['par1'] ;

                $wsqlcomand['pro'] = 'select ' . 
                                     'pro.pro_codigo, ' .
                                     'pro.pro_descricao, ' .                                                    
                                     'pro.pro_unidade, ' . 
                                     'pro.pro_valor, ' . 
                                     'pro.pro_visto, ' .
                                     'pro.pro_produzido, ' .
									 'pro.pro_comanda, ' .	
                                     'gru.gru_codigo, ' . 
                                     'gru.gru_descricao, ' .
                                     'sto.sto_codigo, ' .
                                     'sto.sto_descricao ' .
                                     'from pro pro, gru gru, sto sto ' . 
                                     'where pro.pro_ativo = "S" and ' . 
                                     'pro.gru_codigo = gru.gru_codigo and ' . 
                                     'gru.gru_ativo = "S" and ' . 
                                     'pro.sto_codigo = sto.sto_codigo ' .
                                     'order by gru.gru_codigo , ' . $_GET['par1'] ;
		
		$wsqlcomand['imp'] = 'select ' . 
                                     'imp.imp_codigo, ' .
                                     'imp.imp_ip, ' .                                                    
                                     'imp.imp_porta, ' . 
                                     'imp.imp_local, ' . 
                                     'imp.imp_ativa, ' .
                                     'imp.imp_avancorodape ' . 
                                     'from imp imp ' . 
                                     'where imp.imp_ativa = "S" ' . 
                                     'order by imp.imp_codigo ' ;			
		
                $wsqlcomand['comandar_mesas'] = 'select ' .
												'mov.mes_codigo, ' .	
												'mes.mes_descricao, ' . 
												'(select TIMESTAMPDIFF(minute, mv.mov_datahoravenda, now()) ' . 
												'from mov mv  where  mv.mov_status = "P" and ' .
												'mv.mes_codigo = mov.mes_codigo ' . 
												'order by mv.mov_datahoravenda ASC limit 1) as mov_tempo, ' .
												'(select mv.mov_produzido from mov mv force index(mov_statusmesa) ' . 
												'where mv.mov_status = "P" and ' .
												'mv.mes_codigo = mov.mes_codigo and mv.mov_produzido = "S" limit 1) as mov_produzido ' .
												'from mov mov force index(mov_statusmesa),mes mes ' .
												'where mov.mov_status = "P" ' .	
												'and mov.mes_codigo = mes.mes_codigo ' .
												( $_GET['par1'] == 'S' ? ' and mov.usu_nome = "' . $_GET['par2']. '"' : '' ) .
												 ' group by mov.mes_codigo ' .
												 ' order by mov_tempo desc ' ;												 

		$wsqlcomand['comandar_mesas_itens'] = 'select  ' .
						'mov.mes_codigo, ' .
						'mov.mov_codigo, ' .
						'mov.mov_qtde,  ' .
						'sto.sto_codigo, ' .
						'sto.sto_descricao, ' .
						'pro.pro_codigo,  ' .
						'pro.pro_descricao, ' .
						'mov.usu_nome,  ' .
						'mov.mov_observacao,  ' .
						'mov.mov_print,  ' .
						'mov.mov_produzido, '  .
						'"N" as mov_comanda  ' .
						'from mov mov force index (mov_statusmesa),pro pro, sto sto  ' .
						'where mov.mov_status = "P" and  ' .
						'      mov.mes_codigo = ' . $_GET['par1'] . ' and  ' .
						'      mov.pro_codigo = pro.pro_codigo and  ' .
						' pro.pro_comanda = "S" and ' .
						'      pro.sto_codigo = sto.sto_codigo  ' .
						'      order by mov.mov_codigo, sto.sto_codigo ' ;

		$wsqlcomand['despachar_mesas'] = 'select ' .
										'mov.mes_codigo, ' .
										'mes.mes_descricao, ' .
										'(select TIMESTAMPDIFF(minute, ' .
										'mv.mov_datahoravenda, now() - INTERVAL ' .$_GET['par1']. ' Hour) ' .
										'from mov mv  ' .
										'where mv.mov_status = "P" and ' .
										'      mv.mes_codigo = mov.mes_codigo ' .
										'      order by mv.mov_datahoravenda ASC limit 1) as mov_tempo, ' .
										'(select mv.mov_produzido from mov mv force index(mov_statusmesa) ' .
										'      where mv.mov_status = "P" and ' .
										'      mv.mes_codigo = mov.mes_codigo and ' .
										'      mv.mov_produzido = "S" limit 1) as mov_produzido  ' .
										'      from mov mov force index(mov_statusmesa),mes mes  ' .
										'      where mov.mov_status = "P"  ' .
										'      and mov.mes_codigo = mes.mes_codigo ' .
										($_GET['par2'] == 'S' ? ' and mov.usu_nome = "' . $_GET['par3'] . '"' : ' ' ) .
										' group by mov.mes_codigo  ' .
										' order by mov_tempo desc' ;
											
				
				
		$wsqlcomand['despachar_mesas_itens'] = 'select ' .  
					'mov.mov_codigo, ' .
					'mov.mov_qtde, ' . 
					'pro.pro_descricao, ' . 
					'mov.mov_observacao, ' .
					'mov.mov_produzido, ' .
					'mov.mov_print ' .
					'from mov mov force index(mov_statusmesa), pro pro ' .
					'where mov.pro_codigo = pro.pro_codigo and ' .
					'      mov.mes_codigo = ' .$_GET['par1']. ' and ' .
					'      mov.mov_status = "P" ' .
					'      order by mov.mov_codigo ' ;
					

		$wsqlcomand['permutar_mesas'] = 'select ' .
					'mov.mes_codigo, ' . 
					'mes.mes_descricao ' .  
					'from mov mov force index(mov_statusmesa),mes mes  ' . 
					'where mov.mov_status in ("P", "D")  and ' . 
					'      mov.mes_codigo = mes.mes_codigo ' .
					($_GET['par1'] == 'S' ? ' and mov.usu_nome = "' . $_GET['par2'] . '"' : ' ' ) .
					'      group by mes.mes_codigo ' .
					'      order by mov.mes_codigo ' ;
					


		$wsqlcomand['permutar_mesas_itens'] = 'select ' .
					'mov.mov_codigo, ' .
					'mov.mov_valorunitario, ' .
					'mov.mov_datahoradespacho, ' .
					'mov.mov_datahoravenda, ' .
					'mov.mov_status, ' .
					'mov.mov_print, ' .
					'mov.mov_produzido, ' .
					'mov.mov_qtde, ' .
					'pro.pro_codigo, ' .
					'pro.pro_descricao, ' .
					'mov.mov_observacao, ' .
					'cast(mov.mov_qtde*mov.mov_valorunitario as decimal(6,2)) "mov_total" ' .
					'from mov mov force index (mov_statusmesa), pro pro ' .
					'where mov.pro_codigo = pro.pro_codigo and ' .
					'      mov.mes_codigo = ' .$_GET['par1']. ' and ' .
					'      mov.mov_status in ("P","D")  ' .
					'      order by mov.mov_codigo ' ;
					
		$wsqlcomand['mesas_destino'] = 'select '.
					'mes.mes_codigo, ' .
					'mes.mes_descricao ' .
					'from mes mes ' .
					'where mes.mes_ativa = "S" and ' .
					'      mes.mes_codigo <> ' . $_GET['par1'] ;
				
		$wsqlcomand['garcon_destino'] = 'select ' .
					'mov.usu_nome ' . 
					'from mov mov force index(mov_statusmesa) ' . 
					'where mov.mes_codigo = ' .$_GET['par1']. ' and ' .
					'mov.mov_status in ("P", "D") limit 1' ;
					
					
		$wsqlcomand['cancelar_mesas'] = 'select ' . 
					'mov.mes_codigo, ' .
					'mes.mes_descricao ' . 
					'from mov mov force index(mov_statusmesa),mes mes  ' . 
					'where mov.mov_status in ("P", "D")  and ' . 
					'      mov.mes_codigo = mes.mes_codigo ' . 
					($_GET['par1'] == 'S' ? ' and mov.usu_nome = "' . $_GET['par2'] . '"' : ' ' ) .
					'      group by mes.mes_codigo ' .  
					'      order by mov.mes_codigo ' ;
					
		$wsqlcomand['cancelar_mesas_itens'] = 'select ' .
					'mov.mov_codigo, ' .
					'mov.mov_qtde, ' .
					'pro.pro_descricao, ' .
					'mov.mov_observacao, ' .
					'mov.mov_produzido, ' .
					'mov.mov_print from mov mov force index (mov_statusmesa), pro pro ' .
					'where mov.pro_codigo = pro.pro_codigo and ' .
					'      mov.mes_codigo = ' .$_GET['par1']. ' and ' .
					'      mov.mov_status in ("P", "D") ' .  
					'      order by mov.mov_codigo ' ;									
										
		$wsqlcomand['conta_mesas'] = 'select ' .
					'mov.mes_codigo, ' .
					'mes.mes_descricao ' . 
					'from mov mov force index(mov_statusmesa),mes mes  ' .
					'where mov.mov_status in ("P", "D")  and ' .
					'      mov.mes_codigo = mes.mes_codigo ' .
					($_GET['par1'] == 'S' ? ' and mov.usu_nome = "' . $_GET['par2'] . '"' : ' ' ) .
					'      group by mes.mes_codigo ' .
					'      order by mov.mes_codigo ';
					
					
		$wsqlcomand['conta_mesas_itens'] = 'select ' .
					'mov.mov_codigo, ' .
					'mov.mov_qtde, ' .
					'pro.pro_descricao, ' .
					'mov.mov_print, ' .
					'mov.mov_produzido, ' .
					'mov.mov_observacao ' .
					'from mov mov force index (mov_statusmesa), pro pro ' .
					'where mov.pro_codigo = pro.pro_codigo and ' .
					'      mov.mes_codigo = ' .$_GET['par1']. ' and ' .
					'      mov.mov_status in ("P","D") ' .
					'      order by mov.mov_codigo ' ;
					
		$wsqlcomand['conta_mesas_itens_impressora'] = 'select ' .
					'sum(mov.mov_qtde)  "mov_qtde" , ' .
					'SUBSTR(pro.pro_descricao, 1,25) "pro_descricao",  ' .
					'format(mov.mov_valorunitario,2,"de_DE") mov_valorunitario, ' .
					'format(sum(mov.mov_qtde*mov.mov_valorunitario),2,"de_DE") "mov_valortotal" ' .
					'from mov mov force index (mov_statusmesa), pro pro ' .
					'where mov.mov_status in ("P","D")  and ' .
					'      mov.mes_codigo = ' .$_GET['par1']. ' and ' .
					'      mov.mov_codigo in (' .$_GET['par2']. ') and ' .
					'      mov.pro_codigo = pro.pro_codigo ' .
					'      group by mov.pro_codigo  ' .
					'      order by mov.pro_codigo	' ;																			
					
		$wsqlcomand['conta_mesas_caixa'] = 'select ' .
					'mov.mes_codigo, ' .
					'mov.usu_nome, ' .
					'mes.mes_descricao ' .
					'from mov mov force index(mov_statusmesa), mes mes  ' .
					'where mov.mov_status in ("P","D")  and ' .
					'      mov.mes_codigo = mes.mes_codigo ' .
					'      group by mes.mes_codigo  ' .
					'      order by mov.mes_codigo ' ;
					
		$wsqlcomand['cancelar_mesas_caixa'] = 'select ' .
					'mov.mes_codigo, ' .
					'mov.usu_nome, ' .
					'mes.mes_descricao ' .
					'from mov mov force index(mov_statusmesa), mes mes  ' .
					'where mov.mov_status in ("P","D")  and ' .
					'      mov.mes_codigo = mes.mes_codigo ' .
					'      group by mes.mes_codigo  ' .
					'      order by mov.mes_codigo ' ;
					
		$wsqlcomand['pagamento_mesas'] = 'select ' .
					'mov.mes_codigo, ' .
					'mov.usu_nome, ' .
					'mes.mes_descricao ' .
					'from mov mov force index(mov_statusmesa), mes mes  ' .
					'where mov.mov_status in ("P","D")  and ' .
					'      mov.mes_codigo = mes.mes_codigo ' .
					'      group by mes.mes_codigo  ' .
					'      order by mov.mes_codigo ' ;


		$wsqlcomand['pagamento_mesas_itens'] = 'select ' .
					'mov.mov_codigo, ' .
					'mov.mov_qtde , ' .
					'pro.pro_descricao, ' .
					'mov.mov_observacao, ' .
					'cast(mov.mov_qtde*mov.mov_valorunitario as decimal(6,2)) "mov_total"  ' .
					'from mov mov force index (mov_statusmesa), pro pro ' .
					'where mov.pro_codigo = pro.pro_codigo and ' .
					'      mov.mes_codigo = ' .$_GET['par1']. ' and ' .
					'      mov.mov_status in ("P","D") ' .
					'      order by mov.mov_codigo ' ;
					
		$wsqlcomand['fpg'] = 'select ' .
					'fpg.fpg_codigo,' .
					'fpg.fpg_descricao ' .
					'from fpg fpg ' .
					'where ' .
					'fpg.fpg_ativa = "S" ' .
					'order by fpg.fpg_codigo ' ;

		$wsqlcomand['mesas_geral_chef'] =  'select ' .
					'mov.mes_codigo, ' .
					'mov.mov_codigo, ' .
					'mes.mes_descricao, ' .
					'mov.usu_nome, ' .
					'mov.mov_observacao, ' .
					'mov.mov_qtde, ' .
					
					'(select timestampdiff(minute, mvt.mov_datahoravenda, ' .
					'now() - INTERVAL ' .$_GET['par1']. ' Hour) ' .
					'from mov mvt force index(mov_statusmesa) where mvt.mov_status = "P" and ' .
					'mvt.mes_codigo = mov.mes_codigo ' .
					'order by mvt.mov_datahoravenda asc limit 1 ) as mov_tempo, ' .
					'if ( (select "Ver..." from mov mv force index(mov_statusmesa) where ' .
					'mv.mov_status = "P" and ' .
					'mv.mes_codigo = mov.mes_codigo and ' .
					'mv.mov_print = "N" limit 1) = "Ver...","Ver...","") as mov_ver, ' .
					
					'pro.pro_descricao, ' .
					'mov.mov_print, ' .
					'mov.mov_produzido ' .
					'from mov mov force index (mov_statusmesa), mes mes, pro pro ' .
					'where mov.mov_status = "P" and  ' .
					'      mov.mes_codigo = mes.mes_codigo and  ' .
					'      mov.pro_codigo = pro.pro_codigo ' .
					'      order by mov_tempo desc ' ;
															
		$wsqlcomand['sto'] = 'select ' .
					'sto.sto_codigo, ' .
					'sto.sto_descricao ' .
					'from sto sto ' .
					'where sto.sto_ativo = "S" ' .
					'order by sto.sto_descricao ' ;																						


		$wsqlcomand['mesas_setor'] = 'select ' .
					'mov.mes_codigo, ' .
					'mov.mov_codigo, ' .
					'mes.mes_descricao, ' .
					'mov.usu_nome, ' .
					'mov.mov_observacao, ' .
					'mov.mov_qtde, ' .
					
					'(select timestampdiff(minute, mvt.mov_datahoravenda, ' .
					'now() - INTERVAL ' .$_GET['par1']. ' Hour) ' .
					'from mov mvt force index(mov_statusmesa) ' .
					'where mvt.mov_status = "P" and ' .
					'      mvt.mes_codigo = mov.mes_codigo ' .
					'      order by mvt.mov_datahoravenda asc limit 1 ) as mov_tempo,' .
					
					'if ( (select "Ver..." from mov mv force index(mov_statusmesa) where ' .
					'mv.mov_status = "P" and ' .
					'mv.mes_codigo = mov.mes_codigo and ' .
					'mv.mov_produzido = "N" limit 1) = "Ver...","Ver...","") as mov_ver, ' .
					
					'pro.pro_descricao, ' .
					'mov.mov_print, ' .
					'mov.mov_produzido ' .
					'from mov mov force index (mov_statusmesa), mes mes, pro pro ' .
					'where mov.mov_status = "P" and  ' .
					'      mov.mes_codigo = mes.mes_codigo and  ' .
					'      mov.pro_codigo = pro.pro_codigo and  ' .
					'      pro.sto_codigo in (' .$_GET['par2']. ') ' .
					'order by mov_tempo desc ' ;
					
																																
		$wresult = f_query($_GET['banco'], $wsqlcomand[ $_GET['sql'] ] );
		echo $_GET['callback'] . '(' . json_encode($wresult) . ')';
		
	}
	
}

if ( isset($_GET['action']) && !empty($_GET['action']) )
{
	$myfile = fopen('sqltotal.txt', 'w') or die('Unable to open file!');
	fwrite($myfile, $_GET['sql']);
	fclose($myfile);
	$waction = $_GET['action'];
	$wresult = $waction();
	exit;
}