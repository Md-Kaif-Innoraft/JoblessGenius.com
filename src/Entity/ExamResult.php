<?php

namespace App\Entity;

use App\Repository\ExamResultRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ExamResult of entity type to set and get Exam result data in entity.
 */
#[ORM\Entity(repositoryClass: ExamResultRepository::class)]
class ExamResult
{

  /**
   * @var integer $id
   *  It stores Id of ExamResult Entity for every single exam.
   */
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  /**
   * @var integer $user_id
   *  It stores User Id of Applicant who is appering for every single exam.
   */
  #[ORM\ManyToOne]
  #[ORM\JoinColumn(nullable: false)]
  private ?User $user_id = null;

  /**
   * @var integer $exam_id
   *  It stores Exam Id of Exam Entity for every single result.
   */
  #[ORM\ManyToOne]
  #[ORM\JoinColumn(nullable: false)]
  private ?Exam $exam_id = null;

  /**
   * @var integer $result
   *  It stores result of Exam Entity for every single applicant.
   */
  #[ORM\Column]
  private ?int $result = null;

  /**
   * Function to get Id of ExamResult.
   *
   * @return int
   *  Returns Id of ExamResult.
   */
  public function getId(): ?int
  {
    return $this->id;
  }

   /**
   * Function to get User Id of Applicant.
   *
   * @return object
   *  Returns user id of applicant.
   */
  public function getUserId(): ?User
  {
    return $this->user_id;
  }

  /**
   * Function to set User Id of Applicant.
   *
   * @param object $user_id
   *
   * @return static
   *  Returns instance of ExamResult class.
   */
  public function setUserId(?User $user_id): static
  {
    $this->user_id = $user_id;
    return $this;
  }

  /**
   * Function to get Exam Id of ExamResult class.
   *
   * @return int
   *  Returns exam id of applicant.
   */
  public function getExamId(): ?Exam
  {
    return $this->exam_id;
  }

  /**
   * Function to set Exam Id of ExamResult.
   *
   * @param int $exam_id
   *
   * @return static
   *  Returns instance of ExamResult class.
   */
  public function setExamId(?Exam $exam_id): static
  {
    $this->exam_id = $exam_id;
    return $this;
  }

  /**
   * Function to get Result of Applicant.
   *
   * @return int
   *  Returns result of applicant.
   */
  public function getResult(): ?int
  {
    return $this->result;
  }

  /**
   * Function to set result of Applicant.
   *
   * @param int $result
   *
   * @return static
   *  Returns instance of current class.
   */
  public function setResult(int $result): static
  {
    $this->result = $result;
    return $this;
  }
}
