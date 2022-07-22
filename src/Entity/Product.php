<?php

namespace App\Entity;

use DateTimeInterface;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\CommandeDetail;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'text')]
    private $description;


    #[ORM\Column(type: 'float')]
    private $price;

    /**
     * @ORM\Column(name="`integer`", type="integer")
     */
    private $integer;

    /**
     * @ORM\Column(type="decimal", scale=3)
     */
    private $number;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private $category;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $format;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $dateDeSortie;

    /**
     * @ORM\OneToMany(targetEntity=CommandeDetail::class, mappedBy="product", orphanRemoval=true)
     */
    private $commandeDetails;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $illustration;


    public function __construct()
    {
        $this->commandeDetails = new ArrayCollection();
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

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(string $format): self
    {
        $this->sformat = $format;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateDeSortie(): ?\DateTimeInterface
    {
        return $this->dateDeSortie;
    }

     public function setDateDeSortie(\DateTimeInterface $dateDeSortie): self
    {
        $this->dateDeSortie = $dateDeSortie;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<string, CommandeDetail>
     */
    public function getCommandeDetails(): Collection
    {
        return $this->commandeDetails;
    }

    public function addCommandeDetail(CommandeDetail $commandeDetail): self
    {
        if (!$this->commandeDetails->contains($commandeDetail)) {
            $this->commandeDetails[] = $commandeDetail;
            $commandeDetail->setProduct($this);
        }

        return $this;
    }

    public function removeCommandeDetail(CommandeDetail $commandeDetail): self
    {
        if ($this->commandeDetails->removeElement($commandeDetail)) {
            // set the owning side to null (unless already changed)
            if ($commandeDetail->getProduct() === $this) {
                $commandeDetail->setProduct(null);
            }
        }

        return $this;
    }

    public function getIllustration(): ?string
    {
        return $this->illustration;
    }

    public function setIllustration(?string $illustration): self
    {
        $this->illustration = $illustration;

        return $this;
    }
}
