<?php

class OpcaoQuestao
{

    private ?int $id = null;
    private ?int $questaoId = null;
    private ?string $conteudo = null;
    private ?string $tipoConteudo = null;
    private ?bool $estaCorreta = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getQuestaoId(): ?int
    {
        return $this->questaoId;
    }
    public function setQuestaoId(?int $questaoId): self
    {
        $this->questaoId = $questaoId;
        return $this;
    }

    public function getConteudo(): ?string
    {
        return $this->conteudo;
    }
    public function setConteudo(?string $conteudo): self
    {
        $this->conteudo = $conteudo;
        return $this;
    }

    public function getTipoConteudo(): ?string
    {
        return $this->tipoConteudo;
    }
    public function setTipoConteudo(?string $tipoConteudo): self
    {
        $this->tipoConteudo = $tipoConteudo;
        return $this;
    }

    public function getEstaCorreta(): ?bool
    {
        return $this->estaCorreta;
    }
    public function setEstaCorreta(?bool $estaCorreta): self
    {
        $this->estaCorreta = $estaCorreta;
        return $this;
    }
}
