<?php

class Questao
{
    private ?int $id = null;
    private ?string $titulo = null;
    private ?string $enunciado = null;
    private ?string $tipo = null;
    private ?int $licaoId = null;

    // Propriedades extras populadas por JOIN (carregadas pelo DAO)
    public ?string $detalheConteudo = null;
    public ?string $detalheTipo = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?int $id): self
    {
        $this->id = $id;
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

    public function getEnunciado(): ?string
    {
        return $this->enunciado;
    }
    public function setEnunciado(?string $enunciado): self
    {
        $this->enunciado = $enunciado;
        return $this;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }
    public function setTipo(?string $tipo): self
    {
        $this->tipo = $tipo;
        return $this;
    }

    public function getLicaoId(): ?int
    {
        return $this->licaoId;
    }
    public function setLicaoId(?int $licaoId): self
    {
        $this->licaoId = $licaoId;
        return $this;
    }
}
