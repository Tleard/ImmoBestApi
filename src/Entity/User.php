<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"get"}},
 *     itemOperations={
 *          "get"={
 *              "access_control"="is_granted('IS_AUTHENTICATED_FULLY')",
 *               "normalization_context"={
 *                  "groups"={"get"}
 *               }
 *          },
 *          "put"={
 *              "access_control"="is_granted('IS_AUTHENTICATED_FULLY') and object.getAuthor() == user",
 *              "denormalization_context"={
 *                  "groups"={"put"}
 *              },
 *              "normalization_context"={
 *                  "groups"={"get"}
 *              }
 *          }
 *      },
 *     collectionOperations={
 *          "post" = {
 *              "denormalization_context"={
 *                  "groups"={"post"}
 *              },
 *              "normalization_context"={
 *                  "groups"={"put"}
 *              }
 *          }
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=3, max=32)
     * @Groups({"get","put", "post"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(
     *     pattern="/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/",
     *     message="Password must be six characters long and contain at least one digit, one uppercase letter and one lower case letter"
     * )
     * @Groups({"put", "post"})
     */
    private $password;

    /**
     * @Assert\Expression(
     *     "this.getPassword() == this.getRetypedPassword()",
     *      message="Passwords does not match"
     * )
     * @Groups({"put", "post"})
     */
    private $retypedPassword;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get", "put", "post"})
     * @Assert\NotBlank()
     * @Assert\Length(min=5, max=32)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"put", "post"})
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Assert\Length(min=5, max=45)
     */
    private $email;

    /**
     * @@ORM\OneToMany(targetEntity="App\Entity\Advertisement", mappedBy="author")
     * @Groups({"get"})
     */
    private $advertisement;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="author")
     * @Groups({"get"})
     */
    private $comments;

    public function __construct()
    {
        $this->advertisement = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
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
     * @return Collection
     */
    public function getAdvertisement()
    {
        return $this->advertisement;
    }

    /**
     * @return Collection
     */
    public function getComments()
    {
        return $this->comments;
    }




    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {

    }

    /**
     * @return mixed
     */
    public function getRetypedPassword()
    {
        return $this->retypedPassword;
    }

    /**
     * @param mixed $retypedPassword
     */
    public function setRetypedPassword($retypedPassword): void
    {
        $this->retypedPassword = $retypedPassword;
    }


}
