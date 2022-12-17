<?php

namespace App\Entity;

use App\Repository\ProprietairesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProprietairesRepository::class)]
class Proprietaires
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $Nom = null;

    #[ORM\ManyToMany(targetEntity: Chaton::class, inversedBy: 'proprietaires')]
    private Collection $chat_id;

    public function __construct()
    {
        $this->chat_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    /**
     * @return Collection<int, Chaton>
     */
    public function getChatId(): Collection
    {
        return $this->chat_id;
    }

    public function addChatId(Chaton $chatId): self
    {
        if (!$this->chat_id->contains($chatId)) {
            $this->chat_id->add($chatId);
        }

        return $this;
    }

    public function removeChatId(Chaton $chatId): self
    {
        $this->chat_id->removeElement($chatId);

        return $this;
    }
}
