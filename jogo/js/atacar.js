$(function(){
    $("#loaded").hide();

});
$(document).on('click', '.btn-attack', function (event) {
    event.preventDefault();
        var coord = $(this).data('coord');
        
        $("#loaded").show();
            $.ajax({ 
            type: "POST", 
            url: 'atacarJogo.php', 
            data :  'coord='+coord,
            success: function(data) { 
                data = parseInt(data.trim());
                console.log(data);
                if(data == 1){
                    
                        $.ajax({ 
                        type: "GET", 
                        url: 'atualizarJogo.php', 
                        cache : false, 
                        success: function(z) { 
                            
                            $("#carregar-table-jogo").html(z);
                            
                            $("#loaded").hide();
                        }, 
                        error: function (xhr, status) {  
                            $("#loaded").hide();
                            toastr.error('Não foi possível atualizar'); 
                        }    
                    });
                }else if (data == 0){
                    toastr.error("Houve um erro de conexão");
                    $("#loaded").hide();
                }else{
                    toastr.info("Ainda não é sua vez");
                    $("#loaded").hide();
                }
                         
            }, 
            error: function (xhr, status) {  
                $("#loaded").hide();
                toastr.error('Não foi possível atualizar'); 
            }    
        });
});

setInterval(function(){
    
    $("#loaded").show();
        $.ajax({ 
        type: "GET", 
        url: 'atualizarJogo.php', 
        cache : false, 
        success: function(data) { 
            
            $("#carregar-table-jogo").html(data);
            
            $("#loaded").hide();
        }, 
        error: function (xhr, status) {  
            $("#loaded").hide();
            toastr.error('Não foi possível atualizar'); 
        }    
    });  
}


, 5000);



