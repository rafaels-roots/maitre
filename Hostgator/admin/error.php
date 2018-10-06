<!DOCTYPE html>
<html>
    <head>
        <title>Maitre | Admin</title>    
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
    </head>
    <body>
   
        <div data-role="page" id="error">  
           
            <div data-role="header">
                <h1>Mensagem</h1>
            </div> 
            
            <div data-role="main" class="ui-content">
			
                <p id="error_Descricao"></p>
				
				<a href="#" id="error_redirect" class="ui-btn ui-corner-all" style="background-color:#4887cc;color:#ffffff;text-shadow:none;">Voltar</a> 
			
            </div>
          
			
            <div data-role="footer" data-position="fixed">
                <h1>Maitre | Admin</h1>
            </div>
			
			<script>
				$("#error").on("pageshow", function() 
					{
						if(f_storagecheck("mob") == false) 
							{
								location.href="notfound.php";
								return true;	
							}
						
						$("#error_Descricao").text( f_storageready("erro_mensagem") );
						$("#error_redirect").attr({"href":   f_storageready("page_redirect")  });
						
					});
			</script>
			
        </div>  
		
		
</body>

</html>
