<?php

class Alfabeto
{
    private ?int $id = null;
    private ?string $letra = null;
    private ?string $videoId = null;
    private ?int $ordem = null;
    private ?string $canalNome = null;
    private ?string $canalUrl = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getLetra(): ?string
    {
        return $this->letra;
    }

    public function setLetra(?string $letra): self
    {
        $this->letra = $letra;
        return $this;
    }

    public function getVideoId(): ?string
    {
        return $this->videoId;
    }

    public function setVideoId(?string $videoId): self
    {
        $this->videoId = $videoId;
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

    public function getCanalNome(): ?string
    {
        return $this->canalNome;
    }

    public function setCanalNome(?string $canalNome): self
    {
        $this->canalNome = $canalNome;
        return $this;
    }

    public function getCanalUrl(): ?string
    {
        return $this->canalUrl;
    }

    public function setCanalUrl(?string $canalUrl): self
    {
        $this->canalUrl = $canalUrl;
        return $this;
    }
}
