<?php

namespace App\Controller;

use App\Entity\Exam;

use App\Entity\User;
use App\Entity\Profile;
use App\Entity\Question;
use App\Form\ProfileType;
use App\Entity\ExamResult;
use App\Service\MyService;
use App\Entity\ExamApplication;
use App\Form\ExamApplicationType;
use function PHPUnit\Framework\isEmpty;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

  private $service;

  /**
   * Constructor to initialize instace of class.
   */
  public function __construct(EntityManagerInterface $em, MyService $service)
  {
    $this->em = $em;
    $this->service = $service;
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
   * Method profile for routing to profile page of dashboard.
   *
   * @param int $id
   *  Request from url.
   *
   * @return Response
   *  return respose to render profile page.
   */
  #[Route('/profile/{id}', name: 'app_profile')]
  public function profile(int $id): Response
  {
    $this->denyAccessUnlessGranted(attribute: "IS_AUTHENTICATED_FULLY");
    $data = $this->em->getRepository(Profile::class)->findOneBy(['user_id' =>$id]);
    return $this->render('dashboard/profile.html.twig', ['data' => $data]);
  }

  /**
   * Method create-profile for routing to create profile page of user.
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
    // HanFunctiondling form request.
    $form->handleRequest($request);
    // Check if form is submitted and valid.
    if ($form->isSubmitted() && $form->isValid())
    {
      // Persist the data to the database.
      $this->service->setDataBase($Profile);
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
   * Method editProfile for routing to editprofile page of user.
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
      // Persist the data to the database
      $this->service->setDataBase($data);
      // Flash message upon successfully data updated.
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
   * Method upcomingexam for routing to upcomingexam page of user.
   *
   * @return Response
   *  return respose to render upcomingexam page.
   */
  #[Route('/upcomingexam', name: 'app_upcomingexam')]
  public function upcomingExam(): Response
  {
    $this->denyAccessUnlessGranted(attribute: "IS_AUTHENTICATED_FULLY");
    // Data of all upcoming exams .
    $upcomingExams = $this->service->getUpcomingExam();
    // Render upcoming exam page.
    return $this->render('dashboard/upcoming-exam.html.twig', ['data' => $upcomingExams]);
  }

  /**
   * Method appliedexam for routing to appliedexam page of user.
   *
   * @return Response
   *  return respose to render appliedexam page.
   */
  #[Route('/appliedexam', name: 'app_appliedexam')]
  public function appliedExam(): Response
  {
    $this->denyAccessUnlessGranted(attribute: "IS_AUTHENTICATED_FULLY");
    $id = $this->getUser()->getId();
    // Getting Details of Applied Exams.
    [$examsName, $status, $owner, $duration, $examId] = $this->service->appliedExam($id);
    // Render applied Exam page.
    return $this->render('dashboard/applied-exam.html.twig',[
      'examsName' => $examsName,
      'status' => $status,
      'owner' => $owner,
      'duration' => $duration,
      'examId' => $examId
    ]);
  }

  /**
   * Method applyExam for routing to applyExam page of user.
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
    // Set Exam id and User id in ExamApplication.
    $this->service->setExamApplicationData($id, $examid, $ExamApplication);

    // Create form.
    $form = $this->createForm(ExamApplicationType::class, $ExamApplication);
    // Handle form request.
    $form->handleRequest($request);
    // Check if form is submitted and valid.
    if ($form->isSubmitted() && $form->isValid())
    {
      // Persist the data to the database
      $this->service->setDataBase($ExamApplication);
      $this->addFlash('message', 'Successfully, Applied for the Exam.');
      return $this->redirectToRoute('app_upcomingexam');
    }
    // Render apply exam page.
    return $this->render('dashboard/apply-exam.html.twig', [
      'form' => $form->createview()
    ]);
  }

  /**
   * Method takeExamIns for routing to takeExamIns page of user.
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
    return $this->render('dashboard/take-exam-ins.html.twig', [
      'examid' => $examid
    ]);
  }

  /**
   * Method takeExam for routing to takeExam page of user.
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
    return $this->render('dashboard/take-exam.html.twig',[
      'questions' => $allQuestions,
      'examId' => $examid
    ]);
  }

  /**
   * Method submitExam for routing to submitExam page of user.
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
    $answers = $request->get('answers');
    $this->denyAccessUnlessGranted(attribute: "IS_AUTHENTICATED_FULLY");

    // Calculate marks based on correct answers
    $totalMarks = $this->service->calculateMarks($answers);
    $examResult->setResult($totalMarks);

    // Persist the exam results to the database
    $this->service->setDataBase($examResult);

    // Render Exam Result page.
    $this->addFlash('message', 'Data Updated Successfully');
    return $this->redirectToRoute('app_examResult', [
    'result' => $totalMarks
    ]);
  }

  /**
   * Method examResult for routing to examResult page of user.
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
    return $this->render('dashboard/exam-result.html.twig', [
      'result' => $result
    ]);
  }

  /**
   * Method result for routing to result page of user.
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

    // Getting Data of results.
    [$results, $names, $owners, $totalMarks] = $this->service->getResult($id);

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
