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
use App\Service\ProtectAdminRoutes;
use function PHPUnit\Framework\isEmpty;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * Class Dashboard Controller for routing related to admin.
 */
class DashboardController extends AbstractController
{

  private $allowAdminRoutes;

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
    $this->allowAdminRoutes = new ProtectAdminRoutes($this->em);

  }

  /**
   * Function index for routing to index page of dashboard.
   *
   * @Routh path (/dashboard).
   *
   * @return Response
   *  return respose to render dashboard index page.
   */
  #[Route('/dashboard', name: 'app_dashboard')]
  public function index(): Response
  {
    // Protecting the dashboard routes by checking user role.
    if ($this->allowAdminRoutes->isAdmin($this->getUser()) === TRUE)
    {
      // Adding a flash message on redirectinf to dashboard.
      $this->addFlash('success', 'Redirected to dashboard!');
      return $this->redirectToRoute('app_admin');
    }
    $this->denyAccessUnlessGranted(attribute: "IS_AUTHENTICATED_FULLY");
    return $this->render('dashboard/index.html.twig');
  }

  /**
   * Method profile for routing to profile page of dashboard.
   *
   * @Routh path (/profile/{id}).
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
    // Protecting the dashboard routes by checking user role.
    if ($this->allowAdminRoutes->isAdmin($this->getUser()) === TRUE)
    {
      // Adding a flash message on redirectinf to dashboard.
      $this->addFlash('success', 'Redirected to dashboard!');
      return $this->redirectToRoute('app_admin');
    }
    $this->denyAccessUnlessGranted(attribute: "IS_AUTHENTICATED_FULLY");
    $data = $this->em->getRepository(Profile::class)->findOneBy(['user_id' =>$id]);
    return $this->render('dashboard/profile.html.twig', ['data' => $data]);
  }

  /**
   * Method create-profile for routing to create profile page of user.
   *
   * @Routh path (/create-profile).
   *
   * @param Request $request
   *  Request from url.
   *
   * @return Response
   *  return respose to render create profile page.
   */
  #[Route('/create-profile', name: 'app_create-profile')]
  public function creatProfile(Request $request, SluggerInterface $slugger): Response
  {
    // Protecting the dashboard routes by checking user role.
    if ($this->allowAdminRoutes->isAdmin($this->getUser()) === TRUE)
    {
      // Adding a flash message on redirectinf to dashboard.
      $this->addFlash('success', 'Redirected to dashboard!');
      return $this->redirectToRoute('app_admin');
    }
    // Create object of profile.
    $Profile = new Profile();
    // Create form of profile type.
    $form = $this->createForm(ProfileType::class, $Profile);
    // HanFunctiondling form request.
    $form->handleRequest($request);
    // Check if form is submitted and valid.
    if ($form->isSubmitted() && $form->isValid())
    {
      $photoFile = $form->get('Image')->getData();
      // this condition is needed because the 'brochure' field is not required
      // so the PDF file must be processed only when a file is uploaded
      if ($photoFile) {
        $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $photoFile->guessExtension();
        // Move the file to the directory where brochures are stored
        try {
          $photoFile->move(
            $this->getParameter('photo_directory'),
            $newFilename
          );
      }
      catch (FileException $e) {
        echo "wrong hai".$e->getMessage();
      }

      $Profile->setImage($newFilename);
      }
      // dd($Profile);
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
   * Method editProfile for routing to editprofile page of user.
   *
   * @param Request $request
   *  Request from the url.
   *
   * @return Response
   *  return respose to render create proile page.
   */
  #[Route('/editprofile', name: 'app_editprofile')]
  public function editPost(Request $request)
  {
    $id = $this->getUser()->getId();
    // dd($id);
    // Protecting the dashboard routes by checking user role.
    if ($this->allowAdminRoutes->isAdmin($this->getUser()) === TRUE)
    {
      // Adding a flash message on redirectinf to dashboard.
      $this->addFlash('success', 'Redirected to dashboard!');
      return $this->redirectToRoute('app_admin');
    }

    // Data from database of the current user.
    $data = $this->em->getRepository(Profile::class)->findOneBy(['user_id' => $id]);
    // dd($data);
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
    $id = $this->getUser()->getId();
    // Protecting the dashboard routes by checking user role.
    if ($this->allowAdminRoutes->isAdmin($this->getUser()) === TRUE)
    {
      // Adding a flash message on redirectinf to dashboard.
      $this->addFlash('success', 'Redirected to dashboard!');
      return $this->redirectToRoute('app_admin');
    }
    $this->denyAccessUnlessGranted(attribute: "IS_AUTHENTICATED_FULLY");
    // Data of all upcoming exams .
    $upcomingExams = $this->service->getUpcomingExam($id);
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
    // Protecting the dashboard routes by checking user role.
    if ($this->allowAdminRoutes->isAdmin($this->getUser()) === TRUE)
    {
      // Adding a flash message on redirectinf to dashboard.
      $this->addFlash('success', 'Redirected to dashboard!');
      return $this->redirectToRoute('app_admin');
    }
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
    // Protecting the dashboard routes by checking user role.
    if ($this->allowAdminRoutes->isAdmin($this->getUser()) === TRUE)
    {
      // Adding a flash message on redirectinf to dashboard.
      $this->addFlash('success', 'Redirected to dashboard!');
      return $this->redirectToRoute('app_admin');
    }
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
    // Protecting the dashboard routes by checking user role.
    if ($this->allowAdminRoutes->isAdmin($this->getUser()) === TRUE)
    {
      // Adding a flash message on redirectinf to dashboard.
      $this->addFlash('success', 'Redirected to dashboard!');
      return $this->redirectToRoute('app_admin');
    }
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
    // Protecting the dashboard routes by checking user role.
    if ($this->allowAdminRoutes->isAdmin($this->getUser()) === TRUE)
    {
      // Adding a flash message on redirectinf to dashboard.
      $this->addFlash('success', 'Redirected to dashboard!');
      return $this->redirectToRoute('app_admin');
    }
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
    // Protecting the dashboard routes by checking user role.
    if ($this->allowAdminRoutes->isAdmin($this->getUser()) === TRUE)
    {
      // Adding a flash message on redirectinf to dashboard.
      $this->addFlash('success', 'Redirected to dashboard!');
      return $this->redirectToRoute('app_admin');
    }
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
    // Protecting the dashboard routes by checking user role.
    if ($this->allowAdminRoutes->isAdmin($this->getUser()) === TRUE)
    {
      // Adding a flash message on redirectinf to dashboard.
      $this->addFlash('success', 'Redirected to dashboard!');
      return $this->redirectToRoute('app_admin');
    }
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
    // Protecting the dashboard routes by checking user role.
    if ($this->allowAdminRoutes->isAdmin($this->getUser()) === TRUE)
    {
      // Adding a flash message on redirectinf to dashboard.
      $this->addFlash('success', 'Redirected to dashboard!');
      return $this->redirectToRoute('app_admin');
    }
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

  #[Route('/resultLoadMore', name: 'app_resultLoadMore')]
  public function resultLoadMore(Request $request): Response
  {
    $offset = $request->get('offset', 0);
    $limit = 5;
    $allResults = $this->em->getRepository(ExamResult::class)->findBy([], null,$limit, $offset);
    // dd($allResults);
    foreach ($allResults as $result) {
      $results[] = [
        'result' => $result->getResult(),
        'names' => $result->getExamId()->getTitle(),
        'owners' => $result->getExamId()->getOwner(),
        'totalMarks' => $result->getExamId()->getTotalMarks()
      ];
    }
    // // Render the result page.
    return new JsonResponse(['results' => $results]);
  }
}
