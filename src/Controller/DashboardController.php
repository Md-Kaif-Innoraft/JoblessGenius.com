<?php

namespace App\Controller;

use App\Entity\Exam;

use App\Entity\User;
use App\Entity\Profile;
use App\Entity\Question;
use App\Form\ProfileType;
use App\Entity\ExamResult;
use App\Entity\ExamApplication;
use App\Form\ExamApplicationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use function PHPUnit\Framework\isEmpty;

/**
 * Class Dashboard Controller for routing related to admin.
 */
class DashboardController extends AbstractController
{

  /**
   * @var object $em
   *  Entity manager interface.
   */
  private $em;

  /**
   * Constructor to initialize instace of class.
   */
  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
  }

  /**
   * Function index for routing to index page of dashboard.
   *
   * @return Response
   *  return respose to render dashboard index page.
   */
  #[Route('/dashboard', name: 'app_dashboard')]
  public function index(): Response
  {
    $this->denyAccessUnlessGranted(attribute: "IS_AUTHENTICATED_FULLY");
    return $this->render('dashboard/index.html.twig');
  }

  /**
   * Function profile for routing to profile page of dashboard.
   *
   * @param int $id
   *  Request from url.
   *
   * @return Response
   *  return respose to render profile page.
   */
  #[Route('/profile/{id}', name: 'app_profile')]
  public function pindexrofile(int $id): Response
  {
    $this->denyAccessUnlessGranted(attribute: "IS_AUTHENTICATED_FULLY");
    $data = $this->em->getRepository(Profile::class)->findOneBy(['user_id' =>$id]);
    return $this->render('dashboard/profile.html.twig', ['data' => $data]);
  }

  /**
   * Function create-profile for routing to create profile page of user.
   *
   * @param Request $request
   *  Request from url.
   *
   * @return Response
   *  return respose to render create profile page.
   */
  #[Route('/create-profile', name: 'app_create-profile')]
  public function creatProfile(Request $request): Response
  {
    // Create object of profile.
    $Profile = new Profile();
    // Create form of profile type.
    $form = $this->createForm(ProfileType::class, $Profile);
    // Handling form request.
    $form->handleRequest($request);
    // Check if form is submitted and valid.
    if ($form->isSubmitted() && $form->isValid())
    {
      $this->em->persist($Profile);
      $this->em->flush();
      $this->addFlash('message', 'Profile Created Successfully');
      // Redirect to dashboard.
      return $this->redirectToRoute('app_dashboard');
    }
    // Render create profile page with form.
    return $this->render('dashboard/create-profile.html.twig', [
      'form' => $form->createview()
    ]);
  }

  /**
   * Function editProfile for routing to editprofile page of user.
   *
   * @param Request $request
   *  Request from the url.
   * @param int $id
   *  Taking parameter from url.
   *
   * @return Response
   *  return respose to render create proile page.
   */
  #[Route('/editprofile/{id}', name: 'app_editprofile')]
  public function editPost(Request $request, int $id)
  {
    // Data from database of the current user.
    $data = $this->em->getRepository(Profile::class)->find($id);
    // Creating form.
    $form = $this->createForm(ProfileType::class, $data);
    // Handle form request.
    $form->handleRequest($request);
    // Check if the form is submitted and valid.
    if ($form->isSubmitted() && $form->isValid())
    {
      $this->em->persist($data);
      $this->em->flush();

      $this->addFlash('message', 'Data Updated Successfully');
      // Redirect to dashboard.
      return $this->redirectToRoute('app_dashboard');
    }
    // Render create profile page.
    return $this->render('dashboard/create-profile.html.twig', [
      'form' => $form->createView()
    ]);
  }

  /**
   * Function upcomingexam for routing to upcomingexam page of user.
   *
   * @return Response
   *  return respose to render upcomingexam page.
   */
  #[Route('/upcomingexam', name: 'app_upcomingexam')]
  public function upcomingExam(): Response
  {
    $this->denyAccessUnlessGranted(attribute: "IS_AUTHENTICATED_FULLY");
    // Data of all exam .
    $allExams = $this->em->getRepository(Exam::class)->findAll();
    // Data of all applied exams.
    $appliedExams = $this->em->getRepository(ExamApplication::class)->findAll();
    // Array to store data of upcoming exam.
    $upcomingExams = [];

    // Create an array of applied exam IDs
    $appliedExamIds = [];
    // For loop to traverse and get id of applied exams.
    foreach ($appliedExams as $appliedExam) {
      $appliedExamIds[] = $appliedExam->getExamId()->getId();
    }

    // Check each exam in $allExams and add to $upcomingExams if not applied
    foreach ($allExams as $exam) {
      if (!in_array($exam->getId(), $appliedExamIds))
      {
        $upcomingExams[] = $exam;
      }
    }
    // Render upcoming exam page.
    return $this->render('dashboard/upcomingExam.html.twig', ['data' => $upcomingExams]);
  }


  /**
   * Function appliedexam for routing to appliedexam page of user.
   *
   * @return Response
   *  return respose to render appliedexam page.
   */
  #[Route('/appliedexam', name: 'app_appliedexam')]
  public function appliedExam(): Response
  {
    $this->denyAccessUnlessGranted(attribute: "IS_AUTHENTICATED_FULLY");
    $id = $this->getUser()->getId();
    // Array to store exam names.
    $examsName = [];
    // Array to store exam status.
    $status = [];
    // Array to store owner names.
    $owner = [];
    // Array to store duration of exam.
    $duration = [];
    // Array to store exam ids.
    $examId = [];
    // Data of all applied exams.
    $appliedExam = $this->em->getRepository(ExamApplication::class)->findAll();
    for ($i=0; $i<count($appliedExam); $i++)
    {
      if ($id == $appliedExam[$i]->getUserId()->getId())
      {
        $examsName[] = $appliedExam[$i]->getExamId()->getTitle();
        $examId[] = $appliedExam[$i]->getExamId()->getId();
        $status[] = $appliedExam[$i]->getExamId()->getStatus();
        $owner[] = $appliedExam[$i]->getExamId()->getOwner();
        $duration[] = $appliedExam[$i]->getExamId()->getDuration();
      }
    }
    // Render applied Exam page.
    return $this->render('dashboard/appliedExam.html.twig',[
      'examsName' => $examsName,
      'status' => $status,
      'owner' => $owner,
      'duration' => $duration,
      'examId' => $examId
    ]);
  }

  /**
   * Function applyExam for routing to applyExam page of user.
   *
   * @param Request $request
   *  Request from the url.
   * @param int $id
   *  Taking id parameter from url.
   * @param int $examid
   *  Taking exam id from url.
   *
   * @return Response
   *  return respose to render applyExam page.
   */
  #[Route('/applyexam/{id}/{examid}', name: 'app_applyexam')]
  public function applyExam(Request $request, int $id, int $examid): Response
  {
    // Create object of ExamApplication.
    $ExamApplication = new ExamApplication();
    // Find user data from database.
    $user = $this->em->getRepository(User::class)->find($id);
    // Find exam data from database.
    $exam = $this->em->getRepository(Exam::class)->find($examid);

    // Set Exam id in Exam Application.
    $ExamApplication->setExamId($exam);
    // Set user id in Exam Application.
    $ExamApplication->setUserId($user);
    // Create form.
    $form = $this->createForm(ExamApplicationType::class, $ExamApplication);
    // Handle form request.
    $form->handleRequest($request);
    // Check if form is submitted and valid.
    if ($form->isSubmitted() && $form->isValid())
    {
      $this->em->persist($ExamApplication);
      $this->em->flush();

      $this->addFlash('message', 'Successfully, Applied for the Exam.');
      return $this->redirectToRoute('app_upcomingexam');
    }
    // Render apply exam page.
    return $this->render('dashboard/applyExam.html.twig', [
      'form' => $form->createview()
    ]);
  }

  /**
   * Function takeExamIns for routing to takeExamIns page of user.
   *
   * @param int $examid
   *  Taking exam id from url.
   *
   * @return Response
   *  return respose to render takeExamIns page.
   */
  #[Route('/takeExamIns/{examid}', name: 'app_takeExamIns')]
   function takeExamIns(int $examid): Response
  {
    // Render Take Exam instruction page.
    return $this->render('dashboard/takeExamIns.html.twig', [
      'examid' => $examid
    ]);
  }

  /**
   * Function takeExam for routing to takeExam page of user.
   *
   * @param int $examid
   *  Taking exam id from url.
   *
   * @return Response
   *  return respose to render takeExamI page.
   */
  #[Route('/takeExam/{examid}', name: 'app_takeExam')]
  public function takeExam(Request $request, $examid): Response
  {
    // Getting all question from database.
    $allQuestions = $this->em->getRepository(Question::class)->findAll();
    // Render takeExam page.
    return $this->render('dashboard/takeExam.html.twig',[
      'questions' => $allQuestions,
      'examId' => $examid
    ]);
  }

  /**
   * Function submitExam for routing to submitExam page of user.
   *
   * @param int $examid
   *  Taking exam id from url.
   *
   * @return Response
   *  return respose to render submitExam page.
   */
  #[Route('/submitExam/{examId}', name: 'app_submitExam')]
  public function submitExam(Request $request, $examId): Response
  {
    // Creating Object of Exam Result entity.
    $examResult = new ExamResult();
    $exam = $this->em->getRepository(Exam::class)->find($examId);
    $examResult->setExamId($exam);
    $examResult->setUserId($this->getUser());

    // Retrieve the submitted answers from the form
    $answers = $request->get('answers');$this->denyAccessUnlessGranted(attribute: "IS_AUTHENTICATED_FULLY");
    // Calculate marks based on correct answers

    $totalMarks = 0;
    if ($answers != null)
    {
      foreach ($answers as $questionId => $selectedOption)
      {
        $question = $this->em->getRepository(Question::class)->find($questionId);
        if ($question && $selectedOption === $question->getCorrectAns())
        {
          $totalMarks++;
        }
      }
    }

    $examResult->setResult($totalMarks);
    // Persist the exam results to the database
    $this->em->persist($examResult);
    $this->em->flush();
    // Render Exam Result page.
    $this->addFlash('message', 'Data Updated Successfully');
    return $this->redirectToRoute('app_examResult', [
    'result' => $totalMarks
    ]);
  }

  /**
   * Function examResult for routing to examResult page of user.
   *
   * @param Request $request
   *  Request from the url.
   * @param int $result
   *  Taking result parameter from url.
   *
   * @return Response
   *  return respose to render examResult page.
   */
  #[Route('/examResult/{result}', name: 'app_examResult')]
  public function examResult(Request $request, int $result): Response
  {
    // Render result page.
    return $this->render('dashboard/examResult.html.twig', [
      'result' => $result
    ]);
  }

  /**
   * Function result for routing to result page of user.
   *
   * @param Request $request
   *  Request from the url.
   *
   * @return Response
   *  return respose to render result page.
   */
  #[Route('/result', name: 'app_result')]
  public function result(Request $request): Response
  {
    // Id of current user.
    $id = $this->getUser()->getId();
    // Arrray to store result.
    $results = [];
    // Arrray to store names.
    $names =[] ;
    // Arrray to store owner.
    $owners = [];
    // Arrray to store total marks.
    $totalMarks = [];
    // Data of all the results.
    $allResults = $this->em->getRepository(ExamResult::class)->findAll();
    for($i=0; $i<count($allResults); $i++)
    {
      if ($allResults[$i]->getUserId()->getId() == $id)
      {
        $results[] = $allResults[$i]->getResult();
        $names[] = $allResults[$i]->getExamId()->getTitle();
        $owners[] = $allResults[$i]->getExamId()->getOwner();
        $totalMarks[] = $allResults[$i]->getExamId()->getTotalMarks();
      }
    }
    // Render the result page.
    return $this->render('dashboard/result.html.twig',[
      'results' => $results,
      'names' => $names,
      'totalMarks' => $totalMarks,
      'owners' => $owners
    ]
    );
  }
}
