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
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $zip_code = null;

    #[ORM\Column(length: 150)]
    private ?string $name = null;

    /**
     * @var Collection<int, Sitter>
     */
    #[ORM\OneToMany(targetEntity: Sitter::class, mappedBy: 'city', orphanRemoval: true)]
    private Collection $sitters;

    /**
     * @var Collection<int, UserParent>
     */
    #[ORM\OneToMany(targetEntity: UserParent::class, mappedBy: 'city', orphanRemoval: true)]
    private Collection $userParents;

    public function __construct()
    {
        $this->sitters = new ArrayCollection();
        $this->userParents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getZipCode(): ?int
    {
        return $this->zip_code;
    }

    public function setZipCode(int $zip_code): static
    {
        $this->zip_code = $zip_code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Sitter>
     */
    public function getSitters(): Collection
    {
        return $this->sitters;
    }

    public function addSitter(Sitter $sitter): static
    {
        if (!$this->sitters->contains($sitter)) {
            $this->sitters->add($sitter);
            $sitter->setCity($this);
        }

        return $this;
    }

    public function removeSitter(Sitter $sitter): static
    {
        if ($this->sitters->removeElement($sitter)) {
            // set the owning side to null (unless already changed)
            if ($sitter->getCity() === $this) {
                $sitter->setCity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserParent>
     */
    public function getUserParents(): Collection
    {
        return $this->userParents;
    }

    public function addUserParent(UserParent $userParent): static
    {
        if (!$this->userParents->contains($userParent)) {
            $this->userParents->add($userParent);
            $userParent->setCity($this);
        }

        return $this;
    }

    public function removeUserParent(UserParent $userParent): static
    {
        if ($this->userParents->removeElement($userParent)) {
            // set the owning side to null (unless already changed)
            if ($userParent->getCity() === $this) {
                $userParent->setCity(null);
            }
        }

        return $this;
    }
}
