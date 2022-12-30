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

    public function updateProfile()
    {
        $usuarios = $this->idSaude->getUsers([
            'max' => 9999999
        ]);

        foreach ($usuarios as $usuario) {

            $this->idSaude->updateUser([
                'id' => $usuario['id'],
                'requiredActions' => ['UPDATE_PROFILE']
            ]);

            print_r($this->idSaude->getUser([
                'id' => $usuario['id']
            ]));
        }
    }

    public function migraCPFAtributoParaUsername()
    {
        $usuarios = $this->idSaude->getUsers([
            'max' => 9999999
        ]);
        $migrados = 0;
        foreach ($usuarios as $usuario) {
            if (isset($usuario['attributes']) &&
                isset($usuario['attributes']['CPF'])) {
                $cpf = $usuario['attributes']['CPF'][0];
               
                if (strlen($cpf) == 11) {
                    $dadosKeycloak = [
                        'username' =>$cpf,
                        'id' => $usuario['id']
                    ];
    
                    $this->idSaude->updateUser($dadosKeycloak);
                    $migrados++;
                }
            }
        }
        $totalUsuarios = count($usuarios);
        return "Sucesso - Migrados {$migrados} de {$totalUsuarios} usuários. Verifique na instância do ID Saúde a migração";
    }
}
