<?php


namespace App\Entity;

use App\Repository\ActualitePatrimoineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ActualitePatrimoineRepository::class)]
class ActualitePatrimoine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['api_actualite_patrimoine_index', 'api_actualite_patrimoine_show'])]

    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['api_actualite_patrimoine_index', 'api_actualite_patrimoine_show'])]

    private ?string $titreActualite = null;


    #[ORM\Column(length: 255)]
    #[Groups(['api_actualite_patrimoine_index', 'api_actualite_patrimoine_show'])]

    private ?string $imageActualite = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['api_actualite_patrimoine_index', 'api_actualite_patrimoine_show'])]

    private ?string $contenuActualite = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['api_actualite_patrimoine_index', 'api_actualite_patrimoine_show'])]

    private ?\DateTimeInterface $datePublication = null;

    /**
     * @var Collection<int, AutresLiens>
     */
    #[ORM\OneToMany(targetEntity: AutresLiens::class, mappedBy: 'actualitePatrimoine', cascade: ['persist'])]
        private Collection $autresLiens;
    /**
     * @var Collection<int, AutresImages>
     */
    #[ORM\OneToMany(targetEntity: AutresImages::class, mappedBy: 'actualitePatrimoine', cascade: ['persist'])]
    private Collection $autresImages;

    #[ORM\ManyToOne(inversedBy: 'actualitePatrimoine')]
    private ?User $user = null;


    public function __construct()
    {
        $this->autresLiens = new ArrayCollection();
        $this->autresImages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreActualite(): ?string
    {
        return $this->titreActualite;
    }

    public function setTitreActualite(string $titreActualite): static
    {
        $this->titreActualite = $titreActualite;

        return $this;
    }

    public function getImageActualite(): ?string
    {
        return $this->imageActualite;
    }

    public function setImageActualite(?string $imageActualite): static
    {
        $this->imageActualite = $imageActualite;

        return $this;
    }

    public function getContenuActualite(): ?string
    {
        return $this->contenuActualite;
    }

    public function setContenuActualite(string $contenuActualite): static
    {
        $this->contenuActualite = $contenuActualite;

        return $this;
    }

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->datePublication;
    }

    public function setDatePublication(\DateTimeInterface $datePublication): static
    {
        $this->datePublication = $datePublication;

        return $this;
    }

    /**
     * @return Collection<int, AutresLiens>
     */
    public function getAutresLiens(): Collection
    {
        return $this->autresLiens;
    }

    public function addAutresLien(AutresLiens $autresLien): static
    {
        if (!$this->autresLiens->contains($autresLien)) {
            $this->autresLiens->add($autresLien);
            $autresLien->setActualitePatrimoine($this);
        }

        return $this;
    }

    public function removeAutresLien(AutresLiens $autresLien): static
    {
        if ($this->autresLiens->removeElement($autresLien)) {
            // set the owning side to null (unless already changed)
            if ($autresLien->getActualitePatrimoine() === $this) {
                $autresLien->setActualitePatrimoine(null);
            }
        }

        return $this;
    }

     /**
     * @return Collection<int, AutresImages>
     */
    public function getAutresImages(): Collection
    {
        return $this->autresImages;
    }

    public function addAutresImage(AutresImages $autresImage): static
    {
        if (!$this->autresImages->contains($autresImage)) {
            $this->autresImages->add($autresImage);
            $autresImage->setActualitePatrimoine($this);
        }

        return $this;
    }

    public function removeAutresImage(AutresImages $autresImage): static
    {
        if ($this->autresImages->removeElement($autresImage)) {
            // set the owning side to null (unless already changed)
            if ($autresImage->getActualitePatrimoine() === $this) {
                $autresImage->setActualitePatrimoine(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
