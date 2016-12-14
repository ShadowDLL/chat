<?php
class Chamados extends model {

    public function getChamados() {
        $array = array();
        $sql = "SELECT * FROM chamados WHERE status IN (0,1)";
        $sql = $this->db->query($sql);
        if($sql->rowCount() > 0) {
            $array = $sql->fetchAll();
        }
        return $array;
    }

    public function getNome($id, $area){
        $array = array();
        if(!empty($id)) {
            $sql = ($area == "cliente")?"SELECT nome, nome_suporte FROM chamados WHERE id = '$id'":"SELECT nome, nome_suporte FROM chamados WHERE id = '$id'";
            $sql = $this->db->query($sql);
            if($sql->rowCount() > 0) {
                $array = $sql->fetch();
            }
            $array['nome'] = ($area == "cliente")?$array['nome_suporte']:$array['nome'];
        }
        return $array;
    }

    public function getChamado($id) {
        $array = array();

        if(!empty($id)) {
            $sql = "SELECT * FROM chamados WHERE id = '$id'";
            $sql = $this->db->query($sql);
            if($sql->rowCount() > 0) {
                $array = $sql->fetch();
            }
        }
        return $array;
    }

    public function updateStatus($id, $status, $nome) {
        if(!empty($id) && !empty($status)) {
            $sql = "UPDATE chamados SET status = '$status', nome_suporte = '$nome' WHERE id = '$id'";
            $this->db->query($sql);
        }
    }

public function addChamado($ip, $nome) {
    $id = 0;
    $sql = "INSERT INTO chamados SET ip = '$ip', nome = '$nome', data_inicio = NOW(), status = '0'";
    $sql = $this->db->query($sql);
    $id = $this->db->lastInsertId();
    return $id;
}


}