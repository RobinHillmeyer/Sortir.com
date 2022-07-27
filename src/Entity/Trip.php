<?php

namespace App\Entity;

use App\Repository\TripRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TripRepository::class)]
class Trip
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startDateTime = null;

    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\Length(min: 1, minMessage: "La durée doit être supérieur a 0")]
    private ?int $duration = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\LessThanOrEqual(propertyPath: "startDateTime", message: "La date de cloture doit être inférieur a la date de sortie")]
    private ?\DateTimeInterface $registrationDeadLine = null;

    #[ORM\Column]
    #[Assert\Length(min: 1, minMessage: "Il faut au moins 2 participants")]
    private ?int $registrationNumberMax = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $information = null;

    #[ORM\ManyToOne(inversedBy: 'trips', cascade: ["persist"])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Spot $spot = null;

    #[ORM\ManyToOne(inversedBy: 'trips')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Campus $campus = null;

    #[ORM\ManyToOne(inversedBy: 'trips')]
    #[ORM\JoinColumn(nullable: false)]
    private ?State $state = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $cancelMessage = null;

    #[ORM\ManyToOne(inversedBy: 'tripPromoter')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $promoter = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'trips')]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
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

    public function getStartDateTime(): ?\DateTimeInterface
    {
        return $this->startDateTime;
    }

    public function setStartDateTime(\DateTimeInterface $startDateTime): self
    {
        $this->startDateTime = $startDateTime;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getRegistrationDeadLine(): ?\DateTimeInterface
    {
        return $this->registrationDeadLine;
    }

    public function setRegistrationDeadLine(\DateTimeInterface $registrationDeadLine): self
    {
        $this->registrationDeadLine = $registrationDeadLine;

        return $this;
    }

    public function getRegistrationNumberMax(): ?int
    {
        return $this->registrationNumberMax;
    }

    public function setRegistrationNumberMax(int $registrationNumberMax): self
    {
        $this->registrationNumberMax = $registrationNumberMax;

        return $this;
    }

    public function getInformation(): ?string
    {
        return $this->information;
    }

    public function setInformation(?string $information): self
    {
        $this->information = $information;

        return $this;
    }

    public function getSpot(): ?Spot
    {
        return $this->spot;
    }

    public function setSpot(?Spot $spot): self
    {
        $this->spot = $spot;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(?State $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getCancelMessage(): ?string
    {
        return $this->cancelMessage;
    }

    public function setCancelMessage(?string $cancelMessage): self
    {
        $this->cancelMessage = $cancelMessage;

        return $this;
    }

    public function getPromoter(): ?User
    {
        return $this->promoter;
    }

    public function setPromoter(?User $promoter): self
    {
        $this->promoter = $promoter;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addTrip($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeTrip($this);
        }

        return $this;
    }
}
