<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }

    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            // Set user as NOT verified initially
            $user->setIsVerified(true); // Set to false in production!

            $entityManager->persist($user);
            $entityManager->flush();

            // Try to send email
            try {
                $this->emailVerifier->sendEmailConfirmation(
                    'app_verify_email',
                    $user,
                    (new TemplatedEmail())
                        ->from(new Address('jenzribouthayna18@gmail.com', 'EventReserve'))
                        ->to((string) $user->getEmail())
                        ->subject('Please Confirm your Email')
                        ->htmlTemplate('registration/confirmation_email.html.twig')
                );

                $this->addFlash('success', 'Registration successful! Please check your email to verify your account.');

            } catch (\Exception $e) {
                // Log error
                $this->addFlash('warning', 'Account created but email could not be sent. Please contact support to verify your account.');

                // For development only - auto verify
                if ($_ENV['APP_ENV'] === 'dev') {
                    $user->setIsVerified(true);
                    $entityManager->flush();
                    $this->addFlash('info', 'Development mode: Account auto-verified. You can log in now.');
                }
            }

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Get user ID from the request
        $id = $request->query->get('id');

        if (!$id) {
            $this->addFlash('error', 'Verification link is invalid.');
            return $this->redirectToRoute('app_register');
        }

        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            $this->addFlash('error', 'User not found.');
            return $this->redirectToRoute('app_register');
        }

        // validate email confirmation link
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());
            return $this->redirectToRoute('app_register');
        }

        $this->addFlash('success', 'Your email address has been verified. You can now log in.');
        return $this->redirectToRoute('app_login');
    }
}