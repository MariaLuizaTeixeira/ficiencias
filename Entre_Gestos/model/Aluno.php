<?php

require_once(__DIR__ . "/Curso.php");

class Aluno
{

    private int $id;
    private int $id_usuario;
    private int $vidas;
    private int $sequencia;
    private ?string $apelido = null;
    private ?string $numeroTelefone = null;
    private ?string $dataNascimento = null; // formato AAAA-MM-DD
    private ?string $genero = null;
    private bool $vidasBloqueadas = false; // true = vidas acabaram; só volta com as vidas completas
    private ?int $recargaInicio = null; // quando a recarga começou (segundos, horário Unix)


    public function getApelido(): ?string
    {
        return $this->apelido;
    }

    public function setApelido(?string $apelido): void
    {
        $this->apelido = $apelido;
    }

    public function getNumeroTelefone(): ?string
    {
        return $this->numeroTelefone;
    }

    public function setNumeroTelefone(?string $numeroTelefone): void
    {
        $this->numeroTelefone = $numeroTelefone;
    }

    public function getDataNascimento(): ?string
    {
        return $this->dataNascimento;
    }

    public function setDataNascimento(?string $dataNascimento): void
    {
        $this->dataNascimento = $dataNascimento;
    }

    public function getGenero(): ?string
    {
        return $this->genero;
    }

    public function setGenero(?string $genero): void
    {
        $this->genero = $genero;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getIdUsuario(): int
    {
        return $this->id_usuario;
    }

    public function setIdUsuario(int $id_usuario): void
    {
        $this->id_usuario = $id_usuario;
    }

    public function getVidas(): int
    {
        return $this->vidas;
    }

    public function setVidas(int $vidas): void
    {
        $this->vidas = $vidas;
    }

    public function getSequencia(): int
    {
        return $this->sequencia;
    }

    public function setSequencia(int $sequencia): void
    {
        $this->sequencia = $sequencia;
    }

   
      public function getRecargaInicio(): ?int { 
    return $this->recargaInicio;
     }
      public function setRecargaInicio(?int $recargaInicio): void { 
    $this->recargaInicio = $recargaInicio; 
    }

    public function getVidasBloqueadas(): bool { return $this->vidasBloqueadas; }
    public function setVidasBloqueadas(bool $v): void { $this->vidasBloqueadas = $v; }
}
