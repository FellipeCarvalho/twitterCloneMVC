<?php
    namespace App\Controllers;

    //CHAMADAS DE DEPENDENCIAS DO FRAMEWORK
    use MF\Controller\Action; 
    use MF\Model\Container; //para fazer a conexão com banco e chamar o modelo
    

    class IndexController  extends Action { //herdando da classe action que esta armazenada no diretorio com scrit estrutural apenas para ser usado aqui ou em outros controllers

       
        public function index() {

           //criamos mais um atributo para class dinamica view "login", se tiver na url como parametro ,ele pega do get o login, caso contrario ele fica vazio
           $this->view->login = isset($_GET['login']) ? $_GET['login'] : '';
        

           $this->render('index', 'layout'); //informa na ação render o atributo que deve ser o nome do script de destino

        }

               
        public function inscreverse() {


            //como foi usado em outra rota a captura dos dados preenchidos, é necessario passar nessa rota os dados como vazios para que ele n de erro na tela ao acessar por essa rota
            $this->view->dadosUsuario = array(
                'nome'  => '',
                'email' => '',
                'senha' => '',
            );

            
            $this->view->erroInscricao =  false; //criei esse atributo para enviar a pagina renderizada a informação que deu errado

            $this->render('inscreverse', 'layout'); //informa na ação render o atributo que deve ser o nome do script de destino
 
         }

        public function registrar() {

           //receber os dados do form

            /*echo "<pre>";print_r($_POST);echo "</pre>"; */
           $usuario = Container::getModel('Usuario');
           $usuario->__set('nome', $_POST['nome']);
           $usuario->__set('email', $_POST['email']);
           $usuario->__set('senha', md5($_POST['senha']));

           /*echo "<pre>";print_r($usuario); echo "</pre>";*/

           //validar
           if ($usuario->validaCamposCadastro() && count($usuario->validaDuplicidadeCadastro())==0){
            
               $usuario->salvar();
              $this->render('cadastro','layout');

           }else{

              //em caso de erro ele não limpa os dados dos campos, fazendo com que não precise digitar tudo novamente
              $this->view->dadosUsuario = array(
                  'nome'  => $_POST['nome'],
                  'email' => $_POST['email'],
                  'senha' => $_POST['senha'],
              );

              $this->view->erroInscricao =true; //para exibir msg em vermelhor que deu errado o cadastro 

  

              $this->render('inscreverse','layout');
           }
               
           

  

 
         }
         
         

    }


    
    
?> 