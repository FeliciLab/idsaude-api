<?php

namespace App\Services;

use App\Helpers\CPFHelper;
use Keycloak\Admin\KeycloakClient;

class KeycloakService
{
    private $idSaude;

    public function __construct()
    {
        $this->idSaude = KeycloakClient::factory([
            'realm' => env('KEYCLOAK_ADMIN_REALM'),
            'username' => env('KEYCLOAK_ADMIN_USER'),
            'password' => env('KEYCLOAK_ADMIN_PASSWORD'),
            'client_id' => env('KEYCLOAK_ADMIN_CLIENTID'),
            'baseUri' => env('KEYCLOAK_URI'),
            'grant_type' => env('KEYCLOAK_ADMIN_GRANTTYPE')
        ]);
        $this->idSaude->setRealmName(env('KEYCLOAK_REALM_DEFAULT'));
    }

    public function usernameExiste($username)
    {
        if (is_numeric($username) && strlen($username) == 11) {
            $username = CPFHelper::formataCpf($username);
        }

        $usuarios = $this->idSaude->getUsers([
            'search' => $username
        ]);

        foreach ($usuarios as $usuario) {
            if ( $usuario['username'] == $username ||
                 $usuario['email'] == $username ) {
                return true;
            }
        }

        return false;
    }

    public function migraCPFAtributoParaUsername()
    {
        $usuarios = $this->idSaude->getUsers([
            'max' => 9999999
        ]);

        foreach ($usuarios as $usuario) {
            if (isset($usuario['attributes']) &&
                isset($usuario['attributes']['CPF'])) {
                $cpf = $usuario['attributes']['CPF'][0];

                $dadosKeycloak = [
                    'username' => strlen($cpf) == 11 ? CPFHelper::formataCpf($cpf) : $cpf,
                    'id' => $usuario['id']
                ];

                $this->idSaude->updateUser($dadosKeycloak);
            }
        }
    }
}
