<?php

class Cidades {

	private $con;
	private $sql;
	private $res;

	public function __construct(){
		$this->con = new conecta();
	}

	public function getDados(){

		$this->sql = "select * from cidades";
		$this->res = $this->con->bd->Execute($this->sql);
		$output = array("data"=> array());
		while(!$this->res->EOF){
			$id = $this->res->fields['cid_id'];
			$botoes = "<button class='btn btn-primary' data-id='$id' data-action='edit'><i class='glyphicon glyphicon-pencil'></i></button>&nbsp;";
			$botoes.= "<button class='btn btn-danger' data-id='$id' data-action='del'><i class='glyphicon glyphicon-trash'></i></button>";
			$linha = array();
			$linha[] = $this->res->fields['cid_id'];
			$linha[] = $this->res->fields['cid_nome'];
			$linha[] = $this->res->fields['cid_uf'];
			$linha[] = $botoes;
			$output['data'][] = $linha;
			$this->res->MoveNext();
		}
		return json_encode($output);
	}

	public function addCidade($nome, $uf){
		$this->sql = "insert into cidades (cid_nome, cid_uf) values	('$nome', '$uf')";
		$this->res = $this->con->bd->Execute($this->sql);
		if (!$this->res)
		return "error";
		else
		return "ok";
	}

	public function editCidade($id, $nome, $uf){
		$this->sql = "update cidades set cid_nome='$nome', cid_uf='$uf' where cid_id = $id";
		$this->res = $this->con->bd->Execute($this->sql);
		if (!$this->res)
		return "error";
		else
		return "ok";
	}

	public function delCidade($id){
		$this->sql = "delete from cidades where cid_id = $id ";
		$this->res = $this->con->bd->Execute($this->sql);
		if (!$this->res)
		return "error";
		else
		return "ok";
	}

	public function getCidade($id){
		$this->sql = "select * from cidades where cid_id = $id";
		$this->res = $this->con->bd->Execute($this->sql);
		if (!$this->res)
		return "error";
		else{
			$nome = $this->res->fields['cid_nome'];
			$uf = $this->res->fields['cid_uf'];
			$output = array( "id" => $id,
			"nome" => $nome,
			"uf" => $uf);
			return json_encode($output);
		}
	}

	function violacao_integridade($id) {
		$this->sql = "select * from clientes c where c.cid_id = $id";
		$this->res = $this->con->bd->Execute($this->sql);
		return $this->res->RecordCount();
	}
}
?>
