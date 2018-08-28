<?php

class Pedidos {

	private $con;
	private $sql;
	private $res;

	public function __construct(){
		$this->con = new conecta();
	}

	public function getDados(){
		$this->sql = "select * from pedidos p, cidades c, clientes c1 where p.cid_id = c.cid_id and p.cli_id = c1.cli_id";
		$this->res = $this->con->bd->Execute($this->sql);
		$output = array("data"=> array());
		while(!$this->res->EOF){
			$id = $this->res->fields['ped_id'];
			$botoes = "<button class='btn btn-primary' data-id='$id' data-action='edit'><i class='glyphicon glyphicon-pencil'></i></button>&nbsp;";
			$botoes.= "<button class='btn btn-danger' data-id='$id' data-action='del'><i class='glyphicon glyphicon-trash'></i></button>";
			$linha = array();
			$linha[] = $this->res->fields['ped_id'];
			$dataP = explode('-', $this->res->fields['ped_data']);
			$data = "Data: ".$dataP[2].'/'.$dataP[1].'/'.$dataP[0];
			$linha[] = $data."<br>Hora: ".$this->res->fields['ped_hora'];

			$linha[] = "R$ ".number_format($this->res->fields['ped_total'],2,",",".");
			switch ($this->res->fields['ped_status']) {
				case "1":
				$this->res->fields['ped_status'] = "Aguardando Pagamento";
				break;
				case "2":
				$this->res->fields['ped_status'] = "Pagamento Efetivado";
				break;
				case "3":
				$this->res->fields['ped_status'] = "Em processamento";
				break;
				case "4":
				$this->res->fields['ped_status'] = "Enviado";
				break;
				case "5":
				$this->res->fields['ped_status'] = "Entregue";
				break;
				default:
				break;
			}
			$linha[] = $this->res->fields['ped_status'];
			if ($this->res->fields['ped_dataenvio'] == "") {
				$this->res->fields['ped_dataenvio'] = "";
			}else{
				$dataP2 = explode('-', $this->res->fields['ped_dataenvio']);
				$this->res->fields['ped_dataenvio'] = $dataP2[2].'/'.$dataP2[1].'/'.$dataP2[0];
			}
			$linha[] = $this->res->fields['ped_dataenvio'];
			$linha[] = $this->res->fields['cli_nome'];
			$linha[] = $this->res->fields['cid_nome'];
			$linha[] = $botoes;
			$output['data'][] = $linha;
			$this->res->MoveNext();
		}
		return json_encode($output);
	}

	public function editPedido($id ,$status, $dataenvio){
		if ($dataenvio != "") {
			$dataenvio2 = explode('/', $dataenvio);
			$dataenvioBd = $dataenvio2[2].'-'.$dataenvio2[1].'-'.$dataenvio2[0];
			$this->sql = "update pedidos set ped_status='$status', ped_dataenvio='$dataenvioBd' where ped_id = $id";
		}else{
			$this->sql = "update pedidos set ped_status='$status' where ped_id = $id";
		}
		$this->res = $this->con->bd->Execute($this->sql) or die("Erro na atualização: ".$this->con->bd->ErrorMsg());
		if (!$this->res)
		return "error";
		else
		return "OK";
	}

	public function delPedido($id){
		$this->sql = "delete from pedidos where ped_id = $id ";
		$this->res = $this->con->bd->Execute($this->sql);
		if (!$this->res)
		return "error";
		else
		return "ok";
	}

	public function getPedido($id){
		$this->sql = "select * from pedidos where ped_id = $id";
		$this->res = $this->con->bd->Execute($this->sql);
		if (!$this->res)
		return "error";
		else{
			$status = $this->res->fields['ped_status'];
			if ($this->res->fields['ped_dataenvio'] != "") {
				$dataP2 = explode('-', $this->res->fields['ped_dataenvio']);
				$dataenvio = $dataP2[2].'/'.$dataP2[1].'/'.$dataP2[0];
			}else {
				$dataenvio = "";
			}
			$output = array( "id" => $id,	"status" => $status, "dataenvio" => $dataenvio);
			return json_encode($output);
		}
	}

	function violacao_integridade($id) {
		$this->sql = "select * from pedidos_itens pi where pi.ped_id = $id";
		$this->res = $this->con->bd->Execute($this->sql);
		return $this->res->RecordCount();
	}
}

?>
