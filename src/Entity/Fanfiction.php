<?php

namespace App\Entity;

use App\Entity\Tags;
use App\Entity\User;
use App\Entity\Genre;
use App\Entity\Chapter;
use App\Entity\Comment;
use App\Entity\Language;
use App\Entity\FanficImage;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\FanfictionRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=FanfictionRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(
 *  fields={"title"},
 *  message="A fiction with that name already exist. Please try another."
 * )
 * @ApiResource(
 *  attributes={
 *      "pagination_enabled"=true,
 *      "order"={"title":"asc"}
 *  },
 *  collectionOperations={"GET"={"path"="/fanfictions"}, "POST"},
 *  itemOperations={"GET"={"path"="/fanfictions/{id}"},"PUT","DELETE"},
 *  normalizationContext={
 *      "groups"={"fanfics_read"}
 *  },
 *  subresourceOperations={
 *      "fanfic_images_get_subresource"={
 *          "path"="/fanfictions/{id}/gallery"
 *      },
 *      "genres_get_subresource"={
 *          "path"="/fanfictions/{id}/genres"
 *      },
 *      "api_tags_fanfictions_get_subresource"={
 *          "normalization_context"={"groups"={"fanfics_subresource"}}
 *      }
 *  }
 * )
 * @ApiFilter(OrderFilter::class, properties={"chapters.addedAt","avgRatings","title"}),
 * @ApiFilter(SearchFilter::class, properties={"title","author"})
 */
class Fanfiction
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"fanfics_read", "tags_read", "users_read", "chapters_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"fanfics_read", "fanfics_subresource", "users_read", "tags_read", "chapters_read"})
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"fanfics_read", "tags_read"})
     */
    private $summary;

    /**
     * @ORM\ManyToMany(targetEntity=Tags::class, mappedBy="fictions", cascade={"persist"})
     * @ApiSubresource(maxDepth=2)
     * @Groups({"fanfics_read", "users_read"})
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="fanfictions")
     * @ApiSubresource(maxDepth=2)
     * @Groups({"fanfics_read", "fanfics_subresource", "tags_read"})
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="fanfiction")
     * @ApiSubresource(maxDepth=2)
     * @Groups({"fanfics_read", "fanfics_subresource", "users_read", "tags_read"})
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=Chapter::class, mappedBy="fanfiction", orphanRemoval=true)
     * @ApiSubresource(maxDepth=2)
     * @Groups({"fanfics_read", "users_read", "tags_read"})
     */
    private $chapters;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"fanfics_read", "fanfics_subresource", "tags_read"})
     */
    private $coverImage;

    /**
     * @ORM\ManyToOne(targetEntity=Language::class, inversedBy="fanfiction")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"fanfics_read", "fanfics_subresource", "tags_read"})
     * @ApiSubresource(maxDepth=2)
     */
    private $language;

    /**
     * @ORM\ManyToMany(targetEntity=Genre::class, mappedBy="fanfictions")
     * @Groups({"fanfics_read", "fanfics_subresource", "tags_read"})
     * @ApiSubresource(maxDepth=2)
     */
    private $genres;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->chapters = new ArrayCollection();
        $this->fanficImages = new ArrayCollection();
        $this->genres = new ArrayCollection();
    }

    /**
     * @Groups({"fanfics_read", "tags_read", "users_read"})
     *
     * @return float
     */
    public function getAvgRatings(){
        $sum = array_reduce($this->comments->toArray(), function($total, $comment){
            return $total + $comment->getRating();
        },0);

        if(count($this->comments) > 0) return $average = round($sum / count($this->comments));

        return 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * @return Collection|Tags[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tags $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addFiction($this);
        }

        return $this;
    }

    public function removeTag(Tags $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            $tag->removeFiction($this);
        }

        return $this;
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

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setFanfiction($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getFanfiction() === $this) {
                $comment->setFanfiction(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Chapter[]
     */
    public function getChapters(): Collection
    {
        return $this->chapters;
    }

    public function addChapter(Chapter $chapter): self
    {
        if (!$this->chapters->contains($chapter)) {
            $this->chapters[] = $chapter;
            $chapter->setFanfiction($this);
        }

        return $this;
    }

    public function removeChapter(Chapter $chapter): self
    {
        if ($this->chapters->contains($chapter)) {
            $this->chapters->removeElement($chapter);
            // set the owning side to null (unless already changed)
            if ($chapter->getFanfiction() === $this) {
                $chapter->setFanfiction(null);
            }
        }

        return $this;
    }

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    public function setCoverImage(?string $coverImage): self
    {
        $this->coverImage = $coverImage;

        return $this;
    }

    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    public function setLanguage(?Language $language): self
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return Collection|Genre[]
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): self
    {
        if (!$this->genres->contains($genre)) {
            $this->genres[] = $genre;
            $genre->addFanfiction($this);
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        if ($this->genres->contains($genre)) {
            $this->genres->removeElement($genre);
            $genre->removeFanfiction($this);
        }

        return $this;
    }
}
