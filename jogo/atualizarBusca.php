<div class="col-sm-12">
<table class="table">
    <thead class="thead-dark">
    <tr>
        <th scope="col" class="text-left">Numero</th>
        <th scope="col" class="text-left">Jogador 1</th>
        <th scope="col" class="text-left">Jogador 2</th>
        <th scope="col" class="text-left">Status</th>
        <th scope="col" class="text-center">Entrar</th>
    </tr>
    </thead>
    <tbody>
    <?php 
    require_once("db.php");
    $sql = "SELECT dp.id,u.usuario as user1, dp.status as status,uu.usuario as user2 FROM dados_partida dp 
        join usuario u on u.id = dp.usuario_id_1
        left join usuario uu on uu.id = dp.usuario_id_2 where dp.status <> :status";
    $tabela = $pdo->prepare($sql);
    $tabela->bindValue(':status','Encerrado');
    $tabela->execute();
    
    while($row=$tabela->fetch(PDO::FETCH_OBJ)) {
        
        ?>
    <tr>
        <td><?php echo $row->id ?></td>
        <td><?php echo $row->user1 ?></td>
        <td><?php echo $row->user2 ?></td>
        <td><?php echo $row->status ?></td>
        <td>
            <?php if(html_entity_decode($row->status,ENT_QUOTES,'UTF-8') == 'Disponível'){ ?>
            <form style="padding:0;margin:0" method="post" action="" class="col-sm-12">
            
            <button value="<?php echo $row->id ?>" 
             type="submit" name="entrar" class="form-control col-sm-12 btn btn-register">Entrar</button>
            </form>
            <?php }else if(html_entity_decode($row->status,ENT_QUOTES,'UTF-8') == 'Andamento') { ?>
            <form style="padding:0;margin:0" method="post" action="" class="col-sm-12">
            
                <button value="<?php echo $row->id ?>" 
                type="submit" name="retornar" class="form-control col-sm-12 btn btn-register">Jogar</button>
            </form>
            <?php } ?>
        </td>
    </tr>
    <?php }
    ?>
    
    </tbody>
</table>
<?php if($tabela->rowCount() == 0){
        echo "<div class='text-center'>Ainda não existem partidas para entrar</div>";
    } ?>
</div>
<br><br><br><br><br><br><br><br>
<h2 class="text-center col-sm-12">Ranking</h2>

<div class="col-sm-12">
<table class="table">
    <thead class="thead-dark">
        <tr>
            <th scope="col" class="text-left">Jogador</th>
            <th scope="col" class="text-left">Pontos</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $sql = "SELECT p.*,u.usuario as usuario FROM pontuacao p join usuario u on u.id = p.usuario_id
            order by pontos desc";
            $tabela = $pdo->prepare($sql);
            $tabela->execute();
            while($row=$tabela->fetch(PDO::FETCH_OBJ)) {
                ?>
                <tr>
                <td><?php echo $row->usuario ?></td>
                <td><?php echo $row->pontos ?></td>
                </tr>
                <?php
            }
        ?>
    </tbody>
</table>
</div>
