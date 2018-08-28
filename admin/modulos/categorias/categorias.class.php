<?php

class Categorias {

	private $con;
	private $sql;
	private $res;

	public function __construct(){
		$this->con = new conecta();
	}

	public function getDados(){

		$this->sql = "select * from categorias";
		$this->res = $this->con->bd->Execute($this->sql);
		$output = array("data"=> array());
		while(!$this->res->EOF){
			$id = $this->res->fields['cat_id'];
			$botoes = "<button class='btn btn-primary' data-id='$id' data-action='edit'><i class='glyphicon glyphicon-pencil'></i></button>&nbsp;";
			$botoes.= "<button class='btn btn-danger' data-id='$id' data-action='del'><i class='glyphicon glyphicon-trash'></i></button>";
			$linha = array();
			$linha[] = $this->res->fields['cat_id'];
			$linha[] = $this->res->fields['cat_descricao'];
			$linha[] = $botoes;
			$output['data'][] = $linha;
			$this->res->MoveNext();
		}
		return json_encode($output);
	}

	public function addCategoria($descricao){
		$this->sql = "insert into categorias (cat_descricao) values	('$descricao')";
		$this->res = $this->con->bd->Execute($this->sql);
		if (!$this->res)
		return "error";
		else
		return "ok";
	}

	public function editCategoria($id, $descricao){
		$this->sql = "update categorias set cat_descricao = '$descricao' where cat_id = $id";
		$this->res = $this->con->bd->Execute($this->sql);
		if (!$this->res)
		return "error";
		else
		return "ok";
	}

	public function delCategoria($id){
		$this->sql = "delete from categorias where cat_id = $id";
		$this->res = $this->con->bd->Execute($this->sql);
		if (!$this->res)
			return "error";
		else
			return "ok";
	}

	public function getCategoria($id){
		$this->sql = "select * from categorias where cat_id = $id";
		$this->res = $this->con->bd->Execute($this->sql);
		if (!$this->res)
		return "error";
		else{
			$descricao = $this->res->fields['cat_descricao'];
			$output = array( "id" => $id, "descricao" => $descricao);
			return json_encode($output);
		}
	}

	function violacao_integridade($id) {
		$this->sql = "select * from produtos p where p.cat_id = $id";
		$this->res = $this->con->bd->Execute($this->sql);
		return $this->res->RecordCount();
	}
}

?>
