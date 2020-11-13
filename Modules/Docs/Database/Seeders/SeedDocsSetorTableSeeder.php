<?php

namespace Modules\Docs\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class SeedDocsSetorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       /*
        * Qualidade - O setor mais importante da empresa
        */
        $qualidade               = new Setor();
        $qualidade->nome         = "Qualidade";
        $qualidade->sigla        = "QUA";
        $qualidade->descricao    = "Responsável por garantir o cumprimento das políticas da empresa.";
        $qualidade->tipo_setor_id= Constants::$ID_TIPO_SETOR_SETOR_NORMAL;
        $qualidade->save();
        

        
        /*
        * Diretoria e Gerência - 'Setores' especiais que informarão quais usuários podem ser aprovadores 
        */
        // $diretoria              = new Setor();
        // $diretoria->nome        = "Diretoria";
        // $diretoria->sigla       = "DIR";
        // $diretoria->descricao   = "Responsável em dirigir, planejar, organizar e controlar as atividades de diversas áreas da empresa.";
        // $diretoria->tipo_setor_id = Constants::$ID_TIPO_SETOR_DIRETORIA;
        // $diretoria->save();
        
        // $gerencia               = new Setor();
        // $gerencia->nome         = "Gerência";
        // $gerencia->sigla        = "GER";
        // $gerencia->descricao    = "Responsável por gerenciar diversas atividades da empresa.";
        // $gerencia->tipo_setor_id= Constants::$ID_TIPO_SETOR_GERENCIA;
        // $gerencia->save();   


        
        /*
        * Setores "Normais" da empresa
        */
        $administrativo                 = new Setor();
        $administrativo->nome           = "Administrativo";
        $administrativo->sigla          = "ADM";
        $administrativo->descricao      = "Responsável pelo controle de receitas e despesas e pelo gerenciamento das tarefas e rotinas da empresa.";
        $administrativo->tipo_setor_id  = Constants::$ID_TIPO_SETOR_SETOR_NORMAL;
        $administrativo->save();
        
        $armadores                  = new Setor();
        $armadores->nome            = "Armadores";
        $armadores->sigla           = "ARM";
        $armadores->descricao       = "Responsável por gerenciar as embarcações.";
        $armadores->tipo_setor_id   = Constants::$ID_TIPO_SETOR_SETOR_NORMAL;
        $armadores->save();
        
        $cdi                = new Setor();
        $cdi->nome          = "CDI";
        $cdi->sigla         = "CDI";
        $cdi->descricao     = "Responsável por garantir a segurança empresarial, possibilitando a padronização de processos e o fluxo de informações.";
        $cdi->tipo_setor_id = Constants::$ID_TIPO_SETOR_SETOR_NORMAL;
        $cdi->save();
        
        $compras                = new Setor();
        $compras->nome          = "Compras";
        $compras->sigla         = "CMP";
        $compras->descricao     = "Responsável pelo estabelecimento dos fluxos dos materiais da empresa.";
        $compras->tipo_setor_id = Constants::$ID_TIPO_SETOR_SETOR_NORMAL;
        $compras->save();
        
        $comercial              = new Setor();
        $comercial->nome        = "Comercial";
        $comercial->sigla       = "COM";
        $comercial->descricao   = "Responsável direto pelos ganhos da empresa.";
        $comercial->tipo_setor_id = Constants::$ID_TIPO_SETOR_SETOR_NORMAL;
        $comercial->save();
        
        $comunicacao              = new Setor();
        $comunicacao->nome        = "Comunicação";
        $comunicacao->sigla       = "COC";
        $comunicacao->descricao   = "Responsável em manter informado todos os colaborades da empresa, parceiros e prestadores de serviço.";
        $comunicacao->tipo_setor_id = Constants::$ID_TIPO_SETOR_SETOR_NORMAL;
        $comunicacao->save();
        
        $controladoria              = new Setor();
        $controladoria->nome        = "Controladoria";
        $controladoria->sigla       = "COT";
        $controladoria->descricao   = "Responsável pela organização, avaliação e armazenamento das informações da empresa.";
        $controladoria->tipo_setor_id = Constants::$ID_TIPO_SETOR_SETOR_NORMAL;
        $controladoria->save();
        
        $financeiro              = new Setor();
        $financeiro->nome        = "Financeiro";
        $financeiro->sigla       = "FIN";
        $financeiro->descricao   = "Responsável por administrar os recursos da empresa.";
        $financeiro->tipo_setor_id = Constants::$ID_TIPO_SETOR_SETOR_NORMAL;
        $financeiro->save();
        
        $juridico              = new Setor();
        $juridico->nome        = "Jurídico";
        $juridico->sigla       = "JUR";
        $juridico->descricao   = "Responsável em orientar os assuntos jurídicos da empresa.";
        $juridico->tipo_setor_id = Constants::$ID_TIPO_SETOR_SETOR_NORMAL;
        $juridico->save();
        
        $manutencao              = new Setor();
        $manutencao->nome        = "Manutenção";
        $manutencao->sigla       = "MAN";
        $manutencao->descricao   = "Responsável em realizar serviços para conservação da infraestrutura da empresa.";
        $manutencao->tipo_setor_id = Constants::$ID_TIPO_SETOR_SETOR_NORMAL;
        $manutencao->save();
        
        $meio_ambiente              = new Setor();
        $meio_ambiente->nome        = "Meio Ambiente";
        $meio_ambiente->sigla       = "AMB";
        $meio_ambiente->descricao   = "Responsável por desenvolver métodos e ações, pautando-se nas noções de sustentabilidade e responsabilidade socioambiental.";
        $meio_ambiente->tipo_setor_id = Constants::$ID_TIPO_SETOR_SETOR_NORMAL;
        $meio_ambiente->save();
        
        $operacao              = new Setor();
        $operacao->nome        = "Operação";
        $operacao->sigla       = "OPE";
        $operacao->descricao   = "Responsável pelo planejamento, implantação e manutenação de toda a infraestrutura da empresa.";
        $operacao->tipo_setor_id = Constants::$ID_TIPO_SETOR_SETOR_NORMAL;
        $operacao->save();
        
        $pessoas_organizacao              = new Setor();
        $pessoas_organizacao->nome        = "Pessoas & Organização";
        $pessoas_organizacao->sigla       = "P&O";
        $pessoas_organizacao->descricao   = "Responsável por potencializar o capital humano.";
        $pessoas_organizacao->tipo_setor_id = Constants::$ID_TIPO_SETOR_SETOR_NORMAL;
        $pessoas_organizacao->save();
        
        $processos_aduaneiros              = new Setor();
        $processos_aduaneiros->nome        = "Processos Aduaneiros";
        $processos_aduaneiros->sigla       = "ADU";
        $processos_aduaneiros->descricao   = "Responsável pelas importações e exportações da empresa.";
        $processos_aduaneiros->tipo_setor_id = Constants::$ID_TIPO_SETOR_SETOR_NORMAL;
        $processos_aduaneiros->save();
        
        $projetos              = new Setor();
        $projetos->nome        = "Projetos";
        $projetos->sigla       = "PRJ";
        $projetos->descricao   = "Responsável em planejar, controlar e executar os projetos da empresa.";
        $projetos->tipo_setor_id = Constants::$ID_TIPO_SETOR_SETOR_NORMAL;
        $projetos->save();
        
        $saude              = new Setor();
        $saude->nome        = "Saúde";
        $saude->sigla       = "SOC";
        $saude->descricao   = "Responsável por orientar e previnir a saúde dos colaboradores.";
        $saude->tipo_setor_id = Constants::$ID_TIPO_SETOR_SETOR_NORMAL;
        $saude->save();
        
        $seguranca_do_trabalho              = new Setor();
        $seguranca_do_trabalho->nome        = "Segurança do Trabalho";
        $seguranca_do_trabalho->sigla       = "SET";
        $seguranca_do_trabalho->descricao   = "Responsável em traçar e implantar meios de projeger o colaborador de possíveis acidentes de trabalho.";
        $seguranca_do_trabalho->tipo_setor_id = Constants::$ID_TIPO_SETOR_SETOR_NORMAL;
        $seguranca_do_trabalho->save();
        
        $seguranca_patrimonial              = new Setor();
        $seguranca_patrimonial->nome        = "Segurança Patrimonial";
        $seguranca_patrimonial->sigla       = "SEP";
        $seguranca_patrimonial->descricao   = "Responsável por prevenir e reduzir perdas patrimoniais na empresa.";
        $seguranca_patrimonial->tipo_setor_id = Constants::$ID_TIPO_SETOR_SETOR_NORMAL;
        $seguranca_patrimonial->save();
        
        $ti              = new Setor();
        $ti->nome        = "Tecnologia da Informação";
        $ti->sigla       = "TEC";
        $ti->descricao   = "Responsável por gerenciar as informações da empresa.";
        $ti->tipo_setor_id = Constants::$ID_TIPO_SETOR_SETOR_NORMAL;
        $ti->save();
        
        $transporte              = new Setor();
        $transporte->nome        = "Transporte";
        $transporte->sigla       = "TRP";
        $transporte->descricao   = "Responsável em atuar com a rotina de operação de transporte.";
        $transporte->tipo_setor_id = Constants::$ID_TIPO_SETOR_SETOR_NORMAL;
        $transporte->save();
        
        /*
        * Sem Setor - Criado para usuários que acabaram de ser importados do AD
        */
        $sem_setor              = new Setor();
        $sem_setor->nome        = "Sem Setor";
        $sem_setor->sigla       = "SS";
        $sem_setor->descricao   = "Setor criado para vincular usuários que acabaram de ser importados do AD.";
        $sem_setor->tipo_setor_id = Constants::$ID_TIPO_SETOR_SETOR_NORMAL;
        $sem_setor->save();
    }
}
