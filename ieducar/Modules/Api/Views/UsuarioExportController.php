<?php

namespace iEducarLegacy\Modules\Api\Views;

use iEducarLegacy\Intranet\Source\PmiEducar\Usuario;
use iEducarLegacy\Lib\Portabilis\Controller\ApiCoreController;
use iEducarLegacy\Lib\Portabilis\String\Utils;

/**
 * Class UsuarioExportController
 * @package iEducarLegacy\Modules\Api\Views
 */
class UsuarioExportController extends ApiCoreController
{
    protected function exportUsers()
    {
        $instituicao = $this->getRequest()->instituicao;
        $escola = $this->getRequest()->escola;
        $status = $this->getRequest()->status;
        $tipoUser = $this->getRequest()->tipoUsuario;
        $getUsers = new Usuario();
        $getUsers->setOrderby('nome ASC');

        $lstUsers = $getUsers->listaExportacao(
            $escola,
            $instituicao,
            $tipoUser,
            $status
        );

        $csv = '';
        //Linhas do cabeçalho
        $csv .= 'Nome,';
        $csv .= 'Matricula,';
        $csv .= 'E-mail,';
        $csv .= 'Status,';
        $csv .= Utils::toLatin1('Tipo_usuário,');
        $csv .= Utils::toLatin1('Instituição,');
        $csv .= 'Escola,';
        $csv .= PHP_EOL;

        foreach ($lstUsers as $row) {
            $csv .= '"' . $row['nome'] . '",';
            $csv .= '"' . $row['Matricula'] . '",';
            $csv .= '"' . $row['email'] . '",';
            $csv .= '"' . $row['status'] . '",';
            $csv .= '"' . $row['nm_tipo'] . '",';
            $csv .= '"' . $row['nm_instituicao'] . '",';
            $csv .= '"' . $row['nm_escola'] . '",';
            $csv .= PHP_EOL;
        }

        return ['conteudo' => Utils::toUtf8($csv)];
    }

    public function Gerar()
    {
        if ($this->isRequestFor('get', 'exportarDados')) {
            $this->appendResponse($this->exportUsers());
        } else {
            $this->notImplementedOperationError();
        }
    }
}
