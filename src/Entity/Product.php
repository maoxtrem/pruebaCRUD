<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     * @Assert\NotBlank(message="Este Campo es obligatorio")
     * @Assert\Length(
     *      min = 4,
     *      max = 10,
     *      minMessage = "El código debe tener minimo  4 caracteres",
     *      maxMessage = "El código debe tener miximo  10 caracteres"
     * )
     * @Assert\Regex(
     *     pattern="/^\S\W/",
     *
     *     match=false,
     *     message=" El código no puede contener caracteres especiales ni espacios."
     * )
     * @ORM\Column(name="code", type="string", length=255, nullable=false)
     */
    protected $code;

    /**
     * @var string
     * @Assert\NotBlank(message="Este Campo es obligatorio")
     * @Assert\Length(
     *      min = 4,
     *      minMessage = "El nombre debe contener mínimo  4 caracteres",
     * )
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     * @var string
     * @Assert\NotBlank(message="Este Campo es obligatorio")
     * @ORM\Column(name="description", type="string", length=150, nullable=false)
     */
    protected $description;

    /**
     * @var string
     * @Assert\NotBlank(message="Este Campo es obligatorio")
     * @ORM\Column(name="brand", type="string", length=150, nullable=false)
     */
    protected $brand;

    /**
     * @var Category
     * @Assert\NotBlank(message="Este Campo es obligatorio")
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category", referencedColumnName="id")
     * })
     */
    protected $category;

    /**
     * @var float
     * @Assert\NotBlank(message="Este Campo es obligatorio")
     * @ORM\Column(name="price", type="float", nullable=false)
     */
    protected $price;

    /**
     * @var \DateTime
     * @ORM\Column(name="createAt", type="datetime", nullable=true)
     */
    protected $createAt;

    /**
     * @var \DateTime
     * @ORM\Column(name="updateAt", type="datetime", nullable=true)
     */
    protected $updateAt;


    public function getId()
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code):self
    {
        $this->code = $code;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name):self
    {
        $this->name = $name;
        return $this;
    }


    public function getDescription(): ?string
    {
        return $this->description;
    }


    public function setDescription(?string $description):self
    {
        $this->description = $description;
        return $this;
    }


    public function getBrand(): ?string
    {
        return $this->brand;
    }


    public function setBrand(?string $brand):self
    {
        $this->brand = $brand;
        return $this;
    }


    public function getCategory(): ?Category
    {
        return $this->category;
    }


    public function setCategory(?Category $category):self
    {
        $this->category = $category;
        return $this;
    }


    public function getPrice(): ?float
    {
        return $this->price;
    }


    public function setPrice(?float $price):self
    {
        $this->price = $price;
        return $this;
    }


    public function getCreateAt(): ?\DateTime
    {
        return $this->createAt;
    }


    public function setCreateAt(?\DateTime $createAt):self
    {
        $this->createAt = $createAt;
        return $this;
    }


    public function getUpdateAt(): ?\DateTime
    {
        return $this->updateAt;
    }


    public function setUpdateAt(?\DateTime $updateAt):self
    {
        $this->updateAt = $updateAt;
        return $this;
    }

    /**
     * @Assert\IsTrue(message="El nombre y el código no pueden repetirse")
     */
    public function isCodeNotEqualsName(): bool
    {
        return $this->code !== $this->name;
    }

}