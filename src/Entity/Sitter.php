<?php

namespace App\Entity;

use App\Repository\SitterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SitterRepository::class)]
class Sitter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $borned_at = null;

    #[ORM\Column]
    private ?int $experience_years = null;

    #[ORM\Column(length: 20)]
    private ?string $civility = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $bio = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $availability = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $languages = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $certifications = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo_url = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contact_info = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    /**
     * @var Collection<int, Work>
     */
    #[ORM\OneToMany(targetEntity: Work::class, mappedBy: 'sitter', orphanRemoval: true)]
    private Collection $works;

    #[ORM\OneToOne(inversedBy: 'sitter', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Account $account = null;

    #[ORM\ManyToOne(inversedBy: 'sitters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?City $city = null;

    /**
     * @var Collection<int, SitterAvailability>
     */
    #[ORM\OneToMany(targetEntity: SitterAvailability::class, mappedBy: 'sitter')]
    private Collection $sitterAvailabilities;

    public function __construct()
    {
        $this->works = new ArrayCollection();
        $this->sitterAvailabilities = new ArrayCollection();
        /*
        $this->created_at = new \DateTime('NOW');
        $this->updated_at = new \DateTime('NOW');
        */
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBornedAt(): ?\DateTimeInterface
    {
        return $this->borned_at;
    }

    public function setBornedAt(\DateTimeInterface $borned_at): static
    {
        $this->borned_at = $borned_at;

        return $this;
    }

    public function getExperienceYears(): ?int
    {
        return $this->experience_years;
    }

    public function setExperienceYears(int $experience_years): static
    {
        $this->experience_years = $experience_years;

        return $this;
    }

    public function getCivility(): ?string
    {
        return $this->civility;
    }

    public function setCivility(string $civility): static
    {
        $this->civility = $civility;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): static
    {
        $this->bio = $bio;

        return $this;
    }

    public function getAvailability(): ?string
    {
        return $this->availability;
    }

    public function setAvailability(?string $availability): static
    {
        $this->availability = $availability;

        return $this;
    }

    public function getLanguages(): ?string
    {
        return $this->languages;
    }

    public function setLanguages(?string $languages): static
    {
        $this->languages = $languages;

        return $this;
    }

    public function getCertifications(): ?string
    {
        return $this->certifications;
    }

    public function setCertifications(string $certifications): static
    {
        $this->certifications = $certifications;

        return $this;
    }

    public function getPhotoUrl(): ?string
    {
        return $this->photo_url;
    }

    public function setPhotoUrl(?string $photo_url): static
    {
        $this->photo_url = $photo_url;

        return $this;
    }

    public function getContactInfo(): ?string
    {
        return $this->contact_info;
    }

    public function setContactInfo(?string $contact_info): static
    {
        $this->contact_info = $contact_info;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

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

    /**
     * @return Collection<int, Work>
     */
    public function getWorks(): Collection
    {
        return $this->works;
    }

    public function addWork(Work $work): static
    {
        if (!$this->works->contains($work)) {
            $this->works->add($work);
            $work->setSitter($this);
        }

        return $this;
    }

    public function removeWork(Work $work): static
    {
        if ($this->works->removeElement($work)) {
            // set the owning side to null (unless already changed)
            if ($work->getSitter() === $this) {
                $work->setSitter(null);
            }
        }

        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(Account $account): static
    {
        $this->account = $account;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): static
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection<int, SitterAvailability>
     */
    public function getSitterAvailabilities(): Collection
    {
        return $this->sitterAvailabilities;
    }

    public function addSitterAvailability(SitterAvailability $sitterAvailability): static
    {
        if (!$this->sitterAvailabilities->contains($sitterAvailability)) {
            $this->sitterAvailabilities->add($sitterAvailability);
            $sitterAvailability->setSitter($this);
        }

        return $this;
    }

    public function removeSitterAvailability(SitterAvailability $sitterAvailability): static
    {
        if ($this->sitterAvailabilities->removeElement($sitterAvailability)) {
            // set the owning side to null (unless already changed)
            if ($sitterAvailability->getSitter() === $this) {
                $sitterAvailability->setSitter(null);
            }
        }

        return $this;
    }
}
