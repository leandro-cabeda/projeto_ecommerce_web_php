<?php

class Clientes {

	private $con;
	private $sql;
	private $res;

	public function __construct(){
		$this->con = new conecta();
	}

	function verifica($user, $senha){
		$this->sql = "select * from clientes where cli_email = '$user'";
		$this->res = $this->con->bd->Execute($this->sql);
		if($this->res->fields['cli_senha'] == $senha){
			if ($this->res->fields['cli_tipo'] == "F" || $this->res->fields['cli_tipo'] == "G") {
				$_SESSION['user_session'] = $this->res->fields['cli_id'];
				return "OK";
			}else {
				return "Acesso administrativo negado, pois o usuário é do tipo cliente!";
			}
		}else{
			return "Usuário ou senha não existe!";
		}
	}

	public function getDados(){
		$this->sql = "select * from clientes cl, cidades c where cl.cid_id = c.cid_id";
		$this->res = $this->con->bd->Execute($this->sql);
		$output = array("data"=> array());
		while(!$this->res->EOF){
			$id = $this->res->fields['cli_id'];
			$botoes = "<button class='btn btn-primary' data-id='$id' data-action='edit'><i class='glyphicon glyphicon-pencil'></i></button>&nbsp;";
			$botoes.= "<button class='btn btn-danger' data-id='$id' data-action='del'><i class='glyphicon glyphicon-trash'></i></button>";
			$linha = array();
			$linha[] = $this->res->fields['cli_id'];
			$linha[] = $this->res->fields['cli_nome'];
			$cli_mask = Mask_List("###.###.###-##",$this->res->fields['cli_cpf']);
			$linha[] = $cli_mask;
			$linha[] = $this->res->fields['cli_email'];
			if ($this->res->fields['cli_status'] == "A") {
				$this->res->fields['cli_status'] = "Ativo";
			}else if ($this->res->fields['cli_status'] == "I"){
				$this->res->fields['cli_status'] = "Inativo";
			}
			$linha[] = $this->res->fields['cli_status'];
			$linha[] = $this->res->fields['cid_nome'];
			if ($this->res->fields['cli_tipo'] == "C") {
				$this->res->fields['cli_tipo'] = "Cliente";
			}else if ($this->res->fields['cli_tipo'] == "F"){
				$this->res->fields['cli_tipo'] = "Funcionário";
			}else if ($this->res->fields['cli_tipo'] == "G"){
				$this->res->fields['cli_tipo'] = "Gerente";
			}
			$linha[] = $this->res->fields['cli_tipo'];
			$linha[] = $botoes;
			$output['data'][] = $linha;
			$this->res->MoveNext();
		}
		return json_encode($output);
	}

	public function addCliente($nome, $cpf, $endereco, $email, $sexo, $bairro, $cep, $fone1, $fone2, $datanasc, $status, $maladireta, $cidade, $tipo, $senha){
		$data = explode("/", $datanasc);
		$datanascBd = $data[2]."-".$data[1]."-".$data[0];
		$cpfBd = preg_replace("/\D+/", "", $cpf);
		$this->sql = "insert into clientes (cli_nome, cli_cpf, cli_endereco, cli_email, cli_sexo, cli_bairro, cli_cep, cli_fone1, cli_fone2, cli_datanasc, cli_status, cli_maladireta, cid_id, cli_tipo, cli_senha) values
		('$nome', '$cpfBd', '$endereco', '$email', '$sexo', '$bairro', '$cep', '$fone1', '$fone2', '$datanascBd', '$status', '$maladireta', $cidade, '$tipo', '$senha')";
		$this->res = $this->con->bd->Execute($this->sql);
		if (!$this->res)
			return "error";
		else
			return "ok";
	}

	public function editCliente($id ,$nome, $cpf, $endereco, $email, $sexo, $bairro, $cep, $fone1, $fone2, $datanasc, $status, $maladireta, $cidade, $tipo, $senha){
		$data = explode("/", $datanasc);
		$datanascBd = $data[2]."-".$data[1]."-".$data[0];
		$cpfBd = preg_replace("/\D+/", "", $cpf);
		$this->sql = "update clientes set cli_nome='$nome', cli_cpf='$cpfBd', cli_endereco='$endereco', cli_email='$email', cli_sexo='$sexo', cli_bairro='$bairro', cli_cep='$cep', cli_fone1='$fone1', cli_fone2='$fone2', cli_datanasc='$datanascBd', cli_status='$status', cli_maladireta='$maladireta', cid_id=$cidade, cli_tipo='$tipo', cli_senha='$senha'   where cli_id = $id";
		$this->res = $this->con->bd->Execute($this->sql);
		if (!$this->res){
			if ( $this->verifica_email($email) >= 1) {
				return "e-mail ja existe";
			}else{
				return "error";
			}
		}
		else{
			return "ok";
		}
	}

	public function delCliente($id){
		$this->sql = "delete from clientes where cli_id = $id ";
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
			$cli_mask = Mask_List("###.###.###-##",$this->res->fields['cli_cpf']);
			$cpf = $cli_mask;
			$endereco = $this->res->fields['cli_endereco'];
			$email = $this->res->fields['cli_email'];
			$sexo = $this->res->fields['cli_sexo'];
			$bairro = $this->res->fields['cli_bairro'];
			$cep = $this->res->fields['cli_cep'];
			$fone1 = $this->res->fields['cli_fone1'];
			$fone2 = $this->res->fields['cli_fone2'];
			$data = explode("-", $this->res->fields['cli_datanasc']);
			$datanasc = $data[2]."/".$data[1]."/".$data[0];
			$status = $this->res->fields['cli_status'];
			$maladireta = $this->res->fields['cli_maladireta'];
			$cidade = $this->res->fields['cid_id'];
			$tipo = $this->res->fields['cli_tipo'];
			$senha = $this->res->fields['cli_senha'];

			$output = array( "id" => $id,	"nome" => $nome, "cpf" => $cpf, "endereco" => $endereco, "email" => $email, "sexo" => $sexo,
			"bairro" => $bairro, "cep" => $cep, "fone1" => $fone1, "fone2" => $fone2, "datanasc" => $datanasc, "status" => $status,
			"maladireta" => $maladireta, "cidade" => $cidade, "tipo" => $tipo, "senha" => $senha);
			return json_encode($output);
		}
	}

	public function getMenuCidades($cidade){
		$this->sql = "select cid_nome, cid_id from cidades order by cid_nome";
		$this->res = $this->con->bd->Execute($this->sql);
		return $this->res->GetMenu('cidade',$cidade, true, false, 0, "required class='form-control cidade' id='cidade'");
		//return $this->res->GetMenu('cidade',$cidade, true, false, 0, "required class='form-control' ");  Dessa forma funciona igual o decima, mesma coisa, só emcima ta sendo acrescentando uma classe e um id
			// 1 opção do GetMenu declara o nome do campo como exemplo nome cidade, como tivesse campo name='cidade' na tag dentro.
			// 2 opção é onde entra a lista de registros desse dado buscado como exemplo select tais dados de tal tabela.
			// 3 opção se quer deixar a primeira linha campo vasio botando true ou falso de não deixar vazio.
			// 4 opção se quer abrir uma caixa de lista de váriso dados já mostrando todos, com isso poem true, caso contrário poem false.
			// 5 opção mostra numero que quer mostrar de colunas dentro da caixa seleção.
			// 6 opção ai tu define que tu quer por em cada dado , um classe , ou id, ou outros atributos.
	}

	function violacao_integridade($id) {
		$this->sql = "select * from pedidos p where p.cli_id = $id";
		$this->res = $this->con->bd->Execute($this->sql);
		return $this->res->RecordCount();
	}

	function verifica_email($email) {
		$this->sql = "select * from clientes where cli_email = '$email'";
		$this->res = $this->con->bd->Execute($this->sql);
		return $this->res->RecordCount();
	}

}

?>
