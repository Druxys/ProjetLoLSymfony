<?php

namespace App\Entity;

use App\Repository\UsersTeamsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UsersTeamsRepository::class)
 */
class UsersTeams
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $invitation;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="usersTeams", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class, inversedBy="usersTeams", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $team;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInvitation(): ?bool
    {
        return $this->invitation;
    }

    public function setInvitation(bool $invitation): self
    {
        $this->invitation = $invitation;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(Team $team): self
    {
        $this->team = $team;

        return $this;
    }
}
