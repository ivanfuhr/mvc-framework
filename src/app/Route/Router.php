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
        require_once('../app/config/RouterConfig.php');
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
                }
            }
        }
        if(!$pageFound) echo 'ERRO 404, página não encontrada!';                            //Se nenhuma página for encontrada retornará uma mensagem de erro!
    }

    private function initialize()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = $this->normalizeURI($_SERVER['REQUEST_URI']);
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
