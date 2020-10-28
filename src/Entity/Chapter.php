<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ChapterRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ChapterRepository::class)
 * @ApiResource(
 *  collectionOperations={"GET","POST"},
 *  itemOperations={"GET","PUT","DELETE"},
 *  subresourceOperations={
 *      "api_fanfics_chapters_get_subresource"={
 *          "normalization_context"={"groups"={"chapters_subresource"}}
 *      }
 *  },
 *  normalizationContext={
 *      "groups"={"chapters_read"}
 *  }
 * )
 */
class Chapter
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Groups({"fanfics_read", "chapters_read", "chapters_subresource", "users_read"})
     */
    private $addedAt;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"fanfics_read", "chapters_read", "chapters_subresource", "users_read"})
     */
    private $chapter;

    /**
     * @ORM\ManyToOne(targetEntity=Fanfiction::class, inversedBy="chapters")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"chapters_read"})
     */
    private $fanfiction;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"fanfics_read", "chapters_read", "chapters_subresource", "users_read"})
     */
    private $link;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"fanfics_read", "chapters_read", "chapters_subresource", "users_read"})
     */
    private $title;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddedAt(): ?\DateTimeInterface
    {
        return $this->addedAt;
    }

    public function setAddedAt(\DateTimeInterface $addedAt): self
    {
        $this->addedAt = $addedAt;

        return $this;
    }

    public function getChapter(): ?int
    {
        return $this->chapter;
    }

    public function setChapter(int $chapter): self
    {
        $this->chapter = $chapter;

        return $this;
    }

    public function getFanfiction(): ?Fanfiction
    {
        return $this->fanfiction;
    }

    public function setFanfiction(?Fanfiction $fanfiction): self
    {
        $this->fanfiction = $fanfiction;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }
}
