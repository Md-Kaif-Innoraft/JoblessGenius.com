<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Question of entity type to set and get data in entity.
 */
#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{

  /**
   * @var integer $id
   *  It stores Id of Question Entity for every single question.
   */
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  /**
   * @var string $ques
   *  It stores question of Exam Entity for every single question.
   */
  #[ORM\Column(length: 255)]
  private ?string $ques = null;

  /**
   * @var string $opt1
   *  It stores answer option of question for every single question.
   */
  #[ORM\Column(length: 255)]
  private ?string $opt1 = null;

  /**
   * @var string $opt2
   *  It stores answer option of question for every single question.
   */
  #[ORM\Column(length: 255)]
  private ?string $opt2 = null;

  /**
   * @var string $opt3
   *  It stores answer option of question for every single question.
   */
  #[ORM\Column(length: 255)]
  private ?string $opt3 = null;

  /**
   * @var string $opt4
   *  It stores answer option of question for every single question.
   */
  #[ORM\Column(length: 255)]
  private ?string $opt4 = null;

  /**
   * @var string $correct_ans
   *  It stores correct answer option of question for every single question.
   */
  #[ORM\Column(length: 255)]
  private ?string $correct_ans = null;

  /**
   * @var int $ques_id
   *  It stores question id of question for every single question.
   */
  #[ORM\ManyToOne(inversedBy: 'questions')]
  #[ORM\JoinColumn(nullable: false)]
  private ?Exam $ques_id = null;

  /**
   * Function to get Id of question.
   *
   * @return int
   *  Returns Id of question.
   */
  public function getId(): ?int
  {
    return $this->id;
  }

  /**
   * Function to get question of Exam.
   *
   * @return string
   *  Returns question of exam.
   */
  public function getQues(): ?string
  {
    return $this->ques;
  }

  /**
   * Function to set question of Exam.
   *
   * @param string $ques
   *
   * @return static
   *  Returns instance of current class.
   */
  public function setQues(string $ques): static
  {
    $this->ques = $ques;
    return $this;
  }

  /**
   * Function to get option1 of answer of question.
   *
   * @return string
   *  Returns option1 of answer of question.
   */
  public function getOpt1(): ?string
  {
    return $this->opt1;
  }

  /**
   * Function to set option1 of answer of question.
   *
   * @param string $opt1
   *
   * @return static
   *  Returns instance of current class.
   */
  public function setOpt1(string $opt1): static
  {
    $this->opt1 = $opt1;
    return $this;
  }

  /**
   * Function to get option2 of answer of question.
   *
   * @return string
   *  Returns option2 of answer of question.
   */
  public function getOpt2(): ?string
  {
    return $this->opt2;
  }

  /**
   * Function to set option2 of answer of question.
   *
   * @param string $opt2
   *
   * @return static
   *  Returns instance of current class.
   */
  public function setOpt2(string $opt2): static
  {
    $this->opt2 = $opt2;
    return $this;
  }

  /**
   * Function to get option2 of answer of question.
   *
   * @return string
   *  Returns option3 of answer of question.
   */
  public function getOpt3(): ?string
  {
    return $this->opt3;
  }

  /**
   * Function to set option3 of answer of question.
   *
   * @param string $opt3
   *
   * @return static
   *  Returns instance of current class.
   */
  public function setOpt3(string $opt3): static
  {
    $this->opt3 = $opt3;
    return $this;
  }

  /**
   * Function to get option4 of answer of question.
   *
   * @return string
   *  Returns option4 of answer of question.
   */
  public function getOpt4(): ?string
  {
    return $this->opt4;
  }

  /**
   * Function to set option4 of answer of question.
   *
   * @param string $opt4
   *
   * @return static
   *  Returns instance of current class.
   */
  public function setOpt4(string $opt4): static
  {
    $this->opt4 = $opt4;
    return $this;
  }

  /**
   * Function to get correct answer of question.
   *
   * @return string
   *  Returns correct answer of question.
   */
  public function getCorrectAns(): ?string
  {
    return $this->correct_ans;
  }

  /**
   * Function to set currect answer of question.
   *
   * @param string $correct_ans
   *
   * @return static
   *  Returns instance of current class.
   */
  public function setCorrectAns(string $correct_ans): static
  {
    $this->correct_ans = $correct_ans;
    return $this;
  }

  /**
   * Function to get option id of question.
   *
   * @return string
   *  Returns option id of question.
   */
  public function getQuesId(): ?exam
  {
    return $this->ques_id;
  }

  /**
   * Function to set question id of question.
   *
   * @param string $ques_id
   *
   * @return static
   *  Returns instance of current class.
   */
  public function setQuesId(?exam $ques_id): static
  {
    $this->ques_id = $ques_id;
    return $this;
  }
}
