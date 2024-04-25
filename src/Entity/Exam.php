<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ExamRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Exam of entity type to set and get data in entity.
 */
#[ORM\Entity(repositoryClass: ExamRepository::class)]
class Exam
{

  /**
   * @var integer $id
   *  It stores Id of Exam Entity for every single exam.
   */
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  /**
   * @var string $title
   *  It stores title of Exam Entity for every single exam.
   */
  #[ORM\Column(length: 255)]
  private ?string $title = null;

  /**
   * @var string $description
   *  It stores description of Exam for every single exam.
   */
  #[ORM\Column(length: 255)]
  private ?string $description = null;

  /**
   * @var $time
   *  It stores duration of Exam for every single exam.
   */
  #[ORM\Column(type: Types::TIME_MUTABLE)]
  private ?\DateTimeInterface $duration = null;

  /**
   * @var integer $total_marks
   *  It stores total marks of Exam for every single exam.
   */
  #[ORM\Column]
  private ?int $total_marks = null;

  /**
   * @var string $status
   *  It stores status of Exam for every single exam.
   */
  #[ORM\Column(length: 255)]
  private ?string $status = null;

  /**
   * @var string $owner.
   *  It stores owner name of Exam for every single exam.
   */
  #[ORM\Column(length: 255)]
  private ?string $owner = null;

  /**
  * @var Collection<int, Question>
  *  It stores questions of every exam.
  */
  #[ORM\OneToMany(targetEntity: Question::class, mappedBy: 'ques_id')]
  private Collection $questions;

  /**
   * Constructor to create new Array collection of questions.
   */
  public function __construct()
  {
    $this->questions = new ArrayCollection();
  }

  /**
   * Function to get Id of Exam.
   *
   * @return int
   *  Returns Id of exam.
   */
  public function getId(): ?int
  {
    return $this->id;
  }

  /**
   * Function to get Title of Exam.
   *
   * @return string
   *  Returns Title of exam.
   */
  public function getTitle(): ?string
  {
    return $this->title;
  }

  /**
   * Function to set Title of Exam.
   *
   * @param string
   *
   * @return static
   *  Return Current instance of the class.
   */
  public function setTitle(string $title): static
  {
    $this->title = $title;
    return $this;
  }

  /**
   * Function to get description of Exam.
   *
   * @return string.
   *  Returns description of the exam.
   */
  public function getDescription(): ?string
  {
    return $this->description;
  }

  /**
   * Function to set description of Exam.
   *
   * @param string
   *  sets description of the exam.
   *
   * @return static
   *  Return Current instance of the class.
   */
  public function setDescription(string $description): static
  {
    $this->description = $description;
    return $this;
  }

   /**
   * Function to get duration of Exam.
   *
   * @return \DateTimeInterface
   *  Return duration of the exam.
   */
  public function getDuration(): ?DateTimeInterface
  {
    return $this->duration;
  }

  /**
   * Function to set duration of Exam.
   *
   * @param DateTimeInterface $duration
   *
   * @return static
   *  Return current instance of the class.
   */
  public function setDuration(\DateTimeInterface $duration): static
  {
    $this->duration = $duration;
    return $this;
  }

  /**
   * Function to get Total marks of Exam.
   *
   * @return int
   *  Return total marks of the exam.
   */
  public function getTotalMarks(): ?int
  {
    return $this->total_marks;
  }

  /**
   * Function to set total marks of Exam.
   *
   * @param int
   *
   * @return static
   *  Return current instance of the class.
   */
  public function setTotalMarks(int $total_marks): static
  {
    $this->total_marks = $total_marks;
    return $this;
  }

  /**
   * Function to get status of Exam.
   *
   * @return string
   *  Return status of the exam.
   */
  public function getStatus(): ?string
  {
   return $this->status;
  }

  /**
   * Function to set status of Exam.
   *
   * @param string
   *
   * @return static
   *  Return instance of the class.
   */
  public function setStatus(string $status): static
  {
    $this->status = $status;
    return $this;
  }

  /**
   * Function to get owner of Exam.
   *
   * @return string
   *  Return owner of the exam.
   */
  public function getOwner(): ?string
  {
    return $this->owner;
  }

  /**
   * Function to set owner of Exam.
   *
   * @param string
   *
   * @return static
   *  Return instance of the exam.
   */
  public function setOwner(string $owner): static
  {
    $this->owner = $owner;
    return $this;
  }

  /**
   * Function to get questions.
   *
   * @return Collection<int, Question>
   *  Return questions.
   */
  public function getQuestions(): Collection
  {
   return $this->questions;
  }

  /**
   * Function to add question of Exam.
   *
   * @param Question
   *
   * @return static
   *  Return instance of the exam.
   */
  public function addQuestion(Question $question): static
  {
    if (!$this->questions->contains($question)) {
      $this->questions->add($question);
      $question->setQuesId($this);
    }
    return $this;
  }

   /**
   * Function to remove question from Exam.
   *
   * @param Question
   *
   * @return static
   *  Return instance of the exam.
   */
  public function removeQuestion(Question $question): static
  {
    if ($this->questions->removeElement($question)) {
      // set the owning side to null (unless already changed)
      if ($question->getQuesId() === $this) {
        $question->setQuesId(null);
      }
    }
   return $this;
  }
}
