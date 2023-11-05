<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\User;
use App\Entity\Invitation;


class InvitationController extends AbstractController
{

    /**
     * @Route("/api/invitation", methods={"GET"})
     */
    public function getInvitation()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $invitations = $entityManager->getRepository(Invitation::class)->findAll();
        $invitationData = [];
        foreach ($invitations as $invitation) {
            $invitationData[] = [
                'id' => $invitation->getId(),
                'sender' => $invitation->getSender(),
                'invited' => $invitation->getInvited(),
                'description' => $invitation->getDescription(),
                'status' => $invitation->getStatus(),
                'date' => $invitation->getDate()->format('Y-m-d H:i:s'), // Format the date as needed
            ];
        }
        $data = ['data' => $invitationData];
        return new JsonResponse($data);
    }

    
    /**
     * @Route("/api/invitation", methods={"POST"})
     */
    public function sendInvitation(Request $request)
    {

        $data = json_decode($request->getContent(), true);
       
        $senderId = $data['sender_id'];
        $invitedId = $data['invited_id'];
        $desc = $data['description'];

        $entityManager = $this->getDoctrine()->getManager();

        $sender = $entityManager->getRepository(User::class)->find($senderId);
        $invited = $entityManager->getRepository(User::class)->find($invitedId);

        if (!$sender || !$invited) {
            return new JsonResponse(['message' => 'Sender or Invited user not found'], 404);
        }

        $invitation = new Invitation();
        $invitation->setSender($sender->getId());
        $invitation->setInvited($invited->getId());        
        $invitation->setStatus('pending');
        $invitation->setDescription($desc);
        $invitation->setDate(new \DateTime());

        $entityManager->persist($invitation);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Invitation sent successfully'], 201);
    }

    /**
     * @Route("/api/invitation", methods={"GET", "POST", "PUT"})
     */
    public function cancelInvitation(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $invitationId = $data['invitation_id'];

        $entityManager = $this->getDoctrine()->getManager();
        $invitation = $entityManager->getRepository(Invitation::class)->find($invitationId);

        if (!$invitation) {
            return new JsonResponse(['message' => 'Invitation not found'], 404);
        }

        $invitation->setStatus('canceled');
        $entityManager->flush();

        return new JsonResponse(['message' => 'Invitation canceled successfully'], 200);
    }

    /**
     * @Route("/api/accept_invitation", methods={"GET", "POST", "PUT"})
     */
    public function respondInvitation(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $invitationId = $data['invitation_id'];
        $response = $data['response'];

        if (!in_array($response, ['accept', 'decline'])) {
            return new JsonResponse(['message' => 'Invalid response'], 400);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $invitation = $entityManager->getRepository(Invitation::class)->find($invitationId);

        if (!$invitation) {
            return new JsonResponse(['message' => 'Invitation not found'], 404);
        }

        $invitation->setStatus($response);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Invitation responded successfully'], 200);
    }
}
