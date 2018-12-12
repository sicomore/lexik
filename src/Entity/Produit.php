<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;



/**
* @ORM\Entity(repositoryClass="App\Repository\ProduitRepository")
* @Vich\Uploadable
*/
class Produit
{
  /**
  * @ORM\Id()
  * @ORM\GeneratedValue()
  * @ORM\Column(type="integer")
  */
  private $id;

  /**
  * @Assert\NotBlank(message="produit.nom.not_blank")
  * @ORM\Column(type="string", length=255, nullable=false)
  */
  private $nom;

  /**
  * @Gedmo\Slug(fields={"nom"})
  * @ORM\Column(type="string", length=255, nullable=false)
  */
  private $slug;

  /**
  * @ORM\Column(type="text", nullable=false)
  */
  private $description;

  /**
  * @Assert\NotBlank(message="produit.prix.not_blank")
  * @ORM\Column(type="integer", nullable=false)
  */
  private $prix;

  /**
  * @ORM\Column(type="string", length=255, nullable=true)
  */
  private $image;

  /**
  * @var File
  *
  * @Vich\UploadableField(mapping="image_produit", fileNameProperty="image")
  */
  private $imageFile;

  /**
  * @ORM\Column(type="datetime")
  * @Gedmo\Timestampable(on="create")
  */
  private $createdAt;

  /**
  * @ORM\Column(type="datetime")
  * @Gedmo\Timestampable(on="update")
  */
  private $updatedAt;

  /**
  * Quantité dans le panier
  */
  private $quantite;


  public function getId(): ?int
  {
    return $this->id;
  }

  public function getNom(): ?string
  {
    return $this->nom;
  }

  public function setNom(string $nom): self
  {
    $this->nom = $nom;

    return $this;
  }

  public function getSlug(): ?string
  {
    return $this->slug;
  }

  public function setSlug(string $slug): self
  {
    $this->slug = $slug;

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

  public function getPrix(): ?int
  {
    return $this->prix;
  }

  public function setPrix(int $prix): self
  {
    $this->prix = $prix;

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

  public function setUpdatedAt(\DateTimeInterface $updatedAt): self
  {
    $this->updatedAt = $updatedAt;
    return $this;
  }

  public function getImage(): ?string
  {
    return $this->image;
  }

  public function setImage(?string $image): self
  {
    $this->image = $image;
    return $this;
  }

  public function getImageFile(): ?File
  {
    return $this->imageFile;
  }

  public function setImageFile(?File $imageFile = null): self
  {
    $this->imageFile = $imageFile;
    if ($imageFile) {
      $this->updatedAt = new \DateTime('now');
    }
    return $this;
  }

  public function getQuantite(): ?int
  {
    return $this->quantite;
  }

  public function setQuantite(?int $quantite): self
  {
    $this->quantite = $quantite;
    return $this;
  }

}
