<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\Reservation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $event = new Event();
            $event->setTitle("Event $i");
            $event->setDescription("Description for event $i");
            $event->setDate(new \DateTime('+' . $i . ' days'));
            $event->setLocation("City $i");
            $event->setSeats(100);
            $event->setImage("image$i.jpg");

            $manager->persist($event);

            // create reservations
            for ($j = 1; $j <= 3; $j++) {
                $reservation = new Reservation();
                $reservation->setEvent($event);
                $reservation->setName("User $j");
                $reservation->setEmail("user$j@test.com");
                $reservation->setPhone("123456$j");
                $reservation->setCreatedAt(new \DateTimeImmutable());

                $manager->persist($reservation);
            }
        }

        $manager->flush();
    }
}