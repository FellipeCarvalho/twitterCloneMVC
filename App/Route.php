<?php

    //Nesse arquivo ficaram apenas realmente os dados referente as rotas

    namespace App;

    use MF\Init\Bootstrap; //chamo o namespace com a lógica de inicialização do projeto, para capturar os dados inseridos na url

    
    class Route  extends  Bootstrap {

        protected function  initRoutes() { //coloquei como protegida pq no arquivo com a lógica eu crio essa função para ser apenas implementada aqui
            
           //abaixo array  routes com as rotas em si

            $routes['home'] = array(
                'route' => '/',
                'controller' => 'indexController',
                'action' => 'index'
            );

            $routes['inscreverse'] = array(
                'route' => '/inscreverse',
                'controller' => 'indexController',
                'action' => 'inscreverse'
            );

            
            $routes['registrar'] = array(
                'route' => '/registrar',
                'controller' => 'indexController',
                'action' => 'registrar'
            );

                    
            $routes['autenticar'] = array(
                'route' => '/autenticar',
                'controller' => 'authController',
                'action' => 'autenticar'
            );

            $routes['timeline'] = array(
                'route' => '/timeline',
                'controller' => 'AppController',
                'action' => 'timeline'
            );

            $routes['sair'] = array(
                'route' => '/sair',
                'controller' => 'AuthController',
                'action' => 'sair'
            );

            
            $routes['tweet'] = array(
                'route' => '/tweet',
                'controller' => 'AppController',
                'action' => 'tweet'
            );

            $routes['quem_seguir'] = array(
                'route' => '/quem_seguir',
                'controller' => 'AppController',
                'action' => 'quemSeguir'
            );

            
            $routes['acao'] = array(
                'route' => '/acao',
                'controller' => 'AppController',
                'action' => 'acao'
            );

            $routes['deletar_tweet'] = array(
                'route' => '/deletar_tweet',
                'controller' => 'AppController',
                'action' => 'deletarTweet'
            );




    
            $this->setRoutes($routes); //vai setar os arrays no construtor que foi criado no arquivo de inicialização
        }

      
    }

?>