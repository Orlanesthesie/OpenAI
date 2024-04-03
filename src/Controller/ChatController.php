<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use App\Service\OpenAiService;
use Doctrine\ORM\EntityManagerInterface;
use OpenAI;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ChatController extends AbstractController
{

    #[Route('/', name: 'app_index')]
    public function index(Request $request, MessageRepository $messageRepository): Response
    {
        $messages = $messageRepository->findAll();

        return $this->render('chat/index.html.twig', [
            'controller_name' => 'ChatController',
            'messages' => $messages
        ]);
    }

    #[Route('/chat', name: 'app_chat')]
    public function chat(Request $request, EntityManagerInterface $entityManager)
    {
        $message = $request->request->get('messages');
        $response = OpenAiService::chat($message);

        $messagebdd = new Message();
        $messagebdd->setContent($message);
        $entityManager->persist($messagebdd);
        $entityManager->flush();

        $messagebdd = new Message();
        $messagebdd->setRole('assistant');
        $messagebdd->setContent($response);
        $entityManager->persist($messagebdd);
        $entityManager->flush();

        return $this->redirectToRoute('app_index', ['response' => $response]);
    }
}
