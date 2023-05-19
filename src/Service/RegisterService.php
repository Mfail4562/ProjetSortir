<?php

    namespace App\Service;

    use App\Entity\Travel;
    use App\Entity\User;
    use App\Repository\TravelRepository;
    use DateTime;
    use DateTimeZone;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\Request;

    class RegisterService
    {

        public function RegisterToTravel(
            EntityManagerInterface $entityManager,
                                   $id,
            TravelRepository       $travelRepository,
            User                   $user,
            Request                $request,
            string                 $message
        ): void
        {
            $travelToRegister = $travelRepository->find($id);

            $registered = false;


            $travelToRegister = $travelRepository->find($id);


            $statusId = $travelToRegister->getStatus()->getId();

            if ($statusId != 2 && $travelToRegister->getLeader()->getId() != $user->getId()) {
                $request->getSession()->getFlashBag()->add('warning', 'STATUS ERROR : You cannot be register to this travel it is not open for registration .');
            } else {
                if ($travelToRegister->getLimitDateSubscription() < new DateTime()) {
                    $request->getSession()->getFlashBag()->add('warning', 'CLOSURE ERROR : You cannot be register to this travel it is not open for registration .');
                } else {
                    foreach ($travelToRegister->getSubscriptionedTravelers() as $traveler) {
                        if ($traveler->getUserIdentifier() === $user->getUserIdentifier()) {
                            $request->getSession()->getFlashBag()->add('warning', 'ALREADY REGISTERED ERROR : You have already been registered for this travel');
                            $registered = true;
                        }
                    }
                    if (!$registered) {
                        $nbTravelers = count($travelToRegister->getSubscriptionedTravelers());
                        $maxtraveler = $travelToRegister->getNbMaxTraveler();
                        if ($nbTravelers >= $maxtraveler) {
                            $request->getSession()->getFlashBag()->add('warning', 'TRAVELERS ERROR : You cannot be register to this travel : the maximum travelers has been reached');
                        } else {
                            $travelToRegister->addSubscriptionedTraveler($user);
                            $entityManager->persist($travelToRegister);
                            $entityManager->flush();
                            $request->getSession()->getFlashBag()->add('success', 'You have been registered for this travel');

                            $this->addToTxtFollowing($message, $user, $travelToRegister);
                        }
                    }
                }

            }


        }


        public function addToTxtFollowing(string $message, User $user, Travel $travel): void
        {
            if (str_contains($message, "désinscription")) {
                file_put_contents("following_travel_file/following_register_travel_id_" . $travel->getId(), $message . $user->getPseudo() . " se désinscrit du voyage : " . $travel->getName() . " --> id : " . $travel->getId() . " : " . date_format(new \DateTime('now', new DateTimeZone('Europe/Paris')), 'd/m/y H:i') . "\n", FILE_APPEND);
            } else {
                file_put_contents("following_travel_file/following_register_travel_id_" . $travel->getId(), $message . $user->getPseudo() . " s'inscrit au voyage : " . $travel->getName() . " --> id : " . $travel->getId() . " : " . date_format(new \DateTime('now', new DateTimeZone('Europe/Paris')), 'd/m/y H:i') . "\n", FILE_APPEND);
            }
        }

    }