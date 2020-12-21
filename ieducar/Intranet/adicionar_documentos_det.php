<?php

use iEducarLegacy\Intranet\Source\Detalhe;
use iEducarLegacy\Intranet\Source\Pessoa\Documento;

$desvio_diretorio = '';


class clsIndex extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} Documentos");
    }
}

class indice extends Detalhe
{
    public function Gerar()
    {
        $this->titulo = 'Documentos';
        $this->addBanner('imagens/nvp_top_intranet.jpg', 'imagens/nvp_vert_intranet.jpg', 'Intranet', false);

        $this->idpes = $this->pessoa_logada;

        $idpes = '';
        $objDocumento = new Documento($idpes);
        $detalheDocumento = $objDocumento->detalhe();

        list($idpes, $rg, $data_exp_rg, $sigla_uf_exp_rg, $tipo_cert_civil, $num_termo, $num_livro, $num_folha, $data_emissao_cert_civil, $sigla_uf_cert_civil, $cartorio_cert_civil, $num_cart_trabalho, $serie_cart_trabalho, $data_emissao_cart_trabalho, $sigla_uf_cart_trabalho, $num_tit_eleitor, $zona_tit_eleitor, $secao_tit_eleitor, $idorg_exp_rg) = $objDocumento->detalhe();

        $this->addDetalhe(['RG', $detalheDocumento['rg'] ]);
        $this->addDetalhe(['Data Expedição', date('d/m/Y', strtotime(substr($data_exp_rg, 0, 19))) ]);
        $this->addDetalhe(['Órgão Expedição', $sigla_uf_exp_rg ]);
        $this->addDetalhe(['Certificado Civil', $tipo_cert_civil ]);
        $this->addDetalhe(['Termo', $num_termo ]);
        $this->addDetalhe(['Livro', $num_livro ]);
        $this->addDetalhe(['Folha', $num_folha ]);
        $this->addDetalhe(['Emissão Certificado Civil', $data_emissao_cert_civil]);
        $this->addDetalhe(['Sigla Certificado Civil', $sigla_uf_cert_civil ]);
        $this->addDetalhe(['Cartório', $cartorio_cert_civil ]);
        $this->addDetalhe(['Carteira trabalho', $num_cart_trabalho ]);
        $this->addDetalhe(['série Carteira Trabalho', $serie_cart_trabalho ]);
        $this->addDetalhe(['Emissão Carteira Trabalho', $data_emissao_cart_trabalho ]);
        $this->addDetalhe(['Sigla Carteira de Trabalho', $sigla_uf_cart_trabalho ]);
        $this->addDetalhe(['Título Eleitor', $num_tit_eleitor ]);
        $this->addDetalhe(['Zona', $zona_tit_eleitor ]);
        $this->addDetalhe(['Seção', $secao_tit_eleitor ]);
        $this->addDetalhe(['Órgão Expedição', $idorg_exp_rg]);

        $this->url_novo = 'adicionar_documentos_cad.php';
        $this->url_editar = "adicionar_documentos_cad.php?idpes={$idpes}";
        $this->url_cancelar = 'meusdados.php';

        $this->largura = '100%';
    }
}

    function Novo()
    {
        $objDocumento = new Documento($this->rg, $this->data_exp_rg, $this->sigla_uf_exp_rg, $this->tipo_cert_civil, $this->num_termo, $this->num_livro, $this->num_folha, $this->data_emissao_cert_civil, $this->sigla_uf_cert_civil, $this->cartorio_cert_civil, $this->num_cart_trabalho, $this->serie_cart_trabalho, $this->data_emissao_cart_trabalho, $this->sigla_uf_cart_trabalho, $this->num_tit_eleitor, $this->zona_tit_eleitor, $this->secao_tit_eleitor, $this->idorg_exp_rg);
        if ($objDocumento->cadastra()) {
            echo '<script>document.location=\'meusdados.php\';</script>';

            return true;
        }

        return false;
    }

    function Editar()
    {
        $ObjDocumento = new Documento($this->rg, $this->data_exp_rg, $this->sigla_uf_exp_rg, $this->tipo_cert_civil, $this->num_termo, $this->num_livro, $this->num_folha, $this->data_emissao_cert_civil, $this->sigla_uf_cert_civil, $this->cartorio_cert_civil, $this->num_cart_trabalho, $this->serie_cart_trabalho, $this->data_emissao_cart_trabalho, $this->sigla_uf_cart_trabalho, $this->num_tit_eleitor, $this->zona_tit_eleitor, $this->secao_tit_eleitor, $this->idorg_exp_rg);
        if ($ObjDocumento->edita()) {
            echo '<script>document.location=\'meusdados.php\';</script>';

            return true;
        }

        return false;
    }

    function Excluir()
    {
        $ObjDocumento = new Documento($this->idpes);
        $Objcallback->exclui();
        echo '<script>document.location=\'meusdados.php\';</script>';

        return true;
    }

$pagina = new clsIndex();

$miolo = new indice();
$pagina->addForm($miolo);

$pagina->MakeAll();
