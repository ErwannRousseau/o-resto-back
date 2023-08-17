<?php

namespace App\Controller\Api;

use App\Entity\Reservation;
use App\Entity\User;
use App\Repository\ReservationRepository;
use App\Services\ReservationsMailer;
use Doctrine\ORM\EntityNotFoundException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


/**
 * @Route("/api/reservations", name="app_api_reservation_")
 */
class ReservationController extends CoreApiController
{
    /**
     * list all reservations
     *
     * @Route("",name="browse", methods={"GET"})
     *
     * @param ReservationRepository $reservationRepository
     * @return JsonResponse
     */
    public function browse(ReservationRepository $reservationRepository): JsonResponse
    {
        $allreservations = $reservationRepository->findAll();
        return $this->json200($allreservations, ["reservation_browse"]);
    }
    /**
     * @Route("/{id}", name="read", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function read($id, ReservationRepository $reservationRepository): JsonResponse
    {
        $reservation = $reservationRepository->find($id);
        // gestion of 404
        if ($reservation === null) {
            // ! API -> we don't have HTML
            return $this->json(
                [
                    "message" => "Cette reservation n'existe pas"
                ],
                // code status : 404
                Response::HTTP_NOT_FOUND
            );
        }
        return $this->json(
            $reservation,
            200,
            [],
            [
                "groups" =>
                [
                    "reservation_read"
                ]
            ]
        );
    }

    /**
     * add new reservation
     *
     * @Route("",name="add", methods={"POST"})
     *
     * @return JsonResponse
     */
    public function add(Request $request, SerializerInterface $serializerInterface, ReservationRepository $reservationRepository, ValidatorInterface $validatorInterface, ReservationsMailer $reservationsMailer)
    {

        $user = $this->getUser();

        // In request, I need the content
        $jsonContent = $request->getContent();

        try { // try to deserialiser
            $newReservation = $serializerInterface->deserialize($jsonContent, Reservation::class, 'json');
        } catch (EntityNotFoundException $e) {

            return $this->json("Denormalisation : " . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (Exception $exception) {

            return $this->json("JSON Invalide : " . $exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        $errors = $validatorInterface->validate($newReservation);
        // Have errors? 
        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $newReservation->setUser($user);


        $reservationRepository->add($newReservation, true);

        // $reservationsMailer->newReservationEmail($newReservation);


        return $this->json(

            $newReservation,
            //status 201 for created object
            Response::HTTP_CREATED,

            [],
            // the context because we serialize an object
            [
                "groups" =>
                [
                    // I use an existing group
                    "reservation_read",
                    "user_browse"
                ]
            ]
        );
    }



    /**
     * edit reservation
     *
     * @Route("/{id}",name="edit", requirements={"id"="\d+"}, methods={"PUT", "PATCH"})
     * 
     * 
     * @param int $id
     * @param Request $request 
     * @param SerializerInterface $serializerInterface
     * @param ReservationRepository $reservationRepository
     */
    public function edit($id, Request $request, SerializerInterface $serializerInterface, ReservationRepository $reservationRepository)
    {
        // Update Reservation
        // 1. take back a JSON content
        $jsonContent = $request->getContent();
        // 2. we cherch an existant reservation with id
        $reservation = $reservationRepository->find($id);
        // 3. deserialize and update the object
        $serializerInterface->deserialize(
            // content
            $jsonContent,
            // object type
            Reservation::class,
            // data format
            'json',
            // I want to POPULATE my reservation object 
            [AbstractNormalizer::OBJECT_TO_POPULATE => $reservation]
        );
        // 4. flush
        $reservationRepository->add($reservation, true);

        // return 200
        return $this->json($reservation, Response::HTTP_OK, [], ["groups" => ["reservation_read", "user_browse"]]);
    }

    /**
     * delete review
     * 
     *
     * @Route("/{id}",name="delete", requirements={"id"="\d+"}, methods={"DELETE"})
     * 
     */
    public function delete($id, ReservationRepository $reservationRepository)
    {
        $reservation = $reservationRepository->find($id);
        $reservationRepository->remove($reservation, true);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
