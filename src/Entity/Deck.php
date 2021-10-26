<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DeckRepository::class)
 */
class Deck
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez remplir le nom du deck")
     * @Assert\Length(
     *     min = 2,
     *     max = 255,
     *     minMessage = "Votre nom doit contenir au moins 2 caracteres",
     *     maxMessage = "Votre nom ne peut pas dépasser 255 caracteres"
     *     )
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Veuillez remplir la description du deck")
     * @Assert\Length(
     *     min = 32,
     *     max = 3000,
     *     minMessage = "Votre description est trop courte (32 caracteres).",
     *     maxMessage = "Votre description est trop longue (3000 caracteres)."
     *     )
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Veuillez renseigner une range.")
     * @Assert\Range(
     *     min = 40,
     *     max = 120,
     *     notInRangeMessage = "Vous devez être entre 40 et 120"
     * )
     */
    private $max_size;

    /**
     * @ORM\ManyToMany(targetEntity=Card::class)
     */
    private $card_list;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="deck_list")
     */
    private $user;

    public function __construct()
    {
        $this->card_list = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getMaxSize(): ?int
    {
        return $this->max_size;
    }

    public function setMaxSize(int $max_size): self
    {
        $this->max_size = $max_size;

        return $this;
    }

    /**
     * @return Collection|Card[]
     */
    public function getCardList(): Collection
    {
        return $this->card_list;
    }

    public function addCardList(Card $cardList): self
    {
        if (!$this->card_list->contains($cardList)) {
            $this->card_list[] = $cardList;
        }

        return $this;
    }

    public function removeCardList(Card $cardList): self
    {
        $this->card_list->removeElement($cardList);

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }
}
