<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {

	// Método para exibir a timeLine
	public function timeline() {

		$this->validaSessao();

		//RECUPERANDO AS POSTAGENS
		$tweet = Container::getModel("Tweet");
		$tweet->__set('id_usuario', $_SESSION['id']);
		$tweets = $tweet->getAll();	
		
		$usuario = Container::getModel('Usuario');
		$usuario->__set('id', $_SESSION['id']);
		$this->view->info_usuario = $usuario->getInfoUsuario();
		$this->view->total_post = $usuario->getTotalPosts();
		$this->view->total_seguindo = $usuario->getTotalSeguindo();
		$this->view->total_seguidores = $usuario->getTotalSeguidores();
		

		$this->view->tweets = $tweets;
		$this->render('timeline');
		

		
	}
	// Método para guardar postagens
	public function tweet(){

		$this->validaSessao();
			
		$tweet = Container::getModel("Tweet");
		$tweet->__set('tweet',$_POST['tweet']);
		$tweet->__set('id_usuario',$_SESSION['id']);
		$tweet->salvar();
		header("Location:/timeline");					
	}

	// Método para verificar se o usuário está autenticado
	public function validaSessao() { 
		// Abrir a sessão
		session_start(); 
		// Verificar se os dados de seção existem, ou seja, verificar se o usuário fez o login
		if(!isset($_SESSION['id']) || $_SESSION['id'] == '' || !isset($_SESSION['nome']) || $_SESSION['nome'] == '') {
 
			header('Location: /?login=erro'); 
		}
	}

	public function quemSeguir(){
		$this->validaSessao();
		$pesquisarPor = isset($_GET['pesquisarPor']) ?$_GET['pesquisarPor'] : '';
		$usuarios = array();

		echo "<br>";
		echo "<br>";
		echo "<br>";
		echo $_SESSION['id'];

		if ($pesquisarPor !='') {
			$usuario = Container::getModel('usuario');
			$usuario->__set('nome', $pesquisarPor);
			$usuario->__set('id', $_SESSION['id']);
			$usuarios = $usuario->getAll();
			
		}
		$this->view->usuarios = $usuarios;

		$this->render('quemSeguir');
	}

	public function acao()
	{
		$this->validaSessao();
		
		$acao = isset($_GET["acao"]) ? $_GET['acao'] : '';
		$id_seguindo = isset($_GET['id']) ? $_GET['id'] : '';
		$usuario = Container::getModel('Usuario');
		$usuario->__set('id',$_SESSION['id']);

		if ($acao == "follow") {
			$usuario->follow($id_seguindo);
		}else if($acao == "unfollow"){
			$usuario->unfollow($id_seguindo);
		}

		header("Location:/quem_seguir");

	}

	public function follow($id_seguindo)
	{
		echo "seguir usuario";
	}
	public function unfollow($id_seguindo)
	{
		echo "deixar de seguir usuario";
	}

	public function apagar()
	{
		$this->validaSessao();		
		$id_post = isset($_GET["del"]) ? $_GET['del'] : '';
		print_r($id_post);
		$postagem = Container::getModel('Tweet');		
		$postagem->delete($id_post);
		echo "deletado com sucesso";
		header("Location:/timeline");

	}

	

	
}

?>