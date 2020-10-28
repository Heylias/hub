<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\LanguageRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=LanguageRepository::class)
 * @ApiResource(
 *  collectionOperations={"GET"={"path"="/languages"}, "POST"},
 *  itemOperations={"GET"={"path"="/languages/{id}"},"PUT","DELETE"},
 *  subresourceOperations={
 *      "fanfiction_get_subresource"={
 *          "path"="/languages/{id}/fanfictions"
 *      },
 *      "api_fanfictions_languages_get_subresource"={
 *          "normalization_context"={"groups"={"languages_subresource"}}
 *      }
 *  }
 * )
 */
class Language
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"fanfics_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"fanfics_read"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"fanfics_read"})
     */
    private $short;

    /**
     * @ORM\OneToMany(targetEntity=Fanfiction::class, mappedBy="language")
     * @ApiSubresource(maxDepth=2)
     */
    private $fanfiction;

    public function __construct()
    {
        $this->fanfiction = new ArrayCollection();
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

    public function getShort(): ?string
    {
        return $this->short;
    }

    public function setShort(string $short): self
    {
        $this->short = $short;

        return $this;
    }

    /**
     * @return Collection|Fanfiction[]
     */
    public function getFanfiction(): Collection
    {
        return $this->fanfiction;
    }

    public function addFanfiction(Fanfiction $fanfiction): self
    {
        if (!$this->fanfiction->contains($fanfiction)) {
            $this->fanfiction[] = $fanfiction;
            $fanfiction->setLanguage($this);
        }

        return $this;
    }

    public function removeFanfiction(Fanfiction $fanfiction): self
    {
        if ($this->fanfiction->contains($fanfiction)) {
            $this->fanfiction->removeElement($fanfiction);
            // set the owning side to null (unless already changed)
            if ($fanfiction->getLanguage() === $this) {
                $fanfiction->setLanguage(null);
            }
        }

        return $this;
    }
}
