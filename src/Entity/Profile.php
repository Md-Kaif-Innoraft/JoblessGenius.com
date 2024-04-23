<?php

namespace App\Entity;

use App\Repository\ProfileRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 * Class Profile of entity type to set and get profile data.
 */
#[ORM\Entity(repositoryClass: ProfileRepository::class)]
class Profile
{

  /**
   * @var integer $id
   *  It stores Id of Profile for every single exam.
   */
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  /**
   * @var integer $user_id
   *  It stores User Id of user for every Profile.
   */
  #[ORM\OneToOne(inversedBy: 'profile', cascade: ['persist', 'remove'])]
  #[ORM\JoinColumn(nullable: false)]
  private ?User $user_id = null;

  /**
   * @var string $name
   *  It stores name of user for every user.
   */
  #[ORM\Column(length: 255)]
  private ?string $name = null;

  /**
   * @var string $college_name
   *  It stores college name of for every user.
   */
  #[ORM\Column(length: 255)]
  private ?string $college_name = null;

  /**
   * @var string $email
   *  It stores email of for every single user.
   */
  #[ORM\Column(length: 255)]
  private ?string $email = null;

  /**
   * @var string $degree
   *  It stores degree of for every single user.
   */
  #[ORM\Column(length: 255)]
  private ?string $degree = null;

  /**
   * @var string $branch
   *  It stores branch of for every single user.
   */
  #[ORM\Column(length: 255)]
  private ?string $branch = null;

  /**
   * @var int $graduemailation_cgpa
   *  It stores graduation_cgpa of for every single user.
   */
  #[ORM\Column(length: 255)]
  private ?string $graduation_cgpa = null;

  /**
   * @var string $school_name_12
   *  It stores school name of class 12 of for every single user.
   */
  #[ORM\Column(length: 255)]
  private ?string $school_name_12 = null;

  /**
   * @var int $per_12
   *  It stores percentage in class 12 of for every single user.
   */
  #[ORM\Column]
  private ?int $per_12 = null;

  /**
   * @var string $school_name_10
   *  It stores school name of class 10 of for every single user.
   */
  #[ORM\Column(length: 255)]
  private ?string $school_name_10 = null;

  /**
   * @var int $per_10
   *  It stores percentage of class 10 of for every single user.
   */
  #[ORM\Column]
  private ?int $per_10 = null;

  /**
   * @var string $resule
   *  It stores resume link of for every single user.
   */
  #[ORM\Column(length: 255)]
  private ?string $resume = null;

  /**
   * Function to get Id of profle.
   *
   * @return int
   *  Returns Id of profile.
   */
  public function getId(): ?int
  {
    return $this->id;
  }

  /**
   * Function to get user Id of user.
   *
   * @return int
   *  Returns user Id of user.
   */
  public function getUserId(): ?User
  {
    return $this->user_id;
  }

  /**
   * Function to set user Id of user profile.
   *
   * @param int $user_id
   *
   * @return static
   *  Returns instance of current class.
   */
  public function setUserId(User $user_id): static
  {
    $this->user_id = $user_id;
    return $this;
  }

  /**
   * Function to get name in user profile.
   *
   * @return string
   *  Returns name of user in profile.
   */
  public function getName(): ?string
  {
    return $this->name;
  }

  /**
   * Function to set name of user profile.
   *
   * @param string $name
   *
   * @return static
   *  Returns instance of current class.
   */
  public function setName(string $name): static
  {
    $this->name = $name;
    return $this;
  }

  /**
   * Function to get college name of user.
   *
   * @return string
   *  Returns college name of user.
   */
  public function getCollegeName(): ?string
  {
    return $this->college_name;
  }

  /**
   * Function to set college name in user profile.
   *
   * @param string $college_name
   *
   * @return static
   *  Returns instance of current class.
   */
  public function setCollegeName(string $college_name): static
  {
    $this->college_name = $college_name;
    return $this;
  }

  /**
   * Function to get email from user profile.
   *
   * @return string
   *  Returns email of user.
   */
  public function getEmail(): ?string
  {
    return $this->email;
  }

  /**
   * Function to set email Id of user profile.
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
   * Function to get degree of user profile.
   *
   * @return string
   *  Returns degree of user from profile.
   */
  public function getDegree(): ?string
  {
    return $this->degree;
  }

  /**
   * Function to set degree of user profile.
   *
   * @param string $degree
   *
   * @return static
   *  Returns instance of current class.
   */
  public function setDegree(string $degree): static
  {
    $this->degree = $degree;
    return $this;
  }

  /**
   * Function to get branch of user profile.
   *
   * @return string
   *  Returns branch of user from user profile.
   */
  public function getBranch(): ?string
  {
    return $this->branch;
  }

  /**
   * Function to set branch of user profile.
   *
   * @param string $branch
   *
   * @return static
   *  Returns instance of current class.
   */
  public function setBranch(string $branch): static
  {
    $this->branch = $branch;
    return $this;
  }

  /**
   * Function to get graduation cgpa of user profile.
   *
   * @return int
   *  Returns graduation cgpa of user from user profie.
   */
  public function getGraduationCgpa(): ?string
  {
    return $this->graduation_cgpa;
  }

  /**
   * Function to set graduation cgpa of user profile.
   *
   * @param string $graduation_cgpa

   * @return static
   *  Returns instance of current class.
   */
  public function setGraduationCgpa(string $graduation_cgpa): static
  {
    $this->graduation_cgpa = $graduation_cgpa;
    return $this;
  }

  /**
   * Function to get school name of class 12 of user.
   *
   * @return string
   *  Returns school name of class 12 of user.
   */
  public function getSchoolName12(): ?string
  {
    return $this->school_name_12;
  }

  /**
   * Function to set school name of user profile.
   *
   * @param string $school_name_12
   *
   * @return static
   *  Returns instance of current class.
   */
  public function setSchoolName12(string $school_name_12): static
  {
    $this->school_name_12 = $school_name_12;
    return $this;
  }

  /**
   * Function to get class 12 percentage of user.
   *
   * @return int
   *  Returns class 12 percentage of user.
   */
  public function getPer12(): ?int
  {
    return $this->per_12;
  }

  /**
   * Function to set class 12 percentage of user.
   *
   * @param int $per_12
   *
   * @return static
   *  Returns instance of current class.
   */
  public function setPer12(int $per_12): static
  {
    $this->per_12 = $per_12;
    return $this;
  }

  /**
   * Function to get class 10 school name of user.
   *
   * @return string
   *  Returns class 10 school name of user.
   */
  public function getSchoolName10(): ?string
  {
    return $this->school_name_10;
  }

  /**
   * Function to set class 10 name of user.
   *
   * @param string $school_name
   *
   * @return int
   *  Returns class 12 percentage of user.
   */
  public function setSchoolName10(string $school_name_10): static
  {
    $this->school_name_10 = $school_name_10;
    return $this;
  }

  /**
   * Function to get class 10 percentage of user.
   *
   * @return int
   *  Returns class 10 percentage of user.
   */
  public function getPer10(): ?int
  {
    return $this->per_10;
  }

  /**
   * Function to set class 10 percentage of user.
   *
   * @param int $per_10
   *
   * @return static
   *  Returns insatance of current class.
   */
  public function setPer10(int $per_10): static
  {
    $this->per_10 = $per_10;
    return $this;
  }

  /**
   * Function to get resume link of user.
   *
   * @return string
   *  Returns resume link of user.
   */
  public function getResume(): ?string
  {
    return $this->resume;
  }

  /**
   * Function to set resume link of user.
   *
   * @param string $resume
   *
   * @return static
   *  Returns instance of current class.
   */
  public function setResume(string $resume): static
  {
    $this->resume = $resume;
    return $this;
  }
}
