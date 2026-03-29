<?php
namespace App\Controller;

use App\Repository\ReservationRepository;
use App\Form\AccountType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/dashboard')]
#[IsGranted('ROLE_USER')]
class UserController extends AbstractController
{
    #[Route('', name: 'app_user_dashboard')]
    public function index(ReservationRepository $reservationRepo): Response
    {
        $reservations = $reservationRepo->findBy(
            ['email' => $this->getUser()->getUserIdentifier()],
            ['createdAt' => 'DESC']
        );

        return $this->render('user/dashboard.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    #[Route('/account', name: 'app_user_account', methods: ['GET', 'POST'])]
    public function account(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Account updated successfully.');
            return $this->redirectToRoute('app_user_account');
        }

        return $this->render('user/account.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/reservation/{id}/cancel', name: 'app_user_reservation_cancel', methods: ['POST'])]
    public function cancel(Request $request, EntityManagerInterface $em, \App\Entity\Reservation $reservation): Response
    {
        // Make sure the reservation belongs to this user
        if ($reservation->getEmail() !== $this->getUser()->getUserIdentifier()) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isCsrfTokenValid('cancel' . $reservation->getId(), $request->request->get('_token'))) {
            // Give the seat back
            $event = $reservation->getEvent();
            $event->setSeats($event->getSeats() + 1);

            $em->remove($reservation);
            $em->flush();

            $this->addFlash('success', 'Reservation cancelled successfully.');
        }

        return $this->redirectToRoute('app_user_dashboard');
    }
}