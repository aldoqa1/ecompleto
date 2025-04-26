<?php 

namespace Model;

class OrderPayment extends ActiveRecord{

    public $columns = ["id", "id_pedido", "id_formapagto", "qtd_parcelas", "retorno_intermediador", "data_processamento", "num_cartao", "nome_portador", "codigo_verificacao", "vencimiento"];
    static $table = "pedidos_pagamentos";
    public $alerts = [];
    public $id;
    public $id_pedido;
    public $id_formapagto;
    public $qtd_parcelas;
    public $retorno_intermediador;
    public $data_processamento;
    public $num_cartao;
    public $nome_portador;
    public $codigo_verificacao;
    public $vencimento;
    
    public function __construct($args = []) {
        $this->id = $args["id"] ? trim($args["id"]) : "";
        $this->id_pedido = $args["id_pedido"] ? trim($args["id_pedido"]) : "";
        $this->id_formapagto = $args["id_formapagto"] ? trim($args["id_formapagto"]) : "";
        $this->qtd_parcelas = $args["qtd_parcelas"] ? trim($args["qtd_parcelas"]) : "";
        $this->retorno_intermediador = $args["retorno_intermediador"] ? trim($args["retorno_intermediador"]) : "";
        $this->data_processamento = $args["data_processamento"] ? trim($args["data_processamento"]) : "";
        $this->num_cartao = $args["num_cartao"] ? trim($args["num_cartao"]) : "";
        $this->nome_portador = $args["nome_portador"] ? trim($args["nome_portador"]) : "";
        $this->codigo_verificacao = $args["codigo_verificacao"] ? trim($args["codigo_verificacao"]) : "";
        $this->vencimento = $args["vencimento"] ? trim($args["vencimento"]) : "";
    }

}

?>