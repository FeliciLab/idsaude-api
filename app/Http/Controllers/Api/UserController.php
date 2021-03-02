<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\KeycloakService;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
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

        $keycloakService = new KeycloakService();
        $cpfCadastrado = $keycloakService->usernameExiste($username);

        return response()->json([
            'existe' => $cpfCadastrado
        ]);
    }
}
