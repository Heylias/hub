<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(
 *  fields={"email", "pseudonym"},
 *  errorPath="pseudonym",
 *  message="This email or username is taken. Please try another."
 * )
 * @ApiResource(
 *  normalizationContext={
 *      "groups"={"users_read"}
 *  },
 *  subresourceOperations={
 *      "comments_get_subresource"={
 *          "path"="/users/{id}/comments"
 *      },
 *      "api_comments_users_get_subresource"={
 *          "normalization_context"={"groups"={"users_subresource"}}
 *      }
 *  }
 * )
 * @ApiFilter(SearchFilter::class, properties={"pseudonym"})
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"users_read", "fanfics_read", "tags_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email(message="This mail address is not valid.")
     * @Groups({"users_read"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups({"users_read"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\Length(min=8, minMessage="Your password must at least contain 8 characters.")
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="password", message="Your passwords doesn't match.")
     */
    public $passwordConfirm;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="You need a username")
     * @Assert\Length(min=6, minMessage="Your username must at least contain 6 characters.")
     * @Groups({"fanfics_read", "users_read", "comments_read"})
     */
    private $pseudonym;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="author", orphanRemoval=true)
     * @ApiSubresource(maxDepth=2)
     * @Groups({"users_read"})
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=Fanfiction::class, mappedBy="author")
     * @ApiSubresource(maxDepth=2)
     * @Groups({"users_read"})
     */
    private $fanfictions;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Image(mimeTypes={"image/png","image/jpeg","image/gif"}, mimeTypesMessage="Only jpg, png or gif", groups={"front"})
     * @Assert\File(maxSize="1024k", maxSizeMessage="Picture too big", groups={"front"})
     * @Groups({"fanfics_read", "comments_read", "users_read"})
     */
    private $userImage;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->fanfictions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPseudonym(): ?string
    {
        return $this->pseudonym;
    }

    public function setPseudonym(string $pseudonym): self
    {
        $this->pseudonym = $pseudonym;

        return $this;
    }

    public function getUserImage(): ?string
    {
        return $this->userImage;
    }

    public function setUserImage(?string $userImage): self
    {
        $this->userImage = $userImage;

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
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

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
            $fanfiction->setAuthor($this);
        }

        return $this;
    }

    public function removeFanfiction(Fanfiction $fanfiction): self
    {
        if ($this->fanfictions->contains($fanfiction)) {
            $this->fanfictions->removeElement($fanfiction);
            // set the owning side to null (unless already changed)
            if ($fanfiction->getAuthor() === $this) {
                $fanfiction->setAuthor(null);
            }
        }

        return $this;
    }
}
