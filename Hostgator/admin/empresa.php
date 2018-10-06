<!DOCTYPE html>
<html>
    <head>
        <title>Maitre | Admin</title>    
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
    </head>
    <body>
   
        <div data-role="page" id="empresa">  
           
            <div data-role="header" data-position="fixed">
                <h1>Empresa</h1>
            </div> 
            
            <div data-role="main" class="ui-content">
			
				<div class="infoAdmin">	
					
					<p style="text-align:center;"> <span style="font-size:26px;font-weight:bold;"> Elohim Systems </span>  </p>
					
					<p style="text-align:center;padding:0;margin-top:-15px;font-size:14px;"> <span class="labelwhats">WhatsApp : </span> <span id="whats">55 85 99189-5141</span> </p>
					<p style="text-align:center;padding:0;margin-top:-15px;font-size:14px;"> <span class="labelemail">Email : </span> <span id="email">elohimsystems@hotmail.com</span> </p>
				</div>
			
			
                <form method="post">   
                    
                    <label for="emp_ter">Empresa : </label>
                    <input type="text" name="emp_ter" id="emp_ter" disabled  data-wrapper-class="ui-custom"/>
                    
                    <input type="button" class="emp_submit" value="Validar" style="text-shadow:none;margin-top:10px;">	  
                </form>
            </div>
			
            <div data-role="footer" data-position="fixed">
                <h1>Maitre | Admin</h1>
            </div>
			
	
			<script>

				$("#empresa").on("pageshow", function() 
				{	
						if(f_storagecheck("mob") == false) 
							{
								location.href="notfound.php";
								return true;	
							}
					
						$("#emp_ter").val( JSON.parse( f_storageready("json_ter") )[0]["emp_codigo"] );		
							
						$(".emp_submit").click(function()
							{
								$.mobile.loading("show");										
								$.getJSON('http://www.sisleq.com.br/maitre/admin/emp_processa.php?callback=?', {action: "f_select", banco: "sisle873_maitre_empresas", par1: JSON.parse(f_storageready("json_ter"))[0]["emp_codigo"] }, function(data)
									{
										if( data.length == 0) 
											{  
												f_storagewrite("erro_mensagem" , "Empresa não cadastrada, inativa ou com pendência..." );
												f_storagewrite("page_redirect" , "empresa.php" );
												$ .mobile.changePage ("error.php");
											} 
										else 
											{						 		
												f_storagewrite("json_emp", JSON.stringify(data) );
												$ .mobile.changePage ("login.php");	
											}
									});
									
							});
					});	
			</script>
			
			
        </div>  
	
</body>

</html>
