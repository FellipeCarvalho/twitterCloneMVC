<?php
    namespace App\Controllers;

    //CHAMADAS DE DEPENDENCIAS DO FRAMEWORK
    use MF\Controller\Action; 
    use MF\Model\Container; //para fazer a conexão com banco e chamar o modelo

    class AuthController extends Action {

            public function autenticar(){
                
                $usuario = Container::getModel('Usuario');

                $usuario->__set('email', $_POST['email']);
                $usuario->__set('senha', md5($_POST['senha']));

                $usuario->autenticar();
            
                        
                if ($usuario->__get('id') != '' && $usuario->__get('nome')!= ''){
                    //echo 'Usuario Autenticado';
                    session_start();  //é necessario iniciar o session para armazenar o id do usuario, para saber que ele esta logado durante a navegação
                
                    //armazena na superglobal session os ids
                    $_SESSION['id'] = $usuario->__get('id'); 
                    $_SESSION['nome'] = $usuario->__get('nome');

                    //redireciona para pagina protegida
                    header('Location: /timeline');

                }else{
                    header('Location: /?login=erroAutenticar'); //cria um parametro que , sera tratado no Indexcontroller que cuida do index
                }
            }

            public function sair(){
                session_start(); //para usar a session
                session_destroy();
                header('Location: /');

            }


    }