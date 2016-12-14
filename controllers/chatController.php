<?php
class chatController extends controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $dados = array('nome'=>'');
        $c = new Chamados();        
        if(isset($_GET['id']) && !empty($_GET['id'])) {         
            if (!isset($_POST['nome']) || empty($_POST['nome'])) {
                $this->loadTemplate('newchamado', $dados);
        	exit;
            }
            $nome = addslashes($_POST['nome']);
            $id = addslashes($_GET['id']);
            $c->updateStatus($id, '1', $nome);
            $_SESSION['chatwindow'] = $id;
        }
        elseif(isset($_POST['nome']) && !empty($_POST['nome'])) {
        	$nome = addslashes($_POST['nome']);
        	$ip = $_SERVER['REMOTE_ADDR'];
        	$_SESSION['chatwindow'] = $c->addChamado($ip, $nome);
        }

        if(!isset($_SESSION['chatwindow']) || empty($_SESSION['chatwindow'])) {
        	$this->loadTemplate('newchamado', $dados);
        	exit;
        }

        $idchamado = $_SESSION['chatwindow'];
        $chamado = $c->getChamado($idchamado);
        $dados['nome'] = $chamado['nome'];

        $this->loadTemplate('chat', $dados);
    }

}