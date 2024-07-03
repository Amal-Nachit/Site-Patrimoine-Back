<?php

namespace App\Entity;

use App\Repository\AutresImagesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AutresImagesRepository::class)]
class AutresImages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageName = null;

    #[ORM\ManyToOne(inversedBy: 'autresImages')]
    private ?ActualitePatrimoine $actualitePatrimoine = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): static
    {
        $this->imageName = $imageName;

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
