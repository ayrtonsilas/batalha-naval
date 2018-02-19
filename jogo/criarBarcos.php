<?php require_once('security.php') ?>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Battle Web</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" type="text/css" media="screen" href="css/main.css" />
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.js"></script>
        <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet"/>
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
        <script src="js/main.js"></script>
    </head>
    <body>
        <?php  require_once("db.php"); ?>
        <h1>Criando Barcos...</h1>
            <?php 
           //verificar jogadores
           $busca = $pdo->prepare("SELECT dp.id as partida_id, u.usuario as jogador,
            u.id as jogador_id,uu.usuario as adversario, uu.id as adversario_id FROM usuario u join
            dados_partida dp on dp.usuario_id_1 = u.id left join
            usuario uu on uu.id = dp.usuario_id_2
            WHERE (u.usuario=:usuario or uu.usuario=:usuario) and dp.status <> :status LIMIT 1");
            $busca->bindValue(':usuario',$_SESSION['user']);
            $busca->bindValue(':status','Encerrado');
            $busca->execute();
            $row = $busca->fetch(PDO::FETCH_OBJ);

            if($busca->rowCount() == 0){
                echo "<script>location.href='busca.php'</script>";
                exit;
            }

                $jogadorID = $row->adversario == $_SESSION['user'] ? $row->adversario_id : $row->jogador_id;
            //verifica barcos dos jogadores
                $buscaBarcos = $pdo->prepare("SELECT u.usuario as jogador,uu.usuario as adversario FROM usuario u join
                dados_partida dp on dp.usuario_id_1 = u.id join
                usuario uu on uu.id = dp.usuario_id_2 join
                barcos_partida bp on bp.usuario_id in (dp.usuario_id_1,dp.usuario_id_2)
                WHERE u.usuario=:usuario");
                $buscaBarcos->bindValue(':usuario',$_SESSION['user']);
                $buscaBarcos->execute();
                $rowBarcos = $buscaBarcos->fetch(PDO::FETCH_OBJ);
                if($buscaBarcos->rowCount() == 2){
                    echo "<script>toastr.error('Jogadores ainda não possuem barcos!');</script>";
                }else{
                    $terminou = true;
                    while($terminou){
                        
                        $barcosRegistrados = $pdo->prepare("SELECT * FROM barcos_partida
                        WHERE usuario_id=:usuario and partida_id = :partida_id");
                        $barcosRegistrados->bindValue(':usuario',intval($jogadorID));
                        $barcosRegistrados->bindValue(':partida_id',intval($row->partida_id));
                        $barcosRegistrados->execute();
                        if($barcosRegistrados->rowCount() > 0){
                            $terminou = false;
                        }
                        else{
                            //gerar barco algoritmo
                            
                            $coordY = 0;
                            $coordX = array();
                            $matriz = array();
                            for($j = 1; $j <= 3; $j++){
                                $coordY = mt_rand(1, 8);
                                
                                //primeiro barco
                                if($coordY < 5 && $j == 1){
                                    for($i = 1; $i <= 2; $i++){
                                        $coordX[$i] = $coordY + $i;
                                    }
                                }else if($coordY >= 5 && $j == 1) {
                                    for($i = 1; $i <= 2; $i++){
                                        $coordX[$i] = $coordY - $i;
                                    }
                                }
                                if($j == 1)
                                    $matriz[$coordY] = $coordX;
                                
                                //segundo barco
                                $segundoBarco = true;
                                $coordY = mt_rand(1, 8);
                                while($segundoBarco){
                                    
                                    if(!empty($matriz[$coordY])){
                                        $coordY = mt_rand(1, 8);
                                    }else{
                                        $segundoBarco = false;
                                    }
                                }                          
                                if($coordY < 5 && $j == 2){
                                    for($i = 1; $i <= 3; $i++){
                                        $coordX[$i] = $coordY + $i;
                                    }
                                }else if($coordY >= 5 && $j == 2) {
                                    for($i = 1; $i <= 3; $i++){
                                        $coordX[$i] = $coordY - $i;
                                    }
                                }
                                if($j == 2)
                                    $matriz[$coordY] = $coordX;

                                //terceiro barco
                                $terceiroBarco = true;
                                $coordY = mt_rand(1, 8);
                                while($terceiroBarco){
                                    
                                    if(!empty($matriz[$coordY])){
                                        $coordY = mt_rand(1, 8);
                                    }else{
                                        $terceiroBarco = false;
                                    }
                                }                          
                                if($coordY < 5 && $j == 2){
                                    for($i = 1; $i <= 4; $i++){
                                        $coordX[$i] = $coordY + $i;
                                    }
                                }else if($coordY >= 5 && $j == 2) {
                                    for($i = 1; $i <= 4; $i++){
                                        $coordX[$i] = $coordY - $i;
                                    }
                                }
                                if($j == 3)
                                    $matriz[$coordY] = $coordX;
                            }

                            $quantidadeImagensBarco = 1;
                            $mudaBarco = 1;
                            $pdo->beginTransaction();
                            foreach($matriz as $key => $coluna){
                                sort($coluna);
                                foreach($coluna as $linha){

                                        $sqlInsert = "
                                        INSERT INTO barcos_partida (x,y,usuario_id,partida_id,link_imagem)
                                        VALUES(:x,:y,:usuario_id,:partida_id,:link_imagem)
                                        ";

                                        $inserirBarcos = $pdo->prepare($sqlInsert);
                                        $inserirBarcos->bindValue(':x',intval($key));
                                        $inserirBarcos->bindValue(':y',intval($linha));
                                        $inserirBarcos->bindValue(':usuario_id',intval($jogadorID));
                                        $inserirBarcos->bindValue(':partida_id',intval($row->partida_id));
                                        if($mudaBarco == 1)
                                            $inserirBarcos->bindValue(':link_imagem','/jogoweb/barcos/canoa/image_part_00'.$quantidadeImagensBarco.'.png');
                                        else if($mudaBarco == 2)
                                            $inserirBarcos->bindValue(':link_imagem','/jogoweb/barcos/barco3/image_part_00'.$quantidadeImagensBarco.'.png');
                                        else if($mudaBarco == 3)
                                            $inserirBarcos->bindValue(':link_imagem','/jogoweb/barcos/barco4/image_part_00'.$quantidadeImagensBarco.'.png');


                                        if($inserirBarcos->execute()){
                                            echo "<script>location.href='jogo.php'</script>";
                                        }else{
                                            echo "Houve um erro na conexão!";
                                            exit;
                                        }
                                        $quantidadeImagensBarco++;

                                }
                                
                                $quantidadeImagensBarco = 1;
                                $mudaBarco++;
                            }
                            $pdo->commit();
                            $terminou = false;
                            
                        }
                    }
                    

                }
                echo "<script>location.href='jogo.php'</script>";
            ?>
            

        
    </body>
</html>