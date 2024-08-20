<?php

namespace App\Entity;

use App\Repository\SitterAvailabilityRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SitterAvailabilityRepository::class)]
class SitterAvailability
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /*
    #[ORM\Column(length: 255)]
    private ?string $day_of_week = null;
    */
    public const JOURS = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

    #[ORM\Column(type: "string", length: 9, columnDefinition: "ENUM('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche')")]
    #[Assert\Choice(choices: SitterAvailability::JOURS, message: 'Choisissez un jour valide.')]
    private $day_of_week;

    #[ORM\Column]
    private ?bool $morning = null;

    #[ORM\Column]
    private ?bool $afternoon = null;

    #[ORM\Column]
    private ?bool $late_afternoon = null;

    #[ORM\Column]
    private ?bool $evening = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\ManyToOne(inversedBy: 'sitterAvailabilities')]
    private ?Sitter $sitter = null;

    public function __construct() {
        $this->updated_at = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDayOfWeek(): ?string
    {
        return $this->day_of_week;
    }

    public function setDayOfWeek(string $day_of_week): static
    {
        $this->day_of_week = $day_of_week;

        return $this;
    }

    public function isMorning(): ?bool
    {
        return $this->morning;
    }

    public function setMorning(bool $morning): static
    {
        $this->morning = $morning;

        return $this;
    }

    public function isAfternoon(): ?bool
    {
        return $this->afternoon;
    }

    public function setAfternoon(bool $afternoon): static
    {
        $this->afternoon = $afternoon;

        return $this;
    }

    public function isLateAfternoon(): ?bool
    {
        return $this->late_afternoon;
    }

    public function setLateAfternoon(bool $late_afternoon): static
    {
        $this->late_afternoon = $late_afternoon;

        return $this;
    }

    public function isEvening(): ?bool
    {
        return $this->evening;
    }

    public function setEvening(bool $evening): static
    {
        $this->evening = $evening;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getSitter(): ?Sitter
    {
        return $this->sitter;
    }

    public function setSitter(?Sitter $sitter): static
    {
        $this->sitter = $sitter;

        return $this;
    }
}
