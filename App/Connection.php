<?php

namespace App;



Class Connection {


    //FUNÇÃO RESPONSAVEL POR FAZER A CONEXÃO COM BANCO DE DADOS
    public static function getDb() {  //com o metodo estatico da pra se chamar o metodo diretamente se estiver usando o namespace, usando "nomeClasse::função"

        try{

            $conn = new \PDO( // como estou usando o namespace app, tive que colocar o "\" no PDO para ele entender que é uma classa da raiz do php, caso contrario ele não encontraria a classe padrão PDO
                "mysql:host=localhost;dbname=twitter_clone;charset=utf8", //padrão do PDO DRIVE,HOST,BANCO,USUARIO E SENHA
                "root",
                ""
            );


            return $conn;
    

        } catch (\PDOException  $e){

            $error_msg = "Erro na conexão com banco de dados." . " Mais detalhes: <br /> <br /> ";
            echo  $error_msg;

            echo "<pre>";
            print_r($e);
            echo "</pre>";
            

        }

    }
}

?>