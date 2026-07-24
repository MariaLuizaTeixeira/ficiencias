<?php

namespace App\controller\questions;

use App\Dao\questions\OptionDAO;
use App\mapper\OptionMapper;

class OptionController {
    private OptionDAO $optionDAO;
    private OptionMapper  $optionMapper;

    public function __construct() {
        $this->optionDAO = new OptionDAO();
        $this->optionMapper = new OptionMapper();
    }

    public function criar(array $opcoes, int $questionId): void {
        foreach ($opcoes as $opcao) {

            $opcao = $this->optionMapper->arrayToOption($opcao, $questionId);
            $this->optionDAO->criar($opcao);
        }
    }
}