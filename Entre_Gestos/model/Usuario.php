<?php

class Usuario
{

    private ?int $id = null;
    private ?string $nomeCompleto;
    private ?string $email;
    private ?string $senhaHash;
    private ?string $criadoEm;
    private ?string $atualizadoEm;
    private ?bool $emailVerificado;
    private ?string $perfil; // 'aluno' ou 'administrador'

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getNomeCompleto(): ?string
    {
        return $this->nomeCompleto;
    }

    public function setNomeCompleto(?string $nomeCompleto): self
    {
        $this->nomeCompleto = $nomeCompleto;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getSenhaHash(): ?string
    {
        return $this->senhaHash;
    }

    public function setSenhaHash(?string $senhaHash): self
    {
        $this->senhaHash = $senhaHash;
        return $this;
    }

    public function getCriadoEm(): ?string
    {
        return $this->criadoEm;
    }

    public function setCriadoEm(?string $criadoEm): self
    {
        $this->criadoEm = $criadoEm;
        return $this;
    }

    public function getAtualizadoEm(): ?string
    {
        return $this->atualizadoEm;
    }

    public function setAtualizadoEm(?string $atualizadoEm): self
    {
        $this->atualizadoEm = $atualizadoEm;
        return $this;
    }

    public function getEmailVerificado(): ?bool
    {
        return $this->emailVerificado;
    }

    public function setEmailVerificado(?bool $emailVerificado): self
    {
        $this->emailVerificado = $emailVerificado;
        return $this;
    }

    public function getPerfil(): ?string
    {
        return $this->perfil;
    }

    public function setPerfil(?string $perfil): self
    {
        $this->perfil = $perfil;
        return $this;
    }
}
