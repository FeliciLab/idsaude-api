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
    public function __construct(KeycloakService $keycloakService)
    {
        $this->keycloakService = $keycloakService;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $mensagem = $this->keycloakService->migraCPFAtributoParaUsername();
            $this->info($mensagem);
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
