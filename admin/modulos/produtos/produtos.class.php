<?php

class Produtos {

	private $con;
	private $sql, $sql2;
	private $res, $res2;

	public function __construct(){
		$this->con = new conecta();
	}

	public function getDados(){
		$this->sql = "select * from produtos p, categorias c where p.cat_id = c.cat_id";
		$this->res = $this->con->bd->Execute($this->sql);
		$output = array("data"=> array());
		while(!$this->res->EOF){
			$id = $this->res->fields['pro_id'];
			$imagem = "<img src='../../../../e-commerce/produtos/" ."prod".$id.".jpg' heigth='100' width='100'/>";
			$botoes = "<button class='btn btn-primary' data-id='$id' data-action='edit'><i class='glyphicon glyphicon-pencil'></i></button>&nbsp;";
			$botoes.= "<button class='btn btn-danger' data-id='$id' data-action='del'><i class='glyphicon glyphicon-trash'></i></button>";
			$linha = array();
			$linha[] = $this->res->fields['pro_id'];
			$linha[] = $imagem;
			$linha[] = $this->res->fields['pro_descricao'];
			$linha[] = "R$ ".number_format($this->res->fields['pro_valor'],2,",",".");
			$linha[] = $this->res->fields['pro_estoque'];
			if ($this->res->fields['pro_peso'] < 1) {
				$linha[] = $this->res->fields['pro_peso']. " g";
			}else {
				$linha[] = $this->res->fields['pro_peso']." kg";
			}
			if ($this->res->fields['pro_promocao'] == "") {
				$linha[] = "";
			}else{
				$linha[] = "R$ ".number_format($this->res->fields['pro_promocao'],2,",",".");
			}

			$linha[] = $this->res->fields['cat_descricao'];
			$linha[] = $botoes;
			$output['data'][] = $linha;
			$this->res->MoveNext();
		}
		return json_encode($output);
	}

	public function addProduto($descricao, $valor, $detalhes, $estoque, $peso, $promocao, $categoria, $dimensao){
		$valor = str_replace(".", "", $valor);
		$valor = str_replace(",",".", $valor);
		if($promocao=="")
			{
				$this->sql = "insert into produtos (pro_descricao, pro_valor, pro_detalhes, pro_estoque, pro_peso, cat_id,
				pro_dimensao) values ('$descricao', $valor, '$detalhes', $estoque, $peso, $categoria, '$dimensao')";
				$this->res = $this->con->bd->Execute($this->sql);
			}
			else
			{
				$promocao = str_replace(".", "", $promocao);
				$promocao = str_replace(",",".", $promocao);
				$this->sql = "insert into produtos (pro_descricao, pro_valor, pro_detalhes, pro_estoque, pro_peso, pro_promocao, cat_id,
				pro_dimensao) values ('$descricao', $valor, '$detalhes', $estoque, $peso, $promocao, $categoria, '$dimensao')";
				$this->res = $this->con->bd->Execute($this->sql);
			}
		if (!$this->res)
			return "error - ".$this->sql;
		else{
			if (isset($_FILES['file']) && !empty($_FILES['file']['name'])) {
				$this->sql2 = "select max(pro_id) as id from produtos";
				$this->res2 = $this->con->bd->Execute($this->sql2);
				$id = $this->res2->fields['id'];
				$destino = "C:/xampp/htdocs/e-commerce/produtos/" ."prod".$id.".jpg";
				$move_upload_rs = move_uploaded_file($_FILES['file']['tmp_name'], $destino );
			}
			return "ok";
		}
	}

	public function editProduto($id, $descricao, $valor, $detalhes, $estoque, $peso, $promocao, $categoria, $dimensao){
		$valor = str_replace(".", "", $valor);
		$valor = str_replace(",",".", $valor);
		if($promocao=="")
			{
				$promocao=NULL;
				$this->sql = "update produtos set pro_descricao='$descricao', pro_valor=$valor, pro_detalhes='$detalhes', pro_estoque=$estoque,
				pro_peso=$peso, pro_promocao=$promocao, cat_id=$categoria, pro_dimensao='$dimensao'  where pro_id = $id";
				$this->res = $this->con->bd->Execute($this->sql);
			}
			else
			{
				$promocao = str_replace(".", "", $promocao);
				$promocao = str_replace(",",".", $promocao);
				$this->sql = "update produtos set pro_descricao='$descricao', pro_valor=$valor, pro_detalhes='$detalhes', pro_estoque=$estoque,
				pro_peso=$peso, pro_promocao=$promocao, cat_id=$categoria, pro_dimensao='$dimensao'  where pro_id = $id";
				$this->res = $this->con->bd->Execute($this->sql);
			}
		if (isset($_FILES['file']) && !empty($_FILES['file']['name'])) {
			$destino = "C:/xampp/htdocs/e-commerce/produtos/" ."prod".$id.".jpg";
			$move_upload_rs = move_uploaded_file($_FILES['file']['tmp_name'], $destino );
		}
		if (!$this->res)
		return "error - ".$this->sql;
		else
		return "ok";
	}

	public function delProduto($id){
		$this->sql = "delete from produtos where pro_id = $id ";
		$this->res = $this->con->bd->Execute($this->sql);
		if (!$this->res)
		return "error";
		else
		return "ok";
	}

	public function getProduto($id){
		$this->sql = "select * from produtos where pro_id = $id";
		$this->res = $this->con->bd->Execute($this->sql);
		if (!$this->res)
		return "error";
		else{
			$descricao = $this->res->fields['pro_descricao'];
			$valor = number_format($this->res->fields['pro_valor'],2,",",".");
			$detalhes = $this->res->fields['pro_detalhes'];
			$estoque = $this->res->fields['pro_estoque'];
			$peso = $this->res->fields['pro_peso'];
			$promocao = number_format($this->res->fields['pro_promocao'],2,",",".");
			$categoria = $this->res->fields['cat_id'];
			$dimensao = $this->res->fields['pro_dimensao'];
			$imagem = "../../../../e-commerce/produtos/" ."prod".$id.".jpg";
			if (!file_exists($imagem)){
				$imagem = "";
			}
			$output = array( "id" => $id,	"descricao" => $descricao, "valor" => $valor, "detalhes" => $detalhes, "estoque" => $estoque,
			"peso" => $peso, "promocao" => $promocao, "categoria" => $categoria, "dimensao" => $dimensao, "imagem" => $imagem);
			return json_encode($output);
		}
	}

	public function getMenuCategorias($categoria){
		$this->sql = "select cat_descricao, cat_id from categorias order by cat_descricao";
		$this->res = $this->con->bd->Execute($this->sql);
		return $this->res->GetMenu('categoria',$categoria, true, false, 0, "class='form-control categoria' id='categoria'");
	}

	function violacao_integridade($id) {
		$this->sql = "select * from pedidos_itens pi where pi.pro_id = $id";
		$this->res = $this->con->bd->Execute($this->sql);
		return $this->res->RecordCount();
	}

}

?>
