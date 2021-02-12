<?php

    namespace MF\Controller;

    abstract class Action {

        protected $view; //cria uma variavel para usar no construct

        public function __construct() { //sera executada ao estanciar a classe indexController
            $this->view = new \stdClass(); //cria uma classe vazia que pode inserir os dados dinamicamente, (o "\" é para pegar a expressão da raiz do php e naõ do namespace informado acima)
        }

        
        protected function render($view, $layout = 'layout'){ //embora o nome seja o mesmo da classe vazia $view aqui é apenas um parametro para saber a pagina de destino, e o layout se nao tiver coloca 'layout' por padrão
        $this->view->page = $view; //aqui estamos atribuindo o valor do parametro $view para o parametro "page" criado dinamicamente , para a class $view que agora é um objeto e recebe valores dinamicamente
        
        if (file_exists("../App/Views/".$layout .".phtml")) {
            require_once("../App/Views/".$layout .".phtml"); //faz a requisição do layout que dentro do layout esta chamando a ação content
        }else{
            $this->content(); //caso não tenha layout ele vai para o conteúdo sem layout mesmo
        }
        
        }

        protected function content() {

            $classAtual =  get_class($this); //comando php que pega o nome completo de caminho onde esta a class
            $classAtual =  str_replace( 'App\\Controllers\\', '', $classAtual); //retira os caracteres do caminho da class
            $classAtual = strtolower(str_replace( 'Controller', '', $classAtual));//deixa apenas o nome que possivelmente é o mesmo da view, tudo em caixa baixa
            //como de forma padrão o arquivo da view  vai ter o mesmo nome do script do nome controlador + 'controller', ex: indexController, só que sem a palavra controller,
            //por esse motivo é possível saber qual seria o script da view para inserir de uma forma dinamica no require_once

            require_once ("../App/Views/".$classAtual."/" . $this->view->page .".phtml" ); //criado para evitar redundancia, e só colocar o caminho 1x


        }

    }


?>
