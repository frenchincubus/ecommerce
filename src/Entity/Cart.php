<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CartRepository::class)
 */
class Cart
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="carts")
     * @ORM\JoinColumn(nullable=true)
     */
    private $customer_id;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $total_price;

    /**
     * @ORM\OneToMany(targetEntity=CartProducts::class, mappedBy="Cart_id", orphanRemoval=true)
     */
    private $cartProducts;

    public function __construct()
    {
        $this->cartProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomerId(): ?Customer
    {
        return $this->customer_id;
    }

    public function setCustomerId(?Customer $customer_id): self
    {
        $this->customer_id = $customer_id;

        return $this;
    }

    public function getTotalPrice(): ?string
    {
        return $this->total_price;
    }

    public function setTotalPrice(): self
    {
        // $this->total_price = $total_price;
        $total = 0;
        foreach ($this->getCartProducts() as $cartProduct) {
            $total += (float)($cartProduct->getProduct()->getTtcPrice()) * $cartProduct->getQuantity();
        }
        $this->total_price = $total;
        
        return $this;
    }

    /**
     * @return Collection|CartProducts[]
     */
    public function getCartProducts(): Collection
    {
        return $this->cartProducts;
    }

    public function addCartProduct(CartProducts $cartProduct): self
    {
        if (!$this->cartProducts->contains($cartProduct)) {
            $this->cartProducts[] = $cartProduct;
            $cartProduct->setCartId($this);
        }

        return $this;
    }

    public function removeCartProduct(CartProducts $cartProduct): self
    {
        if ($this->cartProducts->contains($cartProduct)) {
            $this->cartProducts->removeElement($cartProduct);
            // set the owning side to null (unless already changed)
            if ($cartProduct->getCartId() === $this) {
                $cartProduct->setCartId(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getId(). ' - '.$this->getCartProducts()[0].getDate().' - '.$this->getTotalPrice();
    }
}
