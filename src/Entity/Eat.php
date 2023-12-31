<?php

namespace App\Entity;

use App\Repository\EatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;




/**
 * @ORM\Entity(repositoryClass=EatRepository::class)
 */
class Eat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"eat_browse", "eat_read"})
     * @Groups({"category_browse","category_read"})
     * @Groups({"menu_browse", "menu_read"})
     * @Groups({"image_browse", "image_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"eat_browse", "eat_read"})
     * @Groups({"category_browse","category_read"})
     * @Groups({"menu_browse", "menu_read"})
     * 
     * @Assert\NotBlank( message = "Le nom du plat ne peut pas être vide")
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"eat_browse", "eat_read"})
     * @Groups({"category_browse", "category_read"})
     */
    private $description;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     * @Groups({"eat_browse", "eat_read"})
     * @Groups({"category_browse", "category_read"})
     * @Assert\NotBlank( message = "Le prix du plat ne peut pas être vide")
     * @Assert\GreaterThan ( value=0, message = "Le prix doit être forcément positif")
     */
    private $price;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"eat_browse", "eat_read"})
     * 
     */
    private $vegetarian;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"eat_browse", "eat_read"})
     * 
     */
    private $glutenFree;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="eats")
     * @Groups({"eat_browse", "eat_read"})
     * @Groups({"menu_browse", "menu_read"})
     * @Assert\NotBlank( message = "Vous devez renseigner au moins une catégorie")
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=Menu::class, inversedBy="eats")
     */
    private $menu;

    /**
     * @ORM\OneToOne(targetEntity=Image::class)
     * @Groups({"eat_browse", "eat_read"})
     * @Groups({"image_browse", "image_read"})
     * @Groups({"category_browse", "category_read"})
     */
    private $image;

    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->menu = new ArrayCollection();
        $this->setCreatedAt(new \DateTime());
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

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function isVegetarian(): ?bool
    {
        return $this->vegetarian;
    }

    public function setVegetarian(bool $vegetarian): self
    {
        $this->vegetarian = $vegetarian;

        return $this;
    }

    public function isGlutenFree(): ?bool
    {
        return $this->glutenFree;
    }

    public function setGlutenFree(bool $glutenFree): self
    {
        $this->glutenFree = $glutenFree;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = new \DateTime('now');
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->category->removeElement($category);

        return $this;
    }

    /**
     * @return Collection<int, Menu>
     */
    public function getMenu(): Collection
    {
        return $this->menu;
    }

    public function addMenu(Menu $menu): self
    {
        if (!$this->menu->contains($menu)) {
            $this->menu[] = $menu;
        }

        return $this;
    }

    public function removeMenu(Menu $menu): self
    {
        $this->menu->removeElement($menu);

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): self
    {
        $this->image = $image;

        return $this;
    }

}
