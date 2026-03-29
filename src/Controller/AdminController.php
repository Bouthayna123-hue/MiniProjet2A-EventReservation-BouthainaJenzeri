<?php
// src/Controller/AdminController.php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('', name: 'app_admin_dashboard')]
    public function index(EventRepository $eventRepo, ReservationRepository $reservationRepo): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'totalEvents' => count($eventRepo->findAll()),
            'totalReservations' => count($reservationRepo->findAll()),
            'recentReservations' => $reservationRepo->findBy([], ['createdAt' => 'DESC'], 5),
        ]);
    }
}