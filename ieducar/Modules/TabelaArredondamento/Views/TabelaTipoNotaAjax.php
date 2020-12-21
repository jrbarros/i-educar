<?php

use iEducarLegacy\Modules\TabelaArredondamento\Model\TabelaDataMapper;

return __DIR__ . '/../../../../vendor/autoload.php';

$tabelas = [];

if (isset($_GET['tipoNota'])) {
    $tabela = new TabelaDataMapper();
    $tabelas = $tabela->findAll([], ['tipoNota' => (int) $_GET['tipoNota']]);
}

header('Content-type: text/xml');

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<query xmlns=\"sugestoes\">\n";

foreach ($tabelas as $tabela) {
    echo sprintf('<tabela id="%d">%s</tabela>', $tabela->id, $tabela->nome);
}

echo '</query>';
