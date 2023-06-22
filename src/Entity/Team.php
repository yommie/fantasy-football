<?php

namespace App\Entity;

use DateTimeImmutable;
use App\Util\ImageHandler;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TeamRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;

#[Uploadable]
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: TeamRepository::class)]
class Team
{
    public const STARTING_BUDGET = 50000000;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 25)]
    #[ORM\Column(length: 20)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $logo = null;

    #[Assert\Image(minWidth: 1, minHeight: 1)]
    #[UploadableField(mapping: "team_logo", fileNameProperty: "logo")]
    private ?File $logoFile = null;

    #[ORM\Column]
    private ?int $budget = null;

    #[ORM\OneToOne(inversedBy: 'team', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[Assert\Count(
        min: 3,
        max: 25,
        minMessage: "A team must have at least {{ limit }} players",
        maxMessage: "A team must have not more than {{ limit }} players"
    )]
    #[ORM\OneToMany(mappedBy: 'team', targetEntity: Player::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $players;

    #[ORM\OneToMany(mappedBy: 'team', targetEntity: Bid::class, orphanRemoval: true)]
    private Collection $bids;

    public function __construct()
    {
        $this->players = new ArrayCollection();
        $this->bids = new ArrayCollection();
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

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    public function getLogoFile(): ?File
    {
        return $this->logoFile;
    }

    public function setLogoFile(?File $logoFile = null): void
    {
        $this->logoFile = $logoFile;
    }

    public function getBudget(): ?int
    {
        return $this->budget;
    }

    public function setBudget(int $budget): static
    {
        $this->budget = $budget;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getJsonResponse(): array
    {
        return [
            "id"        => $this->getId(),
            "name"      => $this->getName(),
            "logo"      => ImageHandler::resolvePathToResizedImage('/images/' . $this->getLogo(), 60, 60),
            "owner"     => [
                "id"        => $this->getOwner()->getId(),
                "email"     => $this->getOwner()->getEmail(),
                "lastName"  => $this->getOwner()->getLastName(),
                "firstName" => $this->getOwner()->getFirstName()
            ],
            "budget"    => number_format($this->getBudget(), 2),
            "players"   => array_map(function (Player $player) {
                return $player->getJsonResponse();
            }, $this->getPlayers()->toArray()),
            "createdAt" => $this->getCreatedAt()->format("jS F, Y"),
            "updatedAt" => $this->getUpdatedAt()?->format("jS F, Y")
        ];
    }

    /**
     * @return Collection<int, Player>
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): static
    {
        if (!$this->players->contains($player)) {
            $this->players->add($player);
            $player->setTeam($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): static
    {
        if ($this->players->removeElement($player)) {
            // set the owning side to null (unless already changed)
            if ($player->getTeam() === $this) {
                $player->setTeam(null);
            }
        }

        return $this;
    }

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->setCreatedAt(new DateTimeImmutable());
        $this->setBudget(self::STARTING_BUDGET);
    }

    /**
     * @return Collection<int, Bid>
     */
    public function getBids(): Collection
    {
        return $this->bids;
    }

    public function addBid(Bid $bid): static
    {
        if (!$this->bids->contains($bid)) {
            $this->bids->add($bid);
            $bid->setTeam($this);
        }

        return $this;
    }

    public function removeBid(Bid $bid): static
    {
        if ($this->bids->removeElement($bid)) {
            // set the owning side to null (unless already changed)
            if ($bid->getTeam() === $this) {
                $bid->setTeam(null);
            }
        }

        return $this;
    }
}
