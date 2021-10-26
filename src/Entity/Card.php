<?php

namespace App\Entity;

use App\Repository\CardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=CardRepository::class)
 */
class Card
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", unique=true, nullable=true)
     */
    private $multiverse_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mana_cost;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $text;

    // En Many to Many la table est nullable par defaut
    /**
     * @ORM\ManyToMany(targetEntity=Color::class, inversedBy="cards")
     */
    private $color_list;

    /**
     * @ORM\ManyToMany(targetEntity=Type::class, inversedBy="cards")
     */
    private $type_list;

    public function __construct()
    {
        $this->color_list = new ArrayCollection();
        $this->type_list = new ArrayCollection();
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

    public function getMultiverseId(): ?int
    {
        return $this->multiverse_id;
    }

    public function setMultiverseId(int $multiverse_id): self
    {
        $this->multiverse_id = $multiverse_id;

        return $this;
    }

    public function getManaCost(): ?string
    {
        return $this->mana_cost;
    }

    public function setManaCost(string $mana_cost): self
    {
        $this->mana_cost = $mana_cost;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return Collection|Color[]
     */
    public function getColorList(): Collection
    {
        return $this->color_list;
    }

    public function addColorList(Color $colorList): self
    {
        if (!$this->color_list->contains($colorList)) {
            $this->color_list[] = $colorList;
        }

        return $this;
    }

    public function removeColorList(Color $colorList): self
    {
        $this->color_list->removeElement($colorList);

        return $this;
    }

    /**
     * @return Collection|Type[]
     */
    public function getTypeList(): Collection
    {
        return $this->type_list;
    }

    public function addTypeList(Type $typeList): self
    {
        if (!$this->type_list->contains($typeList)) {
            $this->type_list[] = $typeList;
        }

        return $this;
    }

    public function removeTypeList(Type $typeList): self
    {
        $this->type_list->removeElement($typeList);

        return $this;
    }
}
