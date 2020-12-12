<?php

// Objeto de requisição
$request = new CoreExt_Controller_Request();

// Helper de URL. Auxilia para criar uma URL no formato http://www.example.org/module
$url = CoreExt_View_Helper_UrlHelper::getInstance();
$url = $url->url($request->get('REQUEST_URI'), ['components' => CoreExt_View_Helper_UrlHelper::URL_HOST]);

// Configura o baseurl da request
$request->setBaseurl(sprintf('%s/Module', $url));

// Configura o DataMapper para usar uma instância de Banco com fetch de resultados
// usando o tipo FETCH_ASSOC
CoreExt_DataMapper::setDefaultDbAdapter(new Banco(['fetchMode' => Banco::FETCH_ASSOC]));

// Inicia o Front Controller
$frontController = Front::getInstance();
$frontController->setRequest($request);

// Configura o caminho aonde os módulos estão instalados
$frontController->setOptions(
    ['basepath' => base_path('Ieducar/Modules')]
);

$frontController->dispatch();

// Resultado
print $frontController->getViewContents();
