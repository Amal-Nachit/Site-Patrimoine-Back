<?php

namespace App\Entity;

use App\Repository\AutresImagesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AutresImagesRepository::class)]
class AutresImages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['api_actualite_patrimoine_index', 'api_actualite_patrimoine_show'])]

    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['api_actualite_patrimoine_index', 'api_actualite_patrimoine_show'])]

    private ?string $imageName = null;

    #[ORM\ManyToOne(inversedBy: 'autresImages')]
    #[Groups(['api_actualite_patrimoine_index', 'api_actualite_patrimoine_show', 'groups' => 'api_autres_images_index'])]
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
