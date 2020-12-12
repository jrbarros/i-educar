<?php

namespace iEducarLegacy\Lib\Portabilis\Report;

use iEducarLegacy\Lib\Portabilis\Collection\Utils;

/**
 * Class ReportFactory
 *
 * @package iEducarLegacy\Lib\Portabilis\Report
 */
abstract class ReportFactory
{
    /**
     * @var object
     */
    public $config;

    /**
     * @var array
     */
    public $settings;

    /**
     * ReportFactory constructor.
     */
    public function __construct()
    {
        $this->config = json_decode(json_encode(config('legacy')));
        $this->settings = [];

        $this->setSettings($this->config);
    }

    /**
     * Wrapper para Utils::merge.
     *
     * @param array $options
     * @param array $defaultOptions
     *
     * @return array
     *
     *@see Utils::merge()
     *
     */
    protected static function mergeOptions($options, $defaultOptions)
    {
        return Utils::merge($options, $defaultOptions);
    }

    /**
     * Define as configurações dos relatórios.
     *
     * @param object $config
     *
     * @return void
     */
    abstract public function setSettings($config);

    /**
     * Renderiza o relatório.
     *
     * @param ReportCore $report
     * @param array      $options
     *
     * @return mixed
     */
    abstract public function dumps(ReportCore $report, $options = []);
}
