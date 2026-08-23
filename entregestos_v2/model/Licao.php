<?php

class Licao
{
    private ?int $id;
    private ?int $secaoId;
    private ?string $titulo;
    private ?int $posicao;
    private ?bool $estaBloqueada;
    private ?array $questoes;
    

    public function __toString()
    {
        return $this->titulo;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getSecaoId(): ?int
    {
        return $this->secaoId;
    }

    public function setSecaoId(?int $secaoId): self
    {
        $this->secaoId = $secaoId;

        return $this;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(?string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getPosicao(): ?int
    {
        return $this->posicao;
    }

    public function setPosicao(?int $posicao): self
    {
        $this->posicao = $posicao;

        return $this;
    }

    public function getEstaBloqueada(): ?bool
    {
        return $this->estaBloqueada;
    }

    public function setEstaBloqueada(?bool $estaBloqueada): self
    {
        $this->estaBloqueada = $estaBloqueada;

        return $this;
    }
}