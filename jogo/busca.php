<?php require_once('security.php') ?>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Battle Web</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" type="text/css" media="screen" href="css/main.css" />
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.js"></script>
		<link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet"/>
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
        <script src="js/main.js"></script>
        <script src="js/carregarTabelaBusca.js"></script>
    </head>
    <body>
    <div id="loaded"><img width="80" src="js/load.gif"></div>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <ul class="menu">
                
                    <a href="logout.php"><li>Sair</li></a>
                    <a href="busca.php"><li>Busca</li></a>
                    <span><?php echo "<b>Usuário: </b> ".$_SESSION['user'] ?></i></span>
                </ul>
            </div>
        </div>
    </div>
    <div class="container">
        <h1 class="text-center">Battle Web<br><br></h1>

        <h2 class="text-center">Partidas</h2>
    	<div class="row">
        

            <form method="post" action="" class="col-sm-12">
                <button type="submit" name="inserirJogo" class="form-control col-sm-2 btn btn-register">Criar Jogo</button>&nbsp;
            </form>
            
            
        </div>
        <br>
        <div class="row" id="carregar-table-busca">
            <?php include('atualizarBusca.php') ?>
        </div>
	</div>

    <?php
        if(isset($_POST['inserirJogo'])){
            $busca = $pdo->prepare("SELECT * FROM usuario WHERE usuario=:usuario LIMIT 1");
            $busca->bindValue(':usuario',$_SESSION['user']);
            $busca->execute();
            if($busca->rowCount() == 1){
                $row = $busca->fetch(PDO::FETCH_OBJ);
                $id = intval($row->id);
                $verificarPartida = $pdo->prepare("SELECT * FROM dados_partida WHERE usuario_id_1=:usuario_id_1 and
                usuario_id_2 is null LIMIT 1");
                $verificarPartida->bindValue(':usuario_id_1',$id);
                $verificarPartida->execute();
                if($verificarPartida->rowCount() == 1){
                    echo "<script>toastr.error('Já existe uma partida criada!');</script>";
                }else{
                    $inserir = $pdo->prepare("INSERT INTO dados_partida (usuario_id_1,status)VALUES(:usuario_id_1,:status)");
                    $inserir->bindValue(':usuario_id_1',$id);
                    $inserir->bindValue(':status',htmlentities('Disponível', ENT_QUOTES,'UTF-8'));

                    if($inserir->execute()){
                        echo "<script>location.href='criarBarcos.php'</script>";
                    }else{
                        echo "<script>toastr.error('Não foi possível criar partida!');</script>";
                    }
                }
            }
        }
        if(isset($_POST['entrar'])){
            
            $busca = $pdo->prepare("SELECT * FROM usuario WHERE usuario=:usuario LIMIT 1");
            $busca->bindValue(':usuario',$_SESSION['user']);
            $busca->execute();
            if($busca->rowCount() == 1){
                $row = $busca->fetch(PDO::FETCH_OBJ);
                $id = intval($row->id);

                $verificarPartida = $pdo->prepare("SELECT * FROM dados_partida WHERE usuario_id_1=:usuario_id_1 and
                usuario_id_2 is null LIMIT 1");
                $verificarPartida->bindValue(':usuario_id_1',$id);
                $verificarPartida->execute();

                //verificar partidas jogador
                $verificarPartidaPlay = $pdo->prepare("SELECT * FROM dados_partida WHERE (usuario_id_1=:usuario_id_1 or
                usuario_id_2 = :usuario_id_1) and status = :status LIMIT 1");
                $verificarPartidaPlay->bindValue(':usuario_id_1',$id);
                $verificarPartidaPlay->bindValue(':status',htmlentities('Disponível', ENT_QUOTES,'UTF-8'));
                $verificarPartidaPlay->execute();
                $rowVerifica = $verificarPartidaPlay->fetch(PDO::FETCH_OBJ);
                if($verificarPartida->rowCount() == 1){
                    echo "<script>toastr.error('Aguarde outro jogador!');</script>";
                }
                else if($verificarPartidaPlay->rowCount() == 1){
                    echo "<script>toastr.error('Você só pode jogar uma partida por vez!');</script>";
                }
                else{
                    $idPartida = $_POST['entrar'];
                    $atualizar = $pdo->prepare("UPDATE dados_partida SET usuario_id_2 = :usuario_id_2,
                        status = :status where id = :id");
                    $atualizar->bindValue(':usuario_id_2',$id);
                    $atualizar->bindValue(':id',$idPartida);
                    $atualizar->bindValue(':status','Andamento');

                    if($atualizar->execute()){
                        echo "<script>location.href='criarBarcos.php'</script>";
                    }else{
                        echo "<script>toastr.error('Não foi possível entrar na partida!');</script>";
                    }
                }
            }
        }else if(isset($_POST['retornar'])){
            echo "<script>location.href='criarBarcos.php'</script>";
        }
    ?>
    </body>
</html>