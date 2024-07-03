<?php

namespace App\Entity;

use App\Repository\AutresLiensRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AutresLiensRepository::class)]
class AutresLiens
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lienUrl = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $texteLien = null;

    #[ORM\ManyToOne(inversedBy: 'autresLiens')]
    private ?ActualitePatrimoine $actualitePatrimoine = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLienUrl(): ?string
    {
        return $this->lienUrl;
    }

    public function setLienUrl(?string $lienUrl): static
    {
        $this->lienUrl = $lienUrl;

        return $this;
    }

    public function getTexteLien(): ?string
    {
        return $this->texteLien;
    }

    public function setTexteLien(?string $texteLien): static
    {
        $this->texteLien = $texteLien;

        return $this;
    }

    public function getActualitePatrimoine(): ?ActualitePatrimoine
    {
        return $this->actualitePatrimoine;
    }

    public function setActualitePatrimoine(?ActualitePatrimoine $actualitePatrimoine): static
    {
        $this->actualitePatrimoine = $actualitePatrimoine;

        return $this;
    }
}
