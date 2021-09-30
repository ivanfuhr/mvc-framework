<?php

namespace src\app\route\functions;


class RouterCore
{

    public static $findedRoute = false;

    public function __construct()
    {
        require_once('../app/Route/RouterConfig.php');
        if (self::$findedRoute === false) {
            (new \src\app\controller\MessageController)->error('Erro 404!', 'Está rota não existe ou está invalida!', 404);
        }
    }

    protected static function executeController(string $callback, $args = [])
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
                ], [$args]);                                                                                                        //Cria e execute a classa/método
            } else {
                throw new \Exception('Está página pode estar em desenvolvimento, entre em contato com ' . CONTACT);           //Se existir alguma exeção cria uma nova instacia da página de erro
            }
        } catch (\Exception $e) {
            (new \src\app\controller\MessageController)->error('Ocorreu um erro inesperado!', $e->getMessage(), 404);
        }
    }

    protected static function analyzeRoute($route)
    {
        $args = [];

        //ROUTE ================================================
        $regex = '/\[(.*?)\]/';                                                  //$regex para encontrar os valores que estão entre []
        $route = substr($route, 1);                                              //Método para remover a primeira / da rota
        if (substr($route, -1) === '/') $route = substr($route, 0, -1);          //Método que remove a ultima / da rota
        preg_match_all($regex, $route, $regexResult);                            //Função que aplica $regex na variavel $route e devolve $regexResult com o resultado 
        $explodeRoute = explode('/', $route);                                    //Método para dividir a rota em um array a partir da /

        //URI ==================================================
        $explodeURI = explode('/', $_SERVER['REQUEST_URI']);                 //Método para dividir a URI da request em um array a partir da /
        $filterURI = array_filter($explodeURI);                              //Filtra a URI para previnir possiveis tentativas de queryInjection e remover espaços em branco
        $countURI = array_values($filterURI);                                //Recalcula as possições do array
        for ($i = 0; $i < UNSET_URI_COUNT; $i++) unset($countURI[$i]);       //Com base no que foi configurado no arquivo de configuração se remove as possições indesejaveis
        $countURI = array_values($countURI);                                 //Recalcula novamente as posições do array
        $convertedURI = [];

        //FIND ARGS  ===========================================
        foreach ($explodeRoute as $keyRoute => $route) {                         //Criado um laço que percorre todas as posições do array referente a rota
            if (count($countURI) === count($explodeRoute)) {
                if (isset($countURI[$keyRoute])) {                                   //Verifica se a possição da rota existe na URI
                    if ($route === '') {
                        array_push($convertedURI, $countURI[$keyRoute]);             //Se for ela é adionada a uma varivel chamada $convertedURI
                    }
                    if ($route === $countURI[$keyRoute]) {                           //Verificação com objetivo de analisar se a rota é a mesma que a URI
                        array_push($convertedURI, $countURI[$keyRoute]);             //Se for ela é adionada a uma varivel chamada $convertedURI
                    }
                }
            }
            foreach ($regexResult[0] as $keyResult => $result) {                         //Laço que percorre as posições referente a argumentos que ficam dentro de []
                if ($result === $route) {                                            //Se a posição da rota for a mesma que a posição do resultado ele entenderá está posição como um argumento
                    $args[$regexResult[1][$keyResult]] = $countURI[$keyRoute] ?? null;   //Então ele adiciona a um array chamado $args está possição que deve ser a mesma da URI
                    array_push($convertedURI, $result);                              //E ele adiona essa posição da URI a $convertedURI afim de poder verificar depois se a rota da URI é a mesma da rota chamada
                }
            }
        }

        return [
            'filtredURI' => implode('/', $countURI),
            'convertedURI' => count($convertedURI) === 0 ? implode('/', $countURI) : implode('/', $convertedURI ),
            'convertedRoute' =>  implode('/', $explodeRoute),
            'args' => $args
        ];
    }
}
