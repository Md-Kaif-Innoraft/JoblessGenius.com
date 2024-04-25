<?php

namespace App\Entity;

use App\Repository\ExamApplicationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ExamApplication of entity type to set and get data in entity.
 */
#[ORM\Entity(repositoryClass: ExamApplicationRepository::class)]
class ExamApplication
{

  /**
   * @var integer $id.
   *  It stores Id of ExamApplication ExamEntity for every single ExamApplication.
   */
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  /**
   * @var integer $user_id
   *  It stores User Id of Applicant of ExamApplication.
   */
  #[ORM\ManyToOne]
  #[ORM\JoinColumn(nullable: false)]
  private ?User $user_id = null;

  /**
   * @var object $exam_id
   *  It stores exam id of ExamEntity for every single ExamApplication.
   */
  #[ORM\ManyToOne]
  #[ORM\JoinColumn(nullable: false)]
  private ?Exam $exam_id = null;

  /**
   * @var /DateTimeImmutable $applied_at
   *  It stores Date and time of ExamApplication ExamEntity for every single ExamApplication.
   */
  #[ORM\Column]
  private ?\DateTimeImmutable $applied_at = null;

  /**
   * Function to get Id of ExamApplication.
   *
   * @return int
   *  Returns Id of ExamApplication.
   */
  public function getId(): ?int
  {
    return $this->id;
  }

  /**
   * Function to get User Id of Exam Applicant.
   *
   * @return int
   *  Returns User Id of exam Applicant.
   */
  public function getUserId(): ?user
  {
    return $this->user_id;
  }

  /**
   * Function to set User Id of Exam Applicant.
   *
   * @param object $user_id
   *
   * @return static
   *  Returns instance of exam Applicant class.
   */
  public function setUserId(?user $user_id): static
  {
    $this->user_id = $user_id;
    return $this;
  }

  /**
   * Function to get Exam Id of Exam Applicant.
   *
   * @return int
   *  Returns exam Id of exam Applicant.
   */
  public function getExamId(): ?exam
  {
    return $this->exam_id;
  }

  /**
   * Function to set Exam Id of Exam Applicant.
   *
   * @param int
   *
   * @return static
   *  Returns instance of exam Applicant of class.
   */
  public function setExamId(?exam $exam_id): static
  {
    $this->exam_id = $exam_id;
    return $this;
  }

  /**
   * Function to get date and time of Exam Application.
   *
   * @return /DateTimeImmutable
   *  Returns applied date and time of exam Applicant.
   */
  public function getAppliedAt(): ?\DateTimeImmutable
  {
    return $this->applied_at;
  }

 /**
   * Function to set date and time of Exam Application.
   *
   * @param /DateTimeImmutable
   *
   * @return static
   *  Returns instance exam Applicant class.
   */
  public function setAppliedAt(\DateTimeImmutable $applied_at): static
  {
    $this->applied_at = $applied_at;
    return $this;
  }
}
