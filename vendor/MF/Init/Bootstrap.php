<?php
    //o nome do arquivo é bootstrap pq é um nome comum parar arquivos de inicialização de projeto de mvc
    //colocamos o script nessa pasta pq é uma pasta que foi configurada para ser onde são guardados os arquivos de inicialização do projeto, que foi setado na configuração do composer, dessa forma ele já fica disponível em todo projeto
    //esse script foi criado para não misturar no arquivo de rotas, pois é um script contendo lógica, não esta no controller pq é um arquivo de inicialização do projeto
namespace MF\Init;

abstract class Bootstrap { //a diferença é que a classe abstrata não pode ser instanciada, apenas herdada

    private $routes; //variavel para setar o array com dados da rota

    abstract protected function  initRoutes(); //a diferença é que a função abstrata não pode ser instanciada, apenas herdada, além de ser implementada apenas na classe filha, e protegida só pode ser herdade para classes parents

    public function __construct() { //o construct é usado como uma trigger para startar ações ao istanciar a classe na qual esta inserido
        $this->initRoutes(); //quando for instaciada a classe a primeira coisa que vai fazer é executar o metodo initRoutes
        $this->run($this->getUrl());
    }   

    public function getRoutes() {
        return $this->routes;
    }

    public function setRoutes(array $routes) {
        $this->routes = $routes;
    }

    //é protegido mas pode ser herdado
    protected function run($url) { //parametro url para armazenar a url, captura a url a partir do metodo construct que usa o getUrl
        //echo $url . '<hr> <br>';

        foreach($this->getRoutes() as $Key => $route) { //vai passar por cada array da initRoutes
            if($url == $route['route']) { //vai checar se a rota acessada pelo cliente tem o valor igual da posição [route] do array setado no metodo initRoutes
                $class = "App\\Controllers\\".ucfirst($route['controller']); //montado dinamicamente o nome da classe que vai ser estanciada.
                // essa classe é como se fosse criada em controllers, ucfirst serve para deixar a primeira letra em maisculo e o \\ para abreviar a concatenacao
                
                $controller = New $class; // estanciamos a nova classe, é o mesmo que:  App\Controllers\IndexController 
                
                $action = $route['action']; //criamos mais uma variavel para capturar o valor da posição action do array routes do metodo initRoutes, esse valor vai corresponder a um metodo do namespace indexController no diretorio controllers
                
                $controller->$action();

            
                /*
                echo '<pre>';
                print_r($value);
                echo '</pre>';
                
                echo '<hr> <br><br>';
                */
            }
        }
    }

    protected function getUrl(){
        return parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH); 
        //$_SERVER retorna um array com dados da url do server 
        //parse_url retorna apenas a rota da url
        //,PHP_URL_PATH faz um teste para pegar apenas a rota, e não misturar com query string por exemplo

    }
}

?>