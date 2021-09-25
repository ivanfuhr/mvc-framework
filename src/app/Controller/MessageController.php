<?php

namespace src\app\controller;

use src\app\util\Loader;

class MessageController extends Loader{
    public function error(string $title, string $description, $reponseCode = 404)
    {
        http_response_code($reponseCode);
        $this->load('errors/error', [
            'title' => $title,
            'description' => $description
        ]);
    }
}