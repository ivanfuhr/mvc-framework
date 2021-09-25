<?php

namespace src\app\route;

class Router
{
    private $method;
    private $uri;
    private $getArr;

    public function __construct()
    {
        $this->initialize();
        require_once('../app/Route/RouterConfig.php');
        $this->execute();
    }

    private function execute()
    {
        switch ($this->method) {
            case 'GET':
                $this->executeGet();
                break;
            case 'POST':
                break;
        }
    }


    private function get($router, $call)
    {
        $this->getArr[] = [
            'router' => $router,
            'callback'   => $call
        ];
    }

    private function executeGet()
    {
        $pageFound = false;
        foreach ($this->getArr as $get) {
            $cleanRoute = substr($get['router'], 1);                                        //Remove a primeira '/' da rota
            if (substr($cleanRoute, -1) === '/') $cleanRoute = substr($cleanRoute, 0, -1);  //Se houver uma barra no final da rota ela será removida
            if ($this->uri === $cleanRoute) {                                               //Verifica se a URI existe na base da Router
                $pageFound = true;
                if (is_callable($get['callback'])) {                                        //Verifica se $get['callback'] é uma funçao
                    $get['callback']();                                                     //Se for uma função a mesma será executada
                    break;
                } else {
                    $this->executeController($get['callback']);
                }
            }
        }
        if (!$pageFound)
            echo 'Erro 404! Página näo encontrada!';                                        //Se nenhuma página for encontrada retornará uma mensagem de erro!
    }

    private function executeController($callback)
    {
        try {
            if (preg_match('/' . DEFAULT_CONTROLLER_OPERATOR . '/', $callback)) {                                             //Verifica se foi definido alguma callback na RouterConfig
                $explodeCallback = explode(DEFAULT_CONTROLLER_OPERATOR, $callback);                                           //Divide o callback entre Controller e Método
                $controller = $explodeCallback[0];                                                                            //Define a posição 0 como controller
                $method = $explodeCallback[1];                                                                                //Define a posição 1 como método
                if (empty($controller) || empty($method)) throw new \Exception('Método ou controladora não definidos!');      //Verifica se o método ou a controladora não estão vazios
                $pathController = 'src\\app\\controller\\' . $controller;                                                     //Caminho da controladora
                if (!class_exists($pathController)) throw new \Exception('Controladora invalida ou inexistente!');            //Verifica se a classe existe
                if (!method_exists($pathController, $method)) throw new \Exception('Método invalido ou inexistente!');        //Verifica se o metodo existe
                call_user_func_array([
                    new $pathController,
                    $method
                ], []);                                                                                                        //Cria e execute a classa/método
            } else {
                throw new \Exception('Está página pode estar em desenvolvimento, entre em contato com ' . CONTACT);           //Se existir alguma exeção cria uma nova instacia da página de erro
            }
        } catch (\Exception $e) {
            (new \src\app\controller\MessageController)->error('Ocorreu um erro inesperado!', $e->getMessage(), 404);
        }
    }

    private function initialize()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];                     //Define o método da requisição
        $this->uri = $this->normalizeURI($_SERVER['REQUEST_URI']);      //Normaliza a URI
    }

    private function normalizeURI($uri)
    {
        $explodeURI = explode('/', $uri);                                //Divide a URI em um array quebrando apartir da '/'
        $filterURI = array_filter($explodeURI);                          //Elimina espaços vazios entre a URI
        $countURI = array_values($filterURI);                            //Calcula os valores do array
        for ($i = 0; $i < UNSET_URI_COUNT; $i++) unset($countURI[$i]);   //Remove valores desnecessarios da URI definidos na config
        $filteredURI = implode('/', $countURI);                          //Converte o array em string apartir da '/'
        return $filteredURI;
    }
}
