<?php

namespace App\Console\Commands;

use App\Services\KeycloakService;
use Exception;
use Illuminate\Console\Command;

class MigraCpfUsername extends Command
{
    private $keycloakService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'idsaude:migra-cpf-username';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migração do CPF atributo para Username no IDSaúde';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->keycloakService = new KeycloakService();
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $this->keycloakService->migraCPFAtributoParaUsername();
        } catch (Exception $e) {
            return ['erro' => $e->getMessage()];
        }
    }
}
