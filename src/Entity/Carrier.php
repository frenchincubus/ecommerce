<?php

namespace App\Entity;

use App\Repository\CarrierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CarrierRepository::class)
 */
class Carrier
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
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="carrier")
     */
    private $orders;

    /**
     * @ORM\OneToMany(targetEntity=WeightRange::class, mappedBy="carrier")
     */
    private $weightRanges;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->weightRanges = new ArrayCollection();
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

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setCarrier($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getCarrier() === $this) {
                $order->setCarrier(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|WeightRange[]
     */
    public function getWeightRanges(): Collection
    {
        return $this->weightRanges;
    }

    public function addWeightRange(WeightRange $weightRange): self
    {
        if (!$this->weightRanges->contains($weightRange)) {
            $this->weightRanges[] = $weightRange;
            $weightRange->setCarrier($this);
        }

        return $this;
    }

    public function removeWeightRange(WeightRange $weightRange): self
    {
        if ($this->weightRanges->contains($weightRange)) {
            $this->weightRanges->removeElement($weightRange);
            // set the owning side to null (unless already changed)
            if ($weightRange->getCarrier() === $this) {
                $weightRange->setCarrier(null);
            }
        }

        return $this;
    }
}
