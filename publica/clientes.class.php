<?php
class Clientes{

	private $con;
	private $sql;
	private $res;

	public function __construct(){
		$this->con = new conecta();
	}

	public function addCliente($email, $senha, $cpf, $cep, $fone1, $fone2, $sexo, $nome, $endereco, $cidade, $bairro, $dtNasc, $mala){
		$dataP = explode('/', $dtNasc);
		$dataBd = $dataP[2].'-'.$dataP[1].'-'.$dataP[0];
		$cpfBd = preg_replace("/\D+/", "", $cpf);
		$this->sql = "insert into clientes (cli_nome, cli_cpf, cli_endereco, cli_email, cli_sexo, cli_bairro, cli_cep, cli_fone1, cli_fone2, cli_datanasc, cli_status, cli_maladireta, cid_id, cli_tipo, cli_senha)
		values ('$nome', '$cpfBd', '$endereco', '$email', upper('$sexo'), '$bairro', '$cep', '$fone1', '$fone2', '$dataBd', 'A', upper('$mala'), $cidade,'C', '$senha')";
		$this->res = $this->con->bd->Execute($this->sql);
		if (!$this->res)
		return "error";
		else
		return "ok";
	}

	public function editCliente($id, $email, $senha, $cpf, $cep, $fone1, $fone2, $sexo, $nome, $endereco, $cidade, $bairro, $dtNasc, $mala){
		$dataP = explode('/', $dtNasc);
		$dataBd = $dataP[2].'-'.$dataP[1].'-'.$dataP[0];
		$cpfBd = preg_replace("/\D+/", "", $cpf);
		$this->sql = "update clientes set cli_nome='$nome', cli_cpf='$cpfBd', cli_endereco='$endereco', cli_email='$email', cli_sexo='$sexo',
		cli_bairro='$bairro', cli_cep='$cep', cli_fone1='$fone1', cli_fone2='$fone2', cli_datanasc='$dataBd', cli_status='A', cli_maladireta='$mala', cid_id='$cidade', cli_senha='$senha' where cli_id = $id";
		$this->res = $this->con->bd->Execute($this->sql);
		if (!$this->res)
		return "error";
		else
		return "ok";
	}

	public function getCliente($id){
		$this->sql = "select * from clientes where cli_id = $id";
		$this->res = $this->con->bd->Execute($this->sql);
		if (!$this->res)
		return "error";
		else{
			$nome = $this->res->fields['cli_nome'];
			$email = $this->res->fields['cli_email'];
			$senha = $this->res->fields['cli_senha'];
			$cli_mask = Mask_List("###.###.###-##", $this->res->fields['cli_cpf']);
			$cpf = $cli_mask;
			$cep = $this->res->fields['cli_cep'];
			$fone1 = $this->res->fields['cli_fone1'];
			$fone2 = $this->res->fields['cli_fone2'];
			$sexo = $this->res->fields['cli_sexo'];
			$endereco = $this->res->fields['cli_endereco'];
			$cidade = $this->res->fields['cid_id'];
			$bairro = $this->res->fields['cli_bairro'];
			$dtNasc = $this->res->fields['cli_datanasc'];
			$mala = $this->res->fields['cli_maladireta'];
			$dataP = explode('-', $dtNasc);
			$dataBd = $dataP[2].'/'.$dataP[1].'/'.$dataP[0];
			$output = array( "id"=>$id, "nome"=>$nome, "email"=>$email, "senha"=>$senha, "cpf"=>$cpf, "cep"=>$cep, "fone1"=>$fone1, "fone2"=>$fone2, "sexo"=>$sexo, "endereco"=>$endereco, "cidade"=>$cidade, "bairro"=>$bairro, "dtNasc"=>$dataBd, "mala"=>$mala);
			return json_encode($output);
		}
	}

	public function getmenucidades($cidade){
		$this->sql = "select cid_nome, cid_id from cidades order by cid_nome";
		$this->res = $this->con->bd->Execute($this->sql);
		return $this->res->GetMenu('cidade',$cidade,true,false,0,"class='form-control cidade' id='cidade'");
	}

}

?>
