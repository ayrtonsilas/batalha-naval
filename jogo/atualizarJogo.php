<?php  require_once("security.php"); ?>
<?php  require_once("db.php"); ?>
<?php 
            //verificar jogadores
            $busca = $pdo->prepare("SELECT dp.id as partida_id, u.usuario as jogador,
            u.id as jogador_id,uu.usuario as adversario, uu.id as adversario_id FROM usuario u join
            dados_partida dp on dp.usuario_id_1 = u.id join
            usuario uu on uu.id = dp.usuario_id_2
            WHERE (u.usuario=:usuario or uu.usuario=:usuario) and dp.status = :status  LIMIT 1");
            $busca->bindValue(':usuario',$_SESSION['user']);
            $busca->bindValue(':status','Andamento');
            $busca->execute();
            $row = $busca->fetch(PDO::FETCH_OBJ);
            if($busca->rowCount() == 0){
                echo "<script>location.href='busca.php'</script>";
                exit;
            }
            $jogadorID = $row->adversario == $_SESSION['user'] ? $row->adversario_id : $row->jogador_id;

            //mostrar de quem e a vez
            $buscaTurnoPartida = $pdo->prepare("SELECT * from barcos_partida_tiros where partida_id = :partida_id");

            $buscaTurnoPartida->bindValue(':partida_id',$row->partida_id);
            $buscaTurnoPartida->execute();

            $turno = false;
            if((($buscaTurnoPartida->rowCount() % 2 == 0 && $row->jogador_id == $jogadorID) || (
                $buscaTurnoPartida->rowCount() % 2 != 0 && $row->adversario_id == $jogadorID))){
                $turno = true;
                    
            }

            //condição de vitoria
            $fimjogo = $pdo->prepare('SELECT usuario_id,count(*) as qtd from barcos_partida 
            WHERE usuario_id in (:jogador_1,:jogador_2) and partida_id = :partida_id
            and acertou = :acertou group by usuario_id');
            $fimjogo->bindValue(':partida_id',$row->partida_id);
            $fimjogo->bindValue(':jogador_1',$row->jogador_id);
            $fimjogo->bindValue(':jogador_2',$row->adversario_id);
            $fimjogo->bindValue(':acertou','S');
            $fimjogo->execute();
            $totalPontuacao = [];
            $totalJogador = $totalAdversario = 0;
            while($rowFimJogo = $fimjogo->fetch(PDO::FETCH_OBJ)){
                $totalPontuacao[$rowFimJogo->usuario_id] = $rowFimJogo->qtd;
            }
            if($fimjogo->rowCount() > 0){
                $totalJogador = isset($totalPontuacao[$row->jogador_id]) ? $totalPontuacao[$row->jogador_id] : 0;
                $totalAdversario = isset($totalPontuacao[$row->adversario_id]) ? $totalPontuacao[$row->adversario_id] : 0;
                if($totalJogador == 9){
                    terminarJogo($pdo,$row->partida_id,$row->jogador_id);
                }else if($totalAdversario == 9){
                    terminarJogo($pdo,$row->partida_id,$row->adversario_id);
                }
            }

            ?>
            <h2>
                <?php echo $row->jogador." <b style='color:#59B2E6'>".$totalJogador ?>  </b>
                <b>X</b>  
                <?php echo !empty($row->adversario) ? "<b style='color:#59B2E6'>".$totalAdversario."</b> ".$row->adversario : 'Aguardando...' ?>
                
            </h2>
            <p><?php if($turno) echo "<i style='padding:5px' class='btn-success'>Sua vez!</i>"; 
                    else echo "<i  style='padding:5px' class='btn-danger'>Aguarde sua vez!</i>"; ?></p>
            
            <table class="table-edit">
            <tr>
                <td class="column-block">A</td>
                <td class="column-block">B</td>
                <td class="column-block">C</td>
                <td class="column-block">D</td>
                <td class="column-block">E</td>
                <td class="column-block">F</td>
                <td class="column-block">G</td>
                <td class="column-block">H</td>
                <td class="column-block">I</td>
            </tr>
            <?php
            $buscaBarcos = $pdo->prepare("SELECT bp.* from barcos_partida bp join
            dados_partida dp on dp.id = bp.partida_id
            where  bp.usuario_id = :jogador_id and 
            bp.partida_id = :partida_id and dp.status = :status");
            $buscaBarcos->bindValue(':jogador_id',intVal($jogadorID));
            $buscaBarcos->bindValue(':partida_id',intVal($row->partida_id));
            $buscaBarcos->bindValue(':status','Andamento');

            $buscaBarcos->execute();


            $buscaTiros = $pdo->prepare("SELECT bp.* from barcos_partida_tiros bp join
            dados_partida dp on dp.id = bp.partida_id
            where  bp.usuario_id = :jogador_id and 
            bp.partida_id = :partida_id and dp.status = :status");
            $buscaTiros->bindValue(':jogador_id',intVal($jogadorID));
            $buscaTiros->bindValue(':partida_id',intVal($row->partida_id));
            $buscaTiros->bindValue(':status','Andamento');

            $buscaTiros->execute();

            if($buscaBarcos->rowCount() > 0){
                
                $barcosCoord = [];
                $tirosCoord = [];

                while($rowAdd = $buscaBarcos->fetch(PDO::FETCH_OBJ)) {
                    $barcosCoord[intVal($rowAdd->x)][intVal($rowAdd->y)] = [$rowAdd->link_imagem,$rowAdd->acertou];

                }
                while($rowAdd = $buscaTiros->fetch(PDO::FETCH_OBJ)) {
                    $tirosCoord[intVal($rowAdd->x)][intVal($rowAdd->y)] = 1;

                }

                
                for($i = 1; $i <= 8; $i++){
                    
                    echo "<tr>
                    <td class='column-block'>$i</td>
                    ";
                    for($j = 1; $j <= 8; $j++){
                        //$matriz[$i][$j] = 0;
                        echo "<td class='boat'><button data-coord='$i,$j' class='btn-attack'>";
                        if(isset($barcosCoord[$i]) && isset($barcosCoord[$i][$j])){
                           if($barcosCoord[$i][$j][1] == 'S')
                                echo "<img src='".$barcosCoord[$i][$j][0]."'>";
                        }
                        else if(isset($tirosCoord[$i]) && isset($tirosCoord[$i][$j])){
                            echo "<img src='barcos/water.png'>";
                        }

                        
                        echo "</button></td>";
                    }
                    echo "</tr>";
                }
                
                
                
            }

            function terminarJogo($pdo,$idJogo,$usuario){
                $usuario = intVal($usuario);
                $idJogo = intVal($idJogo);
                $buscaPontos = $pdo->prepare("SELECT * from pontuacao where usuario_id = :jogador_id");
                $buscaPontos->bindValue(':jogador_id',$usuario);
                $buscaPontos->execute();
                if($buscaPontos->rowCount() > 0){
                    $buscaPontos = $buscaPontos->fetch(PDO::FETCH_OBJ);
                    $pontos = intVal($buscaPontos->pontos);
                    $pontos++;
                    $atualizarPontos = $pdo->prepare("UPDATE pontuacao set pontos = :pontos where
                    usuario_id = :usuario_id");
                    $atualizarPontos->bindValue(":usuario_id",$usuario);
                    $atualizarPontos->bindValue(":pontos",$pontos);
                    $atualizarPontos->execute();
                }else{
                    $inserirPontos = $pdo->prepare("INSERT INTO pontuacao (usuario_id,pontos)values(:usuario_id,:pontos)");
                    $inserirPontos->bindValue(":usuario_id",$usuario);
                    $inserirPontos->bindValue(":pontos",1);
                    $inserirPontos->execute();
                }
                $atualizar = $pdo->prepare("UPDATE dados_partida 
                SET status = :status where id = :partida_id");
                $atualizar->bindValue(':partida_id',$idJogo);
                $atualizar->bindValue(':status','Encerrado');
                $atualizar->execute();
                 
                $limpar = $pdo->prepare("DELETE FROM barcos_partida where partida_id = :partida_id");
                $limpar->bindValue(':partida_id',$idJogo);
                $limpar->execute();
                $limpar = $pdo->prepare("DELETE FROM barcos_partida_tiros where partida_id = :partida_id");
                $limpar->bindValue(':partida_id',$idJogo);
                $limpar->execute();

                //echo "<script>location.href='busca.php'</script>";
               // exit;   
            }
            ?>
            
            </table>