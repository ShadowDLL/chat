<?php
class Mensagens extends model {
    
    public function sendMessage($idchamado, $origem, $msg, $hora){
        $array = array();
        if(!empty($idchamado) && !empty($msg)) {
            $sql = ($origem == "cliente")?"INSERT INTO mensagens SET id_chamado = '$idchamado', mensagem = '$msg', origem = '0', data_enviado = STR_TO_DATE('$hora', '%H:%i'), visto_cliente = '1', visto_suporte = '0', lida = '0'":"INSERT INTO mensagens SET id_chamado = '$idchamado', mensagem = '$msg', origem = '1', data_enviado = STR_TO_DATE('$hora', '%H:%i'), visto_cliente = '0', visto_suporte = '1', lida = '0'";    
            $this->db->query($sql);
            $array['id'] = $this->db->lastInsertId();
        }
        return $array;
    } 
    
    public function getMessage($idchamado, $area){
        $array = array();
        if (!empty($idchamado) && !empty($area)) {
            
            $sql = ($area == "cliente")?"SELECT * FROM mensagens WHERE id_chamado = '$idchamado' AND origem = '1' AND visto_cliente = '0'":"SELECT * FROM mensagens WHERE id_chamado = '$idchamado' AND origem = '0' AND visto_suporte= '0'";
            $sql = $this->db->query($sql);
            if ($sql->rowCount() > 0) {
                $array = $sql->fetchAll();
                foreach ($array as $key => $value) {
                    $sql = ($area == "cliente")?"UPDATE mensagens SET visto_cliente = '1' WHERE id = '".$value['id']."'":"UPDATE mensagens SET visto_suporte = '1' WHERE id = '".$value['id']."'";
                    $array[$key]['data_enviado'] = date('H:i', strtotime($value['data_enviado']));
                    $this->db->query($sql);
                }
            }
        }
        return $array;
    }
    
    public function receivedMessage($idchamado, $area){
        $array = array();
        if (!empty($idchamado) && !empty($area)) {        
            $sql = ($area == "cliente")?"SELECT id FROM mensagens WHERE id_chamado = '$idchamado' AND origem = '0' AND visto_suporte = '1'":"SELECT id FROM mensagens WHERE id_chamado = '$idchamado' AND origem = '1' AND visto_cliente= '1'";
            $sql = $this->db->query($sql);
            if ($sql->rowCount() > 0) {
                $array = $sql->fetchAll();
                foreach ($array as $key => $value) {
                    $sql = ($area == "cliente")?"UPDATE mensagens SET visto_suporte = '2' WHERE id = '".$value['id']."'":"UPDATE mensagens SET visto_cliente = '2' WHERE id = '".$value['id']."'";
                    $this->db->query($sql);
                }
            }
        }
        return $array;
    }
    
    public function readMessage($id){
        $array = array();
        if (!empty($id)) {        
            $sql = "UPDATE mensagens SET lida = '1' WHERE id = '$id'"; 
            if ($this->db->query($sql)) {
                $array['id'] = $id;
            }
        }
        return $array;
    }
    
    public function getReadMessage($id){
        $array = array();
        if (!empty($id)) {        
            $sql = "SELECT id FROM mensagens WHERE id = '$id' AND lida = '1'"; 
            $sql = $this->db->query($sql);
            if ($sql->rowCount() > 0) {
                $array['id'] = $id;
            }
        }
        return $array;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

	public function sendMessage2($idchamado, $origem, $msg) {
		if(!empty($idchamado) && !empty($msg)) {
			$sql = "INSERT INTO mensagens SET id_chamado = '$idchamado', mensagem = '$msg', origem = '$origem', data_enviado = NOW()";
			$this->db->query($sql);
		}
	}

	public function getMessage2($idchamado, $lastmsg) {
		$array = array();

		$sql = "SELECT * FROM mensagens WHERE id_chamado = '$idchamado' AND data_enviado > '$lastmsg'";
		$sql = $this->db->query($sql);

		if($sql->rowCount() > 0) {
			$array = $sql->fetchAll();

			foreach($array as $chave => $valor) {
				$array[$chave]['data_enviado'] = date('H:i', strtotime($valor['data_enviado']));
			}
		}

		$c = new Chamados();
		$area = $_SESSION['area'];
		$c->updateLastMsg($idchamado, $area);

		return $array;
	}

}