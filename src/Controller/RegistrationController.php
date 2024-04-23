<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\AppAuthenticator;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

/**
 * Class RegistrationController for routing related to registration.
 */
class RegistrationController extends AbstractController
{

  /**
   * Constructor to initialize instace of class.
   */
  public function __construct(private EmailVerifier $emailVerifier)
  {
  }

  /**
   * Function register for routing to register page of user.
   *
   * @param Request $request
   *  Request from url.
   * @param UserPasswordHasherInterface $userPasswordHasher
   *  UserPasswordHasherInterface for hashing password.
   * @param Security $Security
   *  Security for authentication.
   * @param EntityManagerInterface $EntityManagerInterface
   *  EntityManagerInterface for data base queries.
   *
   * @return Response
   *  return respose to render register page.
   */
  #[Route('/register', name: 'app_register')]
  public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
  {
    // Check if user is alredy login.
    if ($this->getUser())
    {
      return $this->redirectToRoute(route: 'app_dashboard');
    }
    // Create object of User.
    $user = new User();
    // Create registration form.
    $form = $this->createForm(RegistrationFormType::class, $user);
    // Handle form request.
    $form->handleRequest($request);
    // Check if form is submitted and is valid.
    if ($form->isSubmitted() && $form->isValid())
    {
      // Encode the plain password.
      $user->setPassword(
          $userPasswordHasher->hashPassword(
            $user,
            $form->get('plainPassword')->getData()
          )
      );

      $entityManager->persist($user);
      $entityManager->flush();

      // Generate a signed url and email it to the user.
      $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
        (new TemplatedEmail())
          ->from(new Address('ansarimdkaif0@gmail.com', 'kaif'))
          ->to($user->getEmail())
          ->subject('Please Confirm your Email')
          ->htmlTemplate('registration/confirmation_email.html.twig')
      );

      // Do anything else you need here, like send an email.
      return $security->login($user, AppAuthenticator::class, 'main');
    }
    // Render register page.
    return $this->render('registration/register.html.twig', [
      'registrationForm' => $form,
    ]);
  }

  /**
   * Function verifyUserEmail for routing to verifyUserEmail of user.
   *
   * @param Request $request
   *  Request from url.
   * @param TranslatorInterface $translatorInterface
   *
   * @return Response
   *  return respose to render verifyUserEmail page.
   */
  #[Route('/verify/email', name: 'app_verify_email')]
  public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
  {
    $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

    // validate email confirmation link, sets User::isVerified=true and persists
    try
    {
      $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
    }
    catch (VerifyEmailExceptionInterface $exception)
    {
      $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));
      return $this->redirectToRoute('app_register');
    }

    // @TODO Change the redirect on success and handle or remove the flash message in your templates
    $this->addFlash('success', 'Your email address has been verified.');
    return $this->redirectToRoute('app_dashboard');
  }
}
