<?php

namespace iEducarLegacy\Lib\Portabilis\Report;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

/**
 * Class ReportsRenderServerFactory
 * @package iEducarLegacy\Lib\Portabilis\Report
 */
class ReportsRenderServerFactory extends ReportFactory
{
    /**
     * URL onde se encontra o serviço de renderização.
     *
     * @var string
     */
    private $url;

    /**
     * Caminho onde estão os arquivos .jasper.
     *
     * @var string
     */
    private $sourcePath;

    /**
     * Token para autorização do serviço.
     *
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $logo;

    /**
     * @var array
     */
    private $connection;

    /**
     * @var string
     */
    private $timezone;

    /**
     * Loga erros da requisição ao servidor de relatórios.
     *
     * @param array  $payload
     * @param string $response
     *
     * @return void
     * @throws \JsonException
     */
    protected function log($payload, $response)
    {
        $log = json_encode([
            'url' => $this->url,
            'payload' => $payload,
            'response' => $response,
        ], JSON_THROW_ON_ERROR);

        Log::error($log);
    }

    /**
     * @inheritdoc
     */
    public function setSettings($config)
    {
        $this->url = $config->report->remote_factory->url;
        $this->sourcePath = $config->report->source_path;
        $this->token = $config->report->remote_factory->token;
        $this->logo = $config->report->logo_file_name;
        $this->timezone = $config->app->locale->timezone;
        $this->connection = [
            'host' => $config->app->database->hostname,
            'port' => $config->app->database->port,
            'database' => $config->app->database->dbname,
            'username' => $config->app->database->username,
            'password' => $config->app->database->password,
        ];
    }

    /**
     * Renderiza o relatório.
     *
     * @param ReportCore $report
     * @param array      $options
     *
     * @return string
     * @throws GuzzleException
     */
    public function dumps(ReportCore $report, $options = [])
    {
        $options = self::mergeOptions($options, [
            'add_logo_name_arg' => true,
            'encoding' => 'uncoded'
        ]);

        if ($options['add_logo_name_arg'] and !$this->logo) {
            throw new \Exception('The option \'add_logo_name_arg\' is true, but no logo_name defined in configurations!');
        } elseif ($options['add_logo_name_arg']) {
            $report->addArg('logo', $this->logo);
        }

        $client = new Client([
            'http_errors' => false
        ]);

        $templateName = $report->templateName();
        $params = $report->args;

        $params['timezone'] = $this->timezone;

        $data = [];
        if ($report->useJson()) {
            $params['datasource'] = 'json';
            $this->url = str_replace('/deprecated', '', $this->url);
            $this->sourcePath = str_replace('/deprecated', '', $this->sourcePath);

            $data = $report->getJsonData();
            $data = $report->modify($data);
        } else {
            $params['datasource'] = 'database';
            $params['connection'] = 'postgresql';

            $params += $this->connection;
        }

        $url = $this->sourcePath;

        $payload = [
            'json' => [
                'report' => $templateName,
                'url' => $url,
                'data' => $data,
                'params' => $params,
            ],
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Token ' . $this->token,
            ]
        ];

        $response = $client->request('POST', $this->url, $payload);

        $json = json_decode($response->getBody()->getContents(), true);

        if (config('legacy.report.debug')) {
            $this->log($payload, $response->getBody()->getContents());
        }

        if (is_null($json)) {
            $this->log($payload, $response->getBody()->getContents());

            throw new \Exception('Não foi possível analisar a resposta do serviço.');
        }

        if ($json['success'] == false) {
            $this->log($payload, $response->getBody()->getContents());

            throw new \Exception($json['error'] ?? $json['message']);
        }

        $report = base64_decode($json['data']['file']);

        return $report;
    }
}
