<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommentRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 * @ApiResource(
 * normalizationContext={
 *     "groups"={"comments_read"}
 * },
 * subresourceOperations={
 *      "comments_get_subresource"={
 *         "path"="/comments/{id}/author"
 *      },
 *      "api_users_comments_get_subresource"={
 *          "normalization_context"={"groups"={"comments_subresource"}}
 *      }
 *  }
 * )
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"fanfics_read", "users_read"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     * @ApiSubresource(maxDepth=2)
     * @Groups({"comments_read", "fanfics_read"})
     */
    private $author;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"comments_read", "fanfics_read"})
     */
    private $rating;

    /**
     * @ORM\Column(type="text")
     * @Groups({"comments_read", "fanfics_read"})
     */
    private $commentary;

    /**
     * @ORM\Column(type="date")
     * @Groups({"comments_read", "fanfics_read"})
     */
    private $creationDate;

    /**
     * @ORM\ManyToOne(targetEntity=Fanfiction::class, inversedBy="comments")
     * @Groups({"comments_read"})
     */
    private $fanfiction;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getCommentary(): ?string
    {
        return $this->commentary;
    }

    public function setCommentary(string $commentary): self
    {
        $this->commentary = $commentary;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

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
}
