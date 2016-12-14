<?php
class ajaxController extends controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $dados = array();
    }
    
    public function getNome(){
        $dados = array();
        $id = addslashes($_SESSION['chatwindow']);
        $area = addslashes($_SESSION['area']);
        $c = new Chamados();
    	$dados['nome'] = $c->getNome($id, $area);
        echo json_encode($dados);
    }
    
    public function getchamado() {
    	$dados = array(); 	
    	$c = new Chamados();
    	$dados['chamados'] = $c->getChamados();
    	echo json_encode($dados);
    }
    
    public function sendMessage(){
        $dados = array();
        if (isset($_POST['msg']) && !empty($_POST['msg'])) {
            $msg = addslashes($_POST['msg']);
            $hr = addslashes($_POST['hr']);
            $id = addslashes($_SESSION['chatwindow']);
            $origem = addslashes($_SESSION['area']);
            $m = new Mensagens();
            $dados['id'] = $m->sendMessage($id, $origem, $msg, $hr);       
        }
        echo json_encode($dados);
    }
    
    public function getMessage(){
        $dados = array();
        $idchamado = addslashes($_SESSION['chatwindow']);
        $area = $_SESSION['area'];
        $m = new Mensagens();
        $dados['mensagens'] = $m->getMessage($idchamado, $area);
        echo json_encode($dados);
    }
    
    public function receivedMessage(){
        $dados = array();
        $idchamado = addslashes($_SESSION['chatwindow']);
        $area = $_SESSION['area'];
        $m = new Mensagens();
        $dados['id'] = $m->receivedMessage($idchamado, $area);
        echo json_encode($dados);
    }
    
    public function readMessage(){
        $dados = array();
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $id = addslashes($_POST['id']);
            $m = new Mensagens();
            $dados['id'] = $m->readMessage($id);
        } 
        echo json_encode($dados);
    }
    
    public function getReadMessage(){
        $dados = array();
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $id = addslashes($_POST['id']);
            $m = new Mensagens();
            $dados['id'] = $m->getReadMessage($id);
        }
        echo json_encode($dados);
    }
}