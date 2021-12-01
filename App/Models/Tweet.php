<?php

namespace App\Models;

use MF\Model\Model;

class Tweet extends Model {

    private $id;
    private $id_usuario;
    private $tweet;
    private $data;
	
    public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}
    //Salvar

    public function salvar(){
        $query = "INSERT INTO tweets (id_usuario,tweet)VALUES(:id_usuario, :tweet)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":id_usuario", $this->__get("id_usuario"));
        $stmt->bindValue(":tweet", $this->__get("tweet"));
        $stmt->execute();
        return $this;        
    }

    public function delete($id_tweet){
        $query = "DELETE FROM tweets WHERE id = :id_tweet";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":id_tweet", $id_tweet);
        $stmt->execute();
        return true;        
    }


    //Recuperar

    public function getAll(){
        $query = "SELECT 
        t.id,
        t.id_usuario,
        t.tweet,
        t.data, 
        u.nome FROM tweets as t LEFT JOIN usuarios as u ON(t.id_usuario = u.id) WHERE id_usuario = :id_usuario or t.id_usuario in(SELECT id_usuario_seguindo FROM usuarios_seguidores WHERE id_usuario = :id_usuario) ORDER BY T.data DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":id_usuario",$this->__get('id_usuario'));
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);        
    }
}

?>