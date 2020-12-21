<?php

// Objeto de requisição
use iEducarLegacy\Intranet\Source\Banco;
use iEducarLegacy\Lib\CoreExt\Controller\Front;
use iEducarLegacy\Lib\CoreExt\Controller\Request;
use iEducarLegacy\Lib\CoreExt\DataMapper;
use iEducarLegacy\Lib\CoreExt\View\Helper\Url;

require_once __DIR__ . '/../../vendor/autoload.php';

$request = new Request();

// Helper de URL. Auxilia para criar uma URL no formato http://www.example.org/module

$url = Url::getInstance()->url($request->get('REQUEST_URI'), ['components' => Url::URL_HOST]);

// Configura o baseurl da request
$request->setBaseurl(sprintf('%s/Module', $url));

// Configura o DataMapper para usar uma instância de Banco com fetch de resultados
// usando o tipo FETCH_ASSOC
DataMapper::setDefaultDbAdapter(new Banco(['fetchMode' => Banco::FETCH_ASSOC]));

// Inicia o Front Controller
$frontController = Front::getInstance();
$frontController->setRequest($request);

// Configura o caminho aonde os módulos estão instalados
$frontController->setOptions(
    ['basepath' => base_path('ieducar/Modules')]
);

$frontController->dispatch();

// Resultado
print $frontController->getViewContents();
