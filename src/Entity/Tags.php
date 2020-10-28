<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TagsRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=TagsRepository::class)
 * @ApiResource(
 *  attributes={
 *      "pagination_enabled"=true,
 *      "order"={"name":"asc"}
 *  },
 *  collectionOperations={"GET"={"path"="/tags"}, "POST"},
 *  itemOperations={"GET"={"path"="/tags/{id}"},"PUT","DELETE"},
 *  normalizationContext={
 *      "groups"={"tags_read"}
 *  },
 *  subresourceOperations={
 *      "fanfictions_get_subresource"={
 *          "path"="/tags/{id}/fanfictions"
 *      }
 *  }
 * )
 * @ApiFilter(SearchFilter::class, properties={"name"}
 * )
 */
class Tags
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"tags_read", "fanfics_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"tags_read", "fanfics_read"})
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Fanfiction::class, inversedBy="tags", cascade={"persist"})
     * @ApiSubresource(maxDepth=2)
     * @Groups({"tags_read"})
     */
    private $fictions;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"tags_read", "fanfics_read"})
     */
    private $description;

    public function __construct()
    {
        $this->fictions = new ArrayCollection();
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

    /**
     * @return Collection|Fanfiction[]
     */
    public function getFictions(): Collection
    {
        return $this->fictions;
    }

    public function addFiction(Fanfiction $fiction): self
    {
        if (!$this->fictions->contains($fiction)) {
            $this->fictions[] = $fiction;
        }

        return $this;
    }

    public function removeFiction(Fanfiction $fiction): self
    {
        if ($this->fictions->contains($fiction)) {
            $this->fictions->removeElement($fiction);
        }

        return $this;
    }
}
