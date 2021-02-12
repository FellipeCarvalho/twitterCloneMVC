<?php

    namespace MF\Model;

        abstract class Model {

            protected $db;

            public function __construct(\PDO $db) { //tipei o tipo de atributo como PDO, é como se tipasse para um classe qlqer, para chamar a conexão do banco

            $this->db =$db;   //armazenamos a conexão
            }
        }
?>
