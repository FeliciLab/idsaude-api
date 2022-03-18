<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\KeycloakService;
use Exception;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private $keycloakService;

    public function __construct(KeycloakService $keycloakService)
    {
        $this->keycloakService = $keycloakService;
    }

    public function usernameCadastrado($username)
    {
        $dados = ['username' => $username];
        $validacao = Validator::make($dados, [
            'username' => 'required',
        ]);

        if ($validacao->fails()) {
            return response()->json([
                'sucesso' => true,
                'mensagem' =>  $validacao->errors()
            ]);
        }

        $cpfCadastrado = $this->keycloakService->usernameExiste($username);

        return response()->json([
            'existe' => $cpfCadastrado
        ]);
    }
}
