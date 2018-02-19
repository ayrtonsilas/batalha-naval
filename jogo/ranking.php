<?php require_once('security.php') ?>
<?php require_once("db.php"); ?>
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
        <h1 class="text-center">Battle Web</h1>
        <div class="container"  id="carregar-table-jogo">
            
        </div>
        <br>
    </body>
</html>