<?php

namespace App\Entity;
use DateTime;
use App\Repository\TournamentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TournamentRepository::class)
 */
class Tournament
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="tournaments")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $start_tournament;

    /**
     * @ORM\Column(type="datetime")
     */
    private $end_tournament;

    /**
     * @ORM\Column(type="integer")
     */
    private $numbers_participants;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type_tournament;

    /**
     * @ORM\Column(type="boolean")
     */
    private $group_stage;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity=Rules::class, mappedBy="tournament")
     */
    private $rules;

    /**
     * @ORM\OneToMany(targetEntity=Game::class, mappedBy="tournament")
     */
    private $games;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->rules = new ArrayCollection();
        $this->games = new ArrayCollection();
        $this->created_at = new DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->user->contains($user)) {
            $this->user->removeElement($user);
        }

        return $this;
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

    public function getStartTournament(): ?\DateTimeInterface
    {
        return $this->start_tournament;
    }

    public function setStartTournament(\DateTimeInterface $start_tournament): self
    {
        $this->start_tournament = $start_tournament;

        return $this;
    }

    public function getEndTournament(): ?\DateTimeInterface
    {
        return $this->end_tournament;
    }

    public function setEndTournament(\DateTimeInterface $end_tournament): self
    {
        $this->end_tournament = $end_tournament;

        return $this;
    }

    public function getNumbersParticipants(): ?int
    {
        return $this->numbers_participants;
    }

    public function setNumbersParticipants(int $numbers_participants): self
    {
        $this->numbers_participants = $numbers_participants;

        return $this;
    }

    public function getTypeTournament(): ?string
    {
        return $this->type_tournament;
    }

    public function setTypeTournament(string $type_tournament): self
    {
        $this->type_tournament = $type_tournament;

        return $this;
    }

    public function getGroupStage(): ?bool
    {
        return $this->group_stage;
    }

    public function setGroupStage(bool $group_stage): self
    {
        $this->group_stage = $group_stage;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection|Rules[]
     */
    public function getRules(): Collection
    {
        return $this->rules;
    }

    public function addRules(Rules $rules): self
    {
        if (!$this->rules->contains($rules)) {
            $this->rules[] = $rules;
            $rules->setTournament($this);
        }

        return $this;
    }

    public function removeRules(Rules $rules): self
    {
        if ($this->rules->contains($rules)) {
            $this->rules->removeElement($rules);
            // set the owning side to null (unless already changed)
            if ($rules->getTournament() === $this) {
                $rules->setTournament(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games[] = $game;
            $game->setTournament($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->contains($game)) {
            $this->games->removeElement($game);
            // set the owning side to null (unless already changed)
            if ($game->getTournament() === $this) {
                $game->setTournament(null);
            }
        }

        return $this;
    }
}
