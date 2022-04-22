<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    private $libelle;

    #[ORM\Column(type: 'float')]
    private $price;

    #[ORM\Column(type: 'integer')]
    private $stock;

    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

    #[ORM\ManyToMany(targetEntity: Categorie::class, inversedBy: 'products')]
    private $productCateg;

    #[ORM\ManyToOne(targetEntity: Seller::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private $productSel;

    #[ORM\ManyToMany(targetEntity: Order::class, mappedBy: 'products')]
    private $orders;

    public function __construct()
    {
        $this->productCateg = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

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

    /**
     * @return Collection<int, Categorie>
     */
    public function getProductCateg(): Collection
    {
        return $this->productCateg;
    }

    public function addProductCateg(Categorie $productCateg): self
    {
        if (!$this->productCateg->contains($productCateg)) {
            $this->productCateg[] = $productCateg;
        }

        return $this;
    }

    public function removeProductCateg(Categorie $productCateg): self
    {
        $this->productCateg->removeElement($productCateg);

        return $this;
    }

    public function getProductSel(): ?Seller
    {
        return $this->productSel;
    }

    public function setProductSel(?Seller $productSel): self
    {
        $this->productSel = $productSel;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->addProduct($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            $order->removeProduct($this);
        }

        return $this;
    }
}
