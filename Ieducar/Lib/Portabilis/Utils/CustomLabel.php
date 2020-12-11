<?php

require_once 'Source/pmieducar/ConfiguracoesGerais.php';

class CustomLabel
{
    protected static $instance;

    protected $defaults;

    public function __construct()
    {
        $this->defaults = $this->getFromDatabase();
    }

    public function customize($key)
    {
        if (!empty($this->defaults[$key])) {
            return $this->defaults[$key];
        }

        return $key;
    }

    public function getDefaults()
    {
        return $this->defaults;
    }

    protected function getFromDatabase()
    {
        $configs = new ConfiguracoesGerais();
        $detalhe = $configs->detalhe();

        return $detalhe['custom_labels'];
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new CustomLabel();
        }

        return self::$instance;
    }
}

function _cl($key)
{
    return CustomLabel::getInstance()->customize($key);
}
