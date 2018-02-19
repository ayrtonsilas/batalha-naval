<?php session_start();?>
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
    <body id="bg-principal">
    <div class="container">
        
    	<div class="row">
			<div class="col-md-4" style="margin:0 auto !important">
				<div class="panel panel-login">
				<h1 class="text-center">Battle Web<br><br></h1>
					<div class="panel-heading">
						<div class="row">
							<div class="col-sm-6">
								<a href="#" class="active" id="login-form-link">Login</a>
							</div>
							<div class="col-sm-6">
								<a href="#" id="register-form-link">Cadastre-se</a>
							</div>
						</div>
						
					</div>
					<hr>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12">
								<form id="login-form" action="" method="post" role="form">
									<div class="form-group">
										<input type="text" name="usuario" id="username" tabindex="1" class="form-control" placeholder="Usuário" value="">
									</div>
									<div class="form-group">
										<input type="password" name="senha" id="password" tabindex="2" class="form-control" placeholder="Senha">
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-12">
												<input type="submit" name="logar" id="login-submit" class="form-control btn btn-login" value="Entrar">
											</div>
										</div>
									</div>
								</form>
								<form id="register-form" action="" method="post" role="form" style="display: none;">
									<div class="form-group">
										<input type="text" name="usuario" id="username" tabindex="1" class="form-control" placeholder="Usuário" value="">
									</div>
									<div class="form-group">
										<input type="email" name="email" id="email" tabindex="1" class="form-control" placeholder="E-mail" value="">
									</div>
									<div class="form-group">
										<input type="password" name="senha" id="password" tabindex="2" class="form-control" placeholder="Senha">
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-12">
												<input type="submit" name="cadastrar" id="register-submit" tabindex="4" class="form-control btn btn-register" value="Cadastrar">
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
		
	
	<?php
		require_once("db.php");
		if(isset($_POST['logar'])){
			$usuario = $_POST['usuario'];
			$senha = md5($_POST['senha']);
			$busca = $pdo->prepare("SELECT * FROM usuario WHERE usuario=:usuario AND senha=:senha LIMIT 1");
			$busca->bindValue(':usuario',$usuario);
			$busca->bindValue(':senha',$senha);
			$busca->execute();
			if($busca->rowCount() == 1){
				$_SESSION['user'] = $usuario;
				echo "<script>location.href='busca.php'</script>";
			}else{
				echo "<script>toastr.error('Login ou Senha Inválidos');</script>";
			}
		}
		if(isset($_POST['cadastrar'])){
			$usuario = $_POST['usuario'];
			$email = $_POST['email'];
			$senha = md5($_POST['senha']);
			
			//verificar
			$busca = $pdo->prepare("SELECT * FROM usuario WHERE usuario=:usuario OR email=:email LIMIT 1");
			$busca->bindValue(':usuario',$usuario);
			$busca->bindValue(':email',$email);
			$busca->execute();
			if($busca->rowCount() == 1){
				echo "<script>toastr.error('Já Existe o Usuário no Sistema');</script>";
			}else{
				$inserir = $pdo->prepare("INSERT INTO usuario (usuario,email,senha)VALUES(:usuario,:email,:senha)");
				$inserir->bindValue(':usuario',$usuario);
				$inserir->bindValue(':email',$email);
				$inserir->bindValue(':senha',$senha);
				if($inserir->execute()){
					echo "<script>toastr.success('Usuário Criado com Sucesso!');</script>";
				}else{
					echo "<script>toastr.error('Não foi possível cadastrar!');</script>";
				}
			}

			
		}
        
			
        ?>
    </body>
</html>