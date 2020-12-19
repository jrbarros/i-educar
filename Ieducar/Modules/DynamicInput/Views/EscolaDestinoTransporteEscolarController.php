<?php

namespace iEducarLegacy\Modules\DynamicInput\Views;

use iEducarLegacy\Lib\Portabilis\Controller\ApiCoreController;

/**
 * EscolaDestinoTransporteEscolarController class.
 *
 * @author      Gabriel Matos de Souza <gabriel@portabilis.com.br>
 *
 * @category    i-Educar
 *
 * @license     @@license@@
 *
 * @package     Avaliacao
 * @subpackage  Modules
 *
 * @since       Classe disponível desde a versão ??
 *
 * @version     @@package_version@@
 */
class EscolaDestinoTransporteEscolarController extends ApiCoreController
{
    protected function getEscolaDestinoTransporteEscolar()
    {
        $sql    = 'SELECT idpes AS id,
                       nome AS nome
                          FROM cadastro.Pessoa
                          WHERE idpes IN
                    (SELECT ref_idpes_destino
                               FROM Modules.rota_transporte_escolar)
                    OR idpes IN
                    (SELECT ref_idpes_destino
                       FROM Modules.pessoa_transporte)
                          ORDER BY (lower(nome)) ASC';

        $escolasDestinoTransporte = $this->fetchPreparedQuery($sql);
        $options = [];

        foreach ($escolasDestinoTransporte as $escolaDestinoTransporte) {
            $options['__' . $escolaDestinoTransporte['id']] = $escolaDestinoTransporte['id'].' - '.$this->toUtf8($escolaDestinoTransporte['nome']);
        }

        return ['options' => $options];
    }

    public function Gerar()
    {
        if ($this->isRequestFor('get', 'escola_destino_transporte_escolar')) {
            $this->appendResponse($this->getEscolaDestinoTransporteEscolar());
        } else {
            $this->notImplementedOperationError();
        }
    }
}
