<?php

namespace App\Entity;

use App\Repository\InvitationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=InvitationRepository::class)
 */
class Invitation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $sender_id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $invited_id;

    /**
     * @ORM\Column(type="enum", options={"enum": "pending,canceled,accept,decline"})
     */
    private $status;

    /**
     * @ORM\Column(type="text")
     */
    private $description;
    
    /**
     * @ORM\Column(type="date")
     */
    private $date;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSender(): ?int
    {
        return $this->sender_id;
    }

    public function setSender(int $sender_id): self
    {
        $this->sender_id = $sender_id;

        return $this;
    }

    public function getInvited(): ?int
    {
        return $this->invited_id;
    }

    public function setInvited(int $invited_id): self
    {
        $this->invited_id = $invited_id;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
