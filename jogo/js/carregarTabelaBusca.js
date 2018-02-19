$(function(){
    $("#loaded").hide();
});

setInterval(function(){
    
    $("#loaded").show();
        $.ajax({ 
        type: "GET", 
        url: 'atualizarBusca.php', 
        cache : false, 
        success: function(data) { 
            $("#loaded").hide();
            $("#carregar-table-busca").html(data);
        }, 
        error: function (xhr, status) {  
            $("#loaded").hide();
            toastr.error('Não foi possível atualizar'); 
        }    
    });  
}


, 6000);

