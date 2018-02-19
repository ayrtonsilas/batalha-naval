<?php require_once('security.php') ?>
<?php  require_once("db.php"); ?>

<?php

$busca = $pdo->prepare("SELECT dp.id as partida_id, u.usuario as jogador,
 u.id as jogador_id,uu.usuario as adversario, uu.id as adversario_id FROM usuario u join
 dados_partida dp on dp.usuario_id_1 = u.id join
 usuario uu on uu.id = dp.usuario_id_2
 WHERE (u.usuario=:usuario or uu.usuario=:usuario) and dp.status = :status  LIMIT 1");
 $busca->bindValue(':usuario',$_SESSION['user']);
 $busca->bindValue(':status','Andamento');
 $busca->execute();
 $row = $busca->fetch(PDO::FETCH_OBJ);
 $jogadorID = $row->jogador == $_SESSION['user'] ? $row->jogador_id : $row->adversario_id;
 $jogadorAdv = $row->adversario != $_SESSION['user'] ? $row->adversario_id : $row->jogador_id;

//turno

$buscaTurnoPartida = $pdo->prepare("SELECT * from barcos_partida_tiros where partida_id = :partida_id");

$buscaTurnoPartida->bindValue(':partida_id',$row->partida_id);
$buscaTurnoPartida->execute();

 
if(($buscaTurnoPartida->rowCount() % 2 == 0 && $row->jogador_id == $jogadorID) || (
$buscaTurnoPartida->rowCount() % 2 != 0 && $row->adversario_id == $jogadorID)){
    $sqlInsert = "
    INSERT INTO barcos_partida_tiros (x,y,usuario_id,partida_id)
    VALUES(:x,:y,:usuario_id,:partida_id)
    ";
    $coord = explode(',',$_POST['coord']);
    
    $x = $coord[0];
    $y = $coord[1];
    $inserirBarcos = $pdo->prepare($sqlInsert);
    $inserirBarcos->bindValue(':x',intval($x));
    $inserirBarcos->bindValue(':y',intval($y));
    $inserirBarcos->bindValue(':usuario_id',intval($jogadorID));
    $inserirBarcos->bindValue(':partida_id',intval($row->partida_id));
    
    if($inserirBarcos->execute()){

        $atualizar = $pdo->prepare("UPDATE barcos_partida 
            SET acertou = :acertou where x = :x and y = :y and partida_id = :partida_id and usuario_id = :usuario_id");
        $atualizar->bindValue(':x',intval($x));
        $atualizar->bindValue(':y',intval($y));
        $atualizar->bindValue(':partida_id',intval($row->partida_id));
        $atualizar->bindValue(':acertou','S');
        $atualizar->bindValue(':usuario_id',$jogadorID);
        if($atualizar->execute()){
        echo 1;
        }else{
            echo 0;
        }
    }else{
        echo 0;
    }
}else{
    echo 2;
}



?>