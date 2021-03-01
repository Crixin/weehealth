<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**CORE*/
        $this->call(\Modules\Core\Database\Seeders\SeedCoreCidadeTableSeeder::class);
        $this->call(\Modules\Core\Database\Seeders\SeedCoreParametroTableSeeder::class);
        $this->call(\Modules\Core\Database\Seeders\SeedCorePerfilTableSeeder::class);
        $this->call(\Modules\Core\Database\Seeders\SeedCoreSetupTableSeeder::class);
        $this->call(\Modules\Core\Database\Seeders\SeedCoreUserTableSeeder::class);
        $this->call(\Modules\Core\Database\Seeders\SeedCoreCreateParametroTableSeeder::class);
        $this->call(\Modules\Core\Database\Seeders\SeedCoreSetorTableSeeder::class);
        $this->call(\Modules\Core\Database\Seeders\SeedCoreCreateParametroTipoEnvioNotificacaoTableSeeder::class);
        $this->call(\Modules\Core\Database\Seeders\SeedCoreCreateParametroTipoNotificacaoTableSeeder::class);
        $this->call(\Modules\Core\Database\Seeders\SeedCoreCreateGrupoUserBDTableSeeder::class);
        $this->call(\Modules\Core\Database\Seeders\SeedCoreCreateFunctionInsereLogTableSeeder::class);
        $this->call(\Modules\Core\Database\Seeders\CreateParamAreaGedTableSeeder::class);
        $this->call(\Modules\Core\Database\Seeders\SeedCoreCreateParametroNotificacaoAprovacao::class);
        $this->call(\Modules\Core\Database\Seeders\SeedCoreCreateParametroNotificacaoRejeicao::class);
        $this->call(\Modules\Core\Database\Seeders\SeedCoreCreateParametroDocumentoVencidoTableSeeder::class);
        $this->call(\Modules\Core\Database\Seeders\SeedCoreCreateParametroValidadeDocumentoTableSeeder::class);

        /**PORTAL */
        $this->call(\Modules\Portal\Database\Seeders\SeedPortalProcessoTableSeeder::class);

        /**MODULO DOCS */
        $this->call(\Modules\Docs\Database\Seeders\SeedDocsCreateParametroStatusEtapaFluxoTableSeeder::class);
        $this->call(\Modules\Docs\Database\Seeders\SeedDocsCreateParametroTipoControleRegistroTableSeeder::class);
        $this->call(\Modules\Docs\Database\Seeders\SeedDocsCreateParametroPadraoNumeroTableSeeder::class);
        $this->call(\Modules\Docs\Database\Seeders\SeedDocsCreateParametroNivelAcessoTableSeeder::class);
        $this->call(\Modules\Docs\Database\Seeders\CreateParametroExtensoesDocsOnlyofficeTableSeeder::class);
        $this->call(\Modules\Docs\Database\Seeders\DocsCreateParametroPerfilElaboradorSeeder::class);
        $this->call(\Modules\Docs\Database\Seeders\SeedDocsCreateParametroPrefixoNameDocumentoTableSeeder::class);
    }
}
