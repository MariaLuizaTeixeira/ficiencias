<?php

class Licao
{
    private ?int $id = null;
    private ?int $secaoId = null;
    private ?string $titulo = null;
    private ?int $ordem = null;
    private ?bool $estaBloqueada = true;

    public ?string $secao_titulo = null;
    public ?string $curso_nome = null;
    public ?string $status_calculado = null;

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

    public function getOrdem(): ?int
    {
        return $this->ordem;
    }
    public function setOrdem(?int $ordem): self
    {
        $this->ordem = $ordem;
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
