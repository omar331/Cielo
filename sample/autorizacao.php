<?php
require_once __DIR__ . '/../vendor/autoload.php';

/* @var $transacao \MrPrompt\Cielo\Transacao */
$transacao = require_once __DIR__ . '/resources/transacao.php';

/* @var $cielo \MrPrompt\Cielo\Cliente */
$cielo     = require_once __DIR__ . '/resources/cliente.php';

try {
    $requisicao = $cielo->autoriza($transacao);

    print_r($requisicao);
} catch (\InvalidArgumentException $ex) {
    echo "# ERRO: {$ex->getCode()} - {$ex->getMessage()}" . PHP_EOL;
}
