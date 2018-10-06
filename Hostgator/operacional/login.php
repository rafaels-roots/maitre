<!DOCTYPE html>
<html>
    <head>
        <title>Maitre | Operacional</title>    
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
    </head>
    <body>
     
        <div data-role="page" id="login">  
           
            <div data-role="header" data-position="fixed">
                <h1>Login</h1>
            </div> 
            
            <div data-role="main" class="ui-content">
			
				<div class="infoOperacional">	
					<p > <span class="labelmac">Terminal : </span> <span id="mac">...</span> </p>
					<p > <span class="labelemp">Empresa :</span> <span id="emp">...</span> </p>
				</div>
				
                <form method="post" >
                    
                    <label for="usu_nome">Usuário : </label>
                    <input type="text" name="usu_nome" id="usu_nome" data-wrapper-class="ui-custom" />
                    
                    <label for="usu_senha">Senha: </label>
                    <input type="password" id="usu_senha" name="usu_senha" data-wrapper-class="ui-custom" />
                   
                    <input type="button"  value="Entrar" class="usu_submit" style="text-shadow:none;">	  
                </form>
            </div>
			
            <div data-role="footer" data-position="fixed">
                <h1>Maitre | Operacional</h1>
            </div>
        
		
			<script>
				$("#login").on("pageshow", function() 
					{ 
						
						if(f_storagecheck("mob") == false) 
							{
								location.href="notfound.php";
								return true;	
							}		 
				
						f_requestparameters();
						
						$("#mac").text( JSON.parse( f_storageready("json_ter") )[0]["ter_codigo"] );
						$("#emp").text( JSON.parse( f_storageready("json_emp") )[0]["emp_descricao"] );
						
						$(".usu_submit").click(function() 
							{
								$.mobile.loading("show");
								if(ElementsValidation([{"Id" : "usu_nome", "Caracteres" : "1"}, {"Id" : "usu_senha", "Caracteres" : "1"}], "minlength") == false) 
									{
										f_storagewrite("erro_mensagem" , "Transação não concluída, Campo Inválido..." );
										f_storagewrite("page_redirect" , "login.php" );
										$ .mobile.changePage ("error.php");
										return true;
									}
								$.getJSON('http://www.sisleq.com.br/maitre/operacional/processa_operacional.php?callback=?', {action: "f_select", banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"], sql: "login", par1 : $("#usu_nome").val()  , par2:  $("#usu_senha").val()  }, function(data)
									{
										if( data.length == 0  )
											{  
												f_storagewrite("erro_mensagem" , "Favor checar (usuário,senha,ativo,nível)..." );
												f_storagewrite("page_redirect" , "login.php" );
												$ .mobile.changePage ("error.php");
											}
											
										else 
											{						 		
												f_storagewrite("json_usu" , JSON.stringify(data));	  
												$ .mobile.changePage ("menu_principal.php");	   
											}
									});				
												
										
							});
						
					
						$('#usu_nome, #usu_senha').keypress(function(e)
							{
								if ( e.keyCode == 13 )
									{
										return false;
									} 
							});
					});	
			</script>
				
		</div>
		
		
		
		
		
</body>
</html>