<?php

namespace App\Entity;

use App\Repository\TeamsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeamsRepository::class)]
class Teams
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 100)]
    private ?string $country = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $moneyBalance = null;

    #[ORM\OneToMany(mappedBy: 'teams', targetEntity: Players::class,  cascade: ['persist'])]
    #[ORM\JoinColumn(onDelete:'CASCADE')]
    protected Collection $players;

    public function __construct()
    {
        $this->players = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getMoneyBalance(): ?string
    {
        return $this->moneyBalance;
    }

    public function setMoneyBalance(string $moneyBalance): static
    {
        $this->moneyBalance = $moneyBalance;

        return $this;
    }

    /**
     * @return Collection<int, Players>
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Players $player): self
    {
        if (!$this->players->contains($player))
        {
            $this->players->add($player);
            $player->setTeams($this);
        }

        return $this;
    }

    public function removePlayer(Players $player): static
    {
        if ($this->players->removeElement($player)) {
            // set the owning side to null (unless already changed)
            if ($player->getTeams() === $this) {
                $player->setTeams(null);
            }
        }

        return $this;
    }

    public function __toString(): string 
    {
        return $this->name ;
    }
}
