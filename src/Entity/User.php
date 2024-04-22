<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User of entity type to set and get data related to user in entity.
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

  /**
   * @var integer $id
   *  It stores Id of user for every single user.
   */
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  /**
   * @var string $email
   *  It stores email of user for every single user.
   */
  #[ORM\Column(length: 180)]
  private ?string $email = null;

  /**
   * @var list<string> The user rolesexam
   *  It stores roles of user.
   */
  #[ORM\Column]
  private array $roles = [];

  /**
   * @var string The hashed password
   *  It stores hashed password of user.
   */
  #[ORM\Column]
  private ?string $password = null;

   /**
   * @var string $profile
   *  It stores profile of user for every single user.
   */
  #[ORM\OneToOne(mappedBy: 'user_id', cascade: ['persist', 'remove'])]
  private ?Profile $profile = null;

   /**
   * @var string $user_type
   *  It stores user type like admin or normal user for every single user.
   */
  #[ORM\Column(length: 255, nullable: true)]
  private ?string $user_type = null;

   /**
   * @var string $owner
   *  It stores owner for admin user.
   */
  #[ORM\Column(length: 50, nullable: true)]
  private ?string $owner = null;

  /**
   * Function to get Id of user.
   *
   * @return int
   *  Returns Id of user.
   */
  public function getId(): ?int
  {
    return $this->id;
  }

  /**
   * Function to get email of user.
   *
   * @return string
   *  Returns email of user.
   */
  public function getEmail(): ?string
  {
    return $this->email;
  }

  /**
   * Function to set email of user.
   *
   * @param string $email
   *
   * @return static
   *  Returns instance of current class.
   */
  public function setEmail(string $email): static
  {
    $this->email = $email;
    return $this;
  }

  /**
   * A visual identifier that represents this user.
   *
   * @see UserInterface
   */
  public function getUserIdentifier(): string
  {
    return (string) $this->email;
  }

  /**
   * Function to get Roles of user.
   *
   * @see UserInterface
   *
   * @return list<string>
   */
  public function getRoles(): array
  {
    $roles = $this->roles;
    // guarantee every user at least has ROLE_USER
    $roles[] = 'ROLE_USER';
    return array_unique($roles);
  }

  /**
   * Function to set roles of user.
   *
   * @param list<string> $roles
   *
   * @return static
   *  Returns instance of current class.
   */
  public function setRoles(array $roles): static
  {
    $this->roles = $roles;
    return $this;
  }

  /**
   * Function to get password of user.
   *
   * @see PasswordAuthenticatedUserInterface
   *
   * @return string
   *  Returns password of current user.
   */
  public function getPassword(): string
  {
    return $this->password;
  }

  /**
   * Function to set password of user.
   *
   * @param string $password
   *
   * @return static
   *  Returns instance of current class.
   */
  public function setPassword(string $password): static
  {
    $this->password = $password;
    return $this;
  }

  /**
   * Function to delete credentials of user.
   *
   * @see UserInterface
   */
  public function eraseCredentials(): void
  {
    // If you store any temporary, sensitive data on the user, clear it here
    // $this->plainPassword = null;
  }

  /**
   * Function to get profile of user.
   *
   * @return string
   *  Returns profile of user.
   */
  public function getProfile(): ?Profile
  {
    return $this->profile;
  }

  /**
   * Function to set profile of user.
   *
   * @param object $profile
   *
   * @return static
   *  Returns instance of current class.
   */
  public function setProfile(Profile $profile): static
  {
    // set the owning side of the relation if necessary
    if ($profile->getUserId() !== $this) {
      $profile->setUserId($this);
    }
    $this->profile = $profile;
    return $this;
  }

  /**
   * Function to get user type of user.
   *
   * @return string
   *  Returns user type of user.
   */
  public function getUserType(): ?string
  {
    return $this->user_type;
  }

  /**
   * Function to set user profile type of user.
   *
   * @param object $user_type
   *
   * @return static
   *  Returns instance of current class.
   */
  public function setUserType(?string $user_type): static
  {
    $this->user_type = $user_type;
    return $this;
  }

  /**
   * Function to get owner.
   *
   * @return string
   *  Returns owner for exam.
   */
  public function getOwner(): ?string
  {
    return $this->owner;
  }

  /**
   * Function to set owner.
   *
   * @param string $owner
   *
   * @return static
   *  Returns instance of current class.
   */
  public function setOwner(?string $owner): static
  {
    $this->owner = $owner;
    return $this;
  }
}
