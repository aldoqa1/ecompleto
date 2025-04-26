<?php 

namespace Controller;

use Model\ActiveRecord;
use MVC\Router;
use Model\Order;
use Model\OrderPayment;

class OrderController{

    public static function validate(Router $router){

        $alerts = [];

        if($_SERVER["REQUEST_METHOD"]==="POST" && trim($_POST["orderNumber"])){
            
            //getting the id number to be searched
            $orderNumber = trim($_POST["orderNumber"]);

            //finding the order
            $order = Order::findJoinGateways("id", $orderNumber);
           
            //if the order exists and the order gateway is equal to PAGCOMPLETO "1" and the situation is equal to Aguardando pagamento "1"
            if($order->id && $order->id_gateway==1 && $order->id_situacao==1){
            
                //finding the orderPayment
                $orderPayment = OrderPayment::find("id_pedido", $orderNumber);
              
                //if the orderPayment exists and the formapagto is equal to cartao de credito "3"
                if($orderPayment->id && $orderPayment->id_formapagto ==3){
                    
                    //url
                    $url = "https://apiinterna.ecompleto.com.br/exams/processTransaction?accessToken=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VySWQiOjI2ODQsInN0b3JlSWQiOjE5NzksImlhdCI6MTc0NDY2MDgyOSwiZXhwIjoxNzQ1OTU2ODI5fQ.m9-SyVLC2o4J2yPp9E5EXt3wbQMjxyh0Rbz3wJrODcM";

                    //body data
                    $data = [
                        "external_order_id" => (int)$orderPayment->id_pedido,
                        "amount" => (float)$order->valor_total,
                        "card_number" => $orderPayment->num_cartao,
                        "card_cvv" => $orderPayment->codigo_verificacao,
                        "card_expiration_date" => formatDateCard($orderPayment->vencimento),
                        "card_holder_name" => $orderPayment->nome_portador,
                        "customer" => [
                            "external_id" => $order->id_cliente,
                            "name" => $order->nome,
                            "type" => "individual",
                            "email" => $order->email,
                            "documents" => [
                                [
                                    "type" => "cpf",
                                    "number" => $order->cpf_cnpj
                                ]
                            ],
                            "birthday" => $order->data_nasc
                        ]
                    ];

                    //data as a json
                    $jsonData = json_encode($data);
                    
                    //options request
                    $options = [
                        "http" => [
                            "header"  => "Content-Type: application/json\r\n",
                            "method"  => "POST",
                            "content" => $jsonData
                        ],
                        "ssl" => [
                            "verify_peer" => false,
                            "verify_peer_name" => false
                        ]
                    ];

                    //request
                    $context = stream_context_create($options);
                    $response = file_get_contents($url, false, $context);
                  
                    // checking the response
                    if ($response === FALSE) {
                        $alerts["error"][]="Erro na solicitação";
                    } else {
                        $objectResponse = json_decode($response);

                        //t checks if the request has an error
                        if($objectResponse->Error){
                            $alerts["error"][]=$objectResponse->Message;
                        }else{

                            //at this point there is no error
                            switch($objectResponse->Transaction_code){
                                //Approved
                                case "00":{
                                   
                                    try {
                                        
                                        // starting transaction
                                        ActiveRecord::$db->begin_transaction();
                                    
                                        $order->id_situacao = 2;
                                        $order->email = null;
                                        $order->nome = null;
                                        $order->id_gateway = null;
                                        $order->data_nasc = null;
                                        $order->cpf_cnpj = null;
                                        
                                        if (!$order->saveProperty("id_situacao")) {
                                            
                                            $alerts["error"][] = "Erro ao atualizar pedido";
                                        }
                                       
                                        $orderPayment->retorno_intermediador = $objectResponse->Message;
                                        if (!$orderPayment->saveProperty("retorno_intermediador")) {
                                            $alerts["error"][] = "Erro ao atualizar a ordem de pagamento";
                                        }
                                        
                                        if(empty($alerts)){
                                            ActiveRecord::$db->commit();
                                            $alerts["success"][] = "O pedido foi aprovado";
                                        }else{

                                            ActiveRecord::$db->rollBack();
                                            
                                        }
                                        
                                    } catch (\Exception $e) {
                                     
                                        $alerts["error"][] = "Erro fatal ao atualizar estado da ordem";
                                        ActiveRecord::$db->rollBack();
                               
                                    }

                                    break;
                                }



                                //it's still being checked
                                case "01":{

                                    $alerts["error"][]=$objectResponse->Message;
                                    break;

                                }

                                //canceled
                                default:{
                                    
                                    try {
                                        // starting transaction
                                        ActiveRecord::$db->begin_transaction();
                                    
                                        $order->id_situacao = 3;
                                        if (!$order->saveUpdate()) {
                                            $alerts["error"][] = "Erro ao atualizar pedido";
                                        }
                                    
                                        $orderPayment->retorno_intermediador = $objectResponse->Message;
                                        if (!$orderPayment->saveUpdate()) {
                                            $alerts["error"][] = "Erro ao atualizar a ordem de pagamento";
                                        }
                                    
                                        if(empty($alerts)){
                                            ActiveRecord::$db->commit();
                                            $alerts["error"][] = "O pedido foi cancelado. Cartão sem crédito disponível";
                                        }else{
                                            ActiveRecord::$db->rollBack();
                                        }
                                    
                                    } catch (\Exception $e) {
                                        $alerts["error"][] = "Erro fatal ao atualizar estado da ordem";
                                    }

                                    break;
                                }
                            }

                        }
                    }


                }else{
                    if($order->id){
                        $alerts["error"][]="Seu pagamento deve ser feito com cartão de crédito";
                    }else{
                        $alerts["error"][]="O número do orderPayment não existe";
                    }
                }
    
            }else{
                if($order->id){
                    $alerts["error"][]="Não foi pago no PAGCOMPLETO ou o pagamento já foi processado";
                }else{
                    $alerts["error"][]="O número do pedido não é válido";
                }
            }

        }else{
            if($_SERVER["REQUEST_METHOD"]==="POST"){
                $alerts["error"][]="O número do pedido está vazio";
            }
        }

        $router->render("user/validate", ["alerts"=>$alerts ]);
    }    



}    