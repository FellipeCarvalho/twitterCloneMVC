<?php
    namespace App\Controllers;

    //CHAMADAS DE DEPENDENCIAS DO FRAMEWORK

use App\Connection;
use App\Models\Usuario;
use MF\Controller\Action; 
    use MF\Model\Container; //para fazer a conexão com banco e chamar o modelo

    class AppController extends Action {

        //após login vai para timeline
        public function timeline(){


            //protegendo a página, para que não seja possível acessar sem estar logado
            //se estiver logado, acessa os dados da timeline
            $this->validaAutenticacao();

            //recuperacao de tweets
            //chama do objeto abstrato o modelo responsavel e a conexão com banco
            $tweet = Container::getModel("Tweet");

            //seta o id de usuario na session corrente
            $tweet->__set('id_usuario', $_SESSION['id']);



            //paginação
            $total_registros_pag = 2;

            $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
            $deslocamento = ($pagina - 1) *  $total_registros_pag;


            $pagina_tweets = $tweet->getPorPagina($total_registros_pag, $deslocamento );
            $total_tweets = $tweet->getTotalTweets();
            $this->view->total_de_paginas =ceil($total_tweets['total']   / $total_registros_pag);
            $this->view->pagina_ativa = $pagina;


             //pega do modelo todos os dados de tweets
            $retorno_tweets = $tweet->getPorPagina($total_registros_pag, $deslocamento);
         
            //criei mais um atributo para view para fazer um foreach ho html e inserir os tweets
            $this->view->tweets_disponiveis =  $retorno_tweets; 


            //para exibir os dados do perfil do usuario (seguidores, seguindo e tweest criados)
            $info_usuario = Container::getModel('Usuario');
            $info_usuario->__set('id', $_SESSION['id'] );
            $info_perfil = $info_usuario->getInfoUsuario();

            $this->view->info_perfil =$info_perfil;


            //renderiza a view responsavel
            $this->render('timeline');


        }


        //inclusão do tweet
        public function tweet(){
            
            session_start();

            //action responsavel por fazer validação se esta logado ou nao
            $this->validaAutenticacao();

             //faz conexao com banco e chama model
               $tweet =Container::getModel("tweet"); 

               //seta no objeto valores dos aatributos
               $tweet->__set('tweet', $_POST['tweet']);
               $tweet->__set('id_usuario', $_SESSION['id']);

               //chama do model a ação salvar, que grava no banco
               $tweet->salvar();

               //volta para a página timeline
               header('Location: /timeline');

        }

        //abstrair a validação se ta autenticado ou nao
        public function validaAutenticacao(){
           
            //aber a sesssion e verifica se tem algum id ou nome, se não tiver qlq um dos dois ele desloga e pede para logar novamente
             session_start();

            if(!isset($_SESSION['id']) || $_SESSION['id'] =='' || !isset($_SESSION['nome']) || $_SESSION['nome'] =='' ) {
                header('Location: /?login=erroAutenticar');

            }
        }



        public function quemSeguir(){

             //action responsavel por fazer validação se esta logado ou nao
            $this->validaAutenticacao();

            //para não dar erro, criei uma variavel com esse array que listara na pagina quemseguir.phtml, caso não tenha nenhuma correspondência o que for pesquisado
            $retorno_usuarios = array();
           
            $pesquisar_por = isset($_GET['pesquisar_por']) ? $_GET['pesquisar_por'] : '';
       
            if ($pesquisar_por != ''){
            
            //abre uma instancia do modelo na classe usuario
            $usuario = Container::getModel('Usuario');

            //seta o nome na classe, para poder ser usado no modelo
            $usuario->__set('nome', $pesquisar_por);

            //seta o id para colocar na query(nao pesquisar o usuario logado)
            $usuario->__set('id', $_SESSION['id']);
            
            $retorno_usuarios = $usuario->getAll();
        
            }
            
            //crio mais um atributo "retorno_usuarios" que corresponde ao mesmo nome da variavel que criei acima, para classe view que é criada dinamicamente
            $this->view->retorno_usuarios =  $retorno_usuarios;

            //echo "<br /> <br /> <br /><pre>";print_r( $retorno_usuarios);  echo "</pre>";

            
            //para exibir os dados do perfil do usuario (seguidores, seguindo e tweest criados)
            $info_usuario = Container::getModel('Usuario');
            $info_usuario->__set('id', $_SESSION['id'] );
            $info_perfil = $info_usuario->getInfoUsuario();

            $this->view->info_perfil =$info_perfil;

            
            //renderizo a página
            $this->render('quemSeguir');

        }


        //ação de seguir ou deixar de seguir
        public function acao(){

            //action responsavel por fazer validação se esta logado ou nao
            $this->validaAutenticacao();
            

            $acao = isset($_GET['acao']) ? $_GET['acao'] : '';
            
            $id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';

            $usuario = Container::getModel('Usuario');

            $usuario->__set('id', $_SESSION['id']);

            if($acao=='seguir'){

                $usuario->seguirUsuario($id_usuario_seguindo);

            }else if($acao == 'deixar_de_seguir'){

                $usuario->deixarSeguirUsuario($id_usuario_seguindo);

            }

            header('Location: /quem_seguir');

        }


        public function deletarTweet (){

            
            //verifica se esta logado
            $this->validaAutenticacao();

            //recebe qual tweet deve ser eliminado via get
            $idTweet = isset($_GET['idTweet']) ? $_GET['idTweet'] : '';

            if ($idTweet != '') {

               $tweet = Container::getModel('Tweet');
               //seto dados para poder usar no model tweet
               $tweet_selecionado = $tweet->__set('id', $idTweet);
               $tweet_selecionado = $tweet->__set('id_usuario', $_SESSION['id']);
              //chamo a ação de deletar do objeto
               $tweet->deletarTweet();
            
            }

            header('Location: /timeline');


            
        }
    }



