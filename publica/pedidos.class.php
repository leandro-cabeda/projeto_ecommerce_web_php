<?php
class Pedidos {
	private $con;
	private $sql;
	private $res;

	public function __construct(){
		$this->con = new conecta();
	}

	public function getDados($id){
		$this->sql = "SELECT * FROM pedidos WHERE cli_id = $id";
		$this->res = $this->con->bd->Execute($this->sql);
		$output = array("data"=> array());
		while(!$this->res->EOF){
			$ped_id = $this->res->fields['ped_id'];
			$botoes = "<button class='btn btn-success btn-view' data-id='$ped_id' data-action='view'><i class='glyphicon glyphicon-eye-open'></i></button>";
			$dataP = explode('-', $this->res->fields['ped_data']);
			$ped_status = $this->res->fields['ped_status'];
			switch ($ped_status) {
				case '1':
				$ped_status = "Aguardando Pagamento";
				break;
				case '2':
				$ped_status = "Pagamento Efetivado";
				break;
				case '3':
				$ped_status = "Em Processamento";
				break;
				case '4':
				$ped_status = "Enviado";
				break;
				case '5':
				$ped_status = "Entregue";
				break;
				default:
				$ped_status = "Erro";
				break;
			}
			$linha = array();
			$linha[] = $ped_id;
			$linha[] = $dataP[2].'/'.$dataP[1].'/'.$dataP[0];
			$linha[] = "R$ ".number_format($this->res->fields['ped_total'],2,",",".");
			$linha[] = $ped_status;
			$linha[] = $botoes;
			$output['data'][] = $linha;
			$this->res->MoveNext();
		}
		return json_encode($output);
	}

	public function getItensPedido($id){
		$this->sql = "SELECT * from pedidos_itens pi, produtos pro where pi.ped_id = $id and pro.pro_id = pi.pro_id";
		$this->res = $this->con->bd->Execute($this->sql);
		if (!$this->res)
			return "error";
		else{
			$output = array("data"=> array());
			while(!$this->res->EOF){
				$pro_id = $this->res->fields['pro_id'];
				$imagem = "<img src='../../../../e-commerce/produtos/" ."prod".$pro_id.".jpg' heigth='90' width='90'/>";
				$pro_descricao = $this->res->fields['pro_descricao'];
				$ite_qtd = $this->res->fields['ite_qtd'];
				$ite_valor = $this->res->fields['ite_valor'];
				$linha = array();
				$linha[] = $imagem;
				$linha[] = $pro_descricao;
				$linha[] = $ite_qtd;
				$linha[] = "R$ ".number_format($ite_valor,2,",",".");
				$linha[] = "R$ ".number_format(($ite_valor * $ite_qtd),2,",",".");
				$output['data'][] = $linha;
				$this->res->MoveNext();
			}
			return json_encode($output);
		}
	}

}

?>
