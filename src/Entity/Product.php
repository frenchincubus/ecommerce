<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use App\Repository\ProductImageRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
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
     * @ORM\Column(type="string", length=255)
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $EAN13;

    /**
     * @ORM\Column(type="decimal", precision=7, scale=2, nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=3, nullable=true)
     */
    private $weight;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="decimal", precision=7, scale=2, nullable=true)
     */
    private $ttc_price;

    /**
     * @ORM\OneToOne(targetEntity=Quantity::class, mappedBy="product_id", cascade={"persist", "remove"})
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity=CartProducts::class, inversedBy="Product_id")
     */
    private $cartProducts;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, mappedBy="products")
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity=CartProducts::class, mappedBy="product", orphanRemoval=true)
     */
    private $cartProductList;

    /**
     * @ORM\OneToMany(targetEntity=ProductImage::class, mappedBy="product", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $productImages;

    /**
     * @ORM\ManyToOne(targetEntity=Tax::class, inversedBy="product")
     * @ORM\JoinColumn(nullable=true)
     */
    private $vat;

    /**
     * @ORM\ManyToOne(targetEntity=Manufacturer::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=true)
     */
    private $manufacturer;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->cartProductList = new ArrayCollection();
        $this->productImages = new ArrayCollection();
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

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getEAN13(): ?string
    {
        return $this->EAN13;
    }

    public function setEAN13(string $EAN13): self
    {
        $this->EAN13 = $EAN13;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function setWeight(?string $weight): self
    {
        $this->weight = $weight;

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

    public function getTtcPrice(): ?string
    {
        return $this->ttc_price;
    }

    public function setTtcPrice(): self
    {
        $this->ttc_price = $this->price*(1.0 + $this->vat->getVat());

        return $this;
    }

    public function getQuantity(): ?Quantity
    {
        return $this->quantity;
    }

    public function setQuantity(Quantity $quantity): self
    {
        $this->quantity = $quantity;

        // set the owning side of the relation if necessary
        if ($quantity->getProductId() !== $this) {
            $quantity->setProductId($this);
        }

        return $this;
    }

    public function getCartProducts(): ?CartProducts
    {
        return $this->cartProducts;
    }

    public function setCartProducts(?CartProducts $cartProducts): self
    {
        $this->cartProducts = $cartProducts;

        return $this;
    }

    public function __toString()
    {
        return $this->id.' - '.$this->name;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->addProduct($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
            $category->removeProduct($this);
        }

        return $this;
    }

    /**
     * @return Collection|CartProducts[]
     */
    public function getCartProductList(): Collection
    {
        return $this->cartProductList;
    }

    public function addCartProductList(CartProducts $cartProductList): self
    {
        if (!$this->cartProductList->contains($cartProductList)) {
            $this->cartProductList[] = $cartProductList;
            $cartProductList->setProduct($this);
        }

        return $this;
    }

    public function removeCartProductList(CartProducts $cartProductList): self
    {
        if ($this->cartProductList->contains($cartProductList)) {
            $this->cartProductList->removeElement($cartProductList);
            // set the owning side to null (unless already changed)
            if ($cartProductList->getProduct() === $this) {
                $cartProductList->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProductImage[]
     */
    public function getProductImages(): Collection
    {
        return $this->productImages;
    }

    public function addProductImage(ProductImage $productImage): self
    {
        if (!$this->productImages->contains($productImage)) {
            $this->productImages[] = $productImage;
            $productImage->setProduct($this);
        }

        return $this;
    }

    public function removeProductImage(ProductImage $productImage): self
    {
        if ($this->productImages->contains($productImage)) {
            $this->productImages->removeElement($productImage);
            // set the owning side to null (unless already changed)
            if ($productImage->getProduct() === $this) {
                $productImage->setProduct(null);
            }
        }

        return $this;
    }

    public function getCoverImageName() 
    {
        if (!empty($this->productImages))
        {
            if ($this->getProductImages() instanceof ProductImage)
            {
                return $this->getProductImages()->getImageName();
            }
            foreach ($this->getProductImages() as $productImage) {
                if ($productImage->getIsCover() === true)
                {
                    return $productImage->getImageName();
                }
            }
        }
        return null;
    }

    public function getVat(): ?Tax
    {
        return $this->vat;
    }

    public function setVat(?Tax $vat): self
    {
        $this->vat = $vat;

        return $this;
    }

    public function getManufacturer(): ?Manufacturer
    {
        return $this->manufacturer;
    }

    public function setManufacturer(?Manufacturer $manufacturer): self
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }
}
