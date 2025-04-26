<?php 

namespace Model;

class Order extends ActiveRecord{

    public $columns = ["id", "valor_total", "valor_frete", "data", "id_cliente", "id_loja", "id_situacao"];
    static $table = "pedidos";
    public $alerts = [];
    public $id;
    public $valor_total;
    public $valor_frete;
    public $data;
    public $id_cliente;
    public $id_loja;
    public $id_situacao;
    public $id_gateway;
    public $nome;
    public $email;
    public $cpf_cnpj;
    public $data_nasc;
    
    public function __construct($args = []) {
        $this->id = $args["id"] ? trim($args["id"]) : "";
        $this->valor_total = $args["valor_total"] ? trim($args["valor_total"]) : "";
        $this->valor_frete = $args["valor_frete"] ? trim($args["valor_frete"]) : "";
        $this->data = $args["data"] ? trim($args["data"]) : "";
        $this->id_cliente = $args["id_cliente"] ? trim($args["id_cliente"]) : "";
        $this->id_loja = $args["id_loja"] ? trim($args["id_loja"]) : "";
        $this->id_situacao = $args["id_situacao"] ? trim($args["id_situacao"]) : "";
    }

    public static function findJoinGateways($type, $value){
        $type = self::$db->escape_string($type);
        $value = self::$db->escape_string($value);
        $query = "SELECT a.id, a.id_situacao, a.valor_total, a.valor_frete, a.id_cliente, a.id_loja, a.data, b.id_gateway, c.email, c.nome, c.cpf_cnpj, c.data_nasc
            FROM " . static::$table . " AS a
                LEFT JOIN lojas_gateway AS b ON a.id_loja = b.id_loja
                LEFT JOIN clientes AS c ON a.id_cliente = c.id  
                WHERE a.$type = '$value' LIMIT 1;";

        $consult = self::$db->query($query)->fetch_assoc();
        
        $newObject = new static($consult);
        $newObject->id_gateway = $consult["id_gateway"];
        $newObject->email = $consult["email"];
        $newObject->nome = $consult["nome"];
        $newObject->cpf_cnpj = $consult["cpf_cnpj"];
        $newObject->data_nasc = $consult["data_nasc"];
        
        return $newObject;
    }
}

?>