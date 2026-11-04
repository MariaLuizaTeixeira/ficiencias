<?php

class Secao {
    private ?int $id = null;
    private ?int $cursoId = null;
    private ?string $titulo = null;
    private ?int $ordem = null;
    private ?string $descricao = null;

    public ?string $curso_nome = null;

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }

    public function getCursoId(): ?int { return $this->cursoId; }
    public function setCursoId(?int $cursoId): self { $this->cursoId = $cursoId; return $this; }

    public function getTitulo(): ?string { return $this->titulo; }
    public function setTitulo(?string $titulo): self { $this->titulo = $titulo; return $this; }

    public function getOrdem(): ?int { return $this->ordem; }
    public function setOrdem(?int $ordem): self { $this->ordem = $ordem; return $this; }

    public function getDescricao(): ?string { return $this->descricao; }
    public function setDescricao(?string $descricao): self { $this->descricao = $descricao; return $this; }
}
