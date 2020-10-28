<?php

namespace App\Entity;

use App\Entity\Fanfiction;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\GenreRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=GenreRepository::class)
 * @ApiResource(
 *  collectionOperations={"GET"={"path"="/genres"}, "POST"},
 *  itemOperations={"GET"={"path"="/genres/{id}"},"PUT","DELETE"},
 *  subresourceOperations={
 *      "fanfiction_get_subresource"={
 *          "path"="/genres/{id}/fanfictions"
 *      },
 *      "api_fanfictions_genres_get_subresource"={
 *          "normalization_context"={"groups"={"genres_subresource"}}
 *      }
 *  }
 * )
 */
class Genre
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"fanfics_read", "tags_read", "fanfics_subresource"})
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Fanfiction::class, inversedBy="genres")
     * @Groups({"genres_subresource"})
     * @ApiSubresource(maxDepth=2)
     */
    private $fanfictions;

    public function __construct()
    {
        $this->fanfictions = new ArrayCollection();
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
     * @return Collection|Fanfiction[]
     */
    public function getFanfictions(): Collection
    {
        return $this->fanfictions;
    }

    public function addFanfiction(Fanfiction $fanfiction): self
    {
        if (!$this->fanfictions->contains($fanfiction)) {
            $this->fanfictions[] = $fanfiction;
        }

        return $this;
    }

    public function removeFanfiction(Fanfiction $fanfiction): self
    {
        if ($this->fanfictions->contains($fanfiction)) {
            $this->fanfictions->removeElement($fanfiction);
        }

        return $this;
    }
}
