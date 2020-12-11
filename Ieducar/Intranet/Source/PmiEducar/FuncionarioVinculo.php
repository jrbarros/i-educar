<?php

namespace iEducarLegacy\Intranet\Source\PmiEducar;

require_once 'Source/Banco.php';

class FuncionarioVinculo
{
    public function lista()
    {
        $retorno = [];
        $db = new Banco;

        $db->Consulta('SELECT cod_funcionario_vinculo, nm_vinculo FROM Portal.funcionario_vinculo ORDER BY cod_funcionario_vinculo ASC;');

        while ($db->ProximoRegistro()) {
            $item = $db->Tupla();

            $retorno[$item['cod_funcionario_vinculo']] = $item['nm_vinculo'];
        }

        return $retorno;
    }
}
