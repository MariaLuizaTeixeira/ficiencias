<?php

class Secao
{
    private ?int $id;
    private ?int $cursoId;
    private ?string $titulo;
    private ?int $posicao;
    private ?string $descricao;
    private ?array $licoes;

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

    public function getCursoId(): ?int
    {
        return $this->cursoId;
    }

    public function setCursoId(?int $cursoId): self
    {
        $this->cursoId = $cursoId;

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

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(?string $descricao): self
    {
        $this->descricao = $descricao;

        return $this;
    }
}