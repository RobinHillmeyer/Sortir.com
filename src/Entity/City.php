<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CityRepository::class)]
class City
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 5)]
    private ?string $zipCode = null;

    #[ORM\OneToMany(mappedBy: 'city', targetEntity: Spot::class)]
    private Collection $spots;

    public function __construct()
    {
        $this->spots = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * @return Collection<int, Spot>
     */
    public function getSpots(): Collection
    {
        return $this->spots;
    }

    public function addSpot(Spot $spot): self
    {
        if (!$this->spots->contains($spot)) {
            $this->spots[] = $spot;
            $spot->setCity($this);
        }

        return $this;
    }

    public function removeSpot(Spot $spot): self
    {
        if ($this->spots->removeElement($spot)) {
            // set the owning side to null (unless already changed)
            if ($spot->getCity() === $this) {
                $spot->setCity(null);
            }
        }

        return $this;
    }
}
