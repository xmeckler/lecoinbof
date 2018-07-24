<?php

namespace App\Controller;

use App\Entity\Advertisement;
use App\Entity\Message;
use App\Entity\User;
use App\Form\AdvertisementType;
use App\Form\MessageType;
use App\Repository\AdvertisementRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/advertisement")
 */
class AdvertisementController extends Controller
{
    /**
     * @Route("/", name="advertisement_index", methods="GET")
     */
    public function index(AdvertisementRepository $advertisementRepository): Response
    {
        return $this->render('advertisement/index.html.twig', [
            'advertisements' => $advertisementRepository->findAll()]);
    }

    /**
     * @Route("/author/{username}", name="advertisement_authorList", methods="GET")
     * @ParamConverter("user", options={"mapping": {"username": "username"}})
     */
    public function listUserAds(AdvertisementRepository $advertisementRepository, User $user): Response
    {
        return $this->render('advertisement/listUserAds.html.twig', [
            'advertisements' => $advertisementRepository->findBy(['author' => $user]),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/requests/{username}", name="advertisement_user_requests", methods="GET")
     * @ParamConverter("user", options={"mapping": {"username": "username"}})
     */
    public function listRepliedAds(User $user): Response
    {
        $messages = $user->getMessages();
        $advertisements = [];
        foreach ($messages as $message) {
            $advertisement = $message->getAdvertisement();
            if ($message->getReplyToMessage() === null && !in_array($advertisement, $advertisements)) {
                $advertisements[] = $advertisement;
            }
        }

        return $this->render('advertisement/listUserRequests.html.twig', [
            'advertisements' => $advertisements,
            'user' => $user,
        ]);
    }

    /**
     * @Route("/new", name="advertisement_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $advertisement = new Advertisement();
        $advertisement->setAuthor($this->getUser());
        $form = $this->createForm(AdvertisementType::class, $advertisement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($advertisement);
            $em->flush();

            return $this->redirectToRoute('advertisement_index');
        }

        return $this->render('advertisement/new.html.twig', [
            'advertisement' => $advertisement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="advertisement_show", methods="GET|POST")
     */
    public function show(Request $request, Advertisement $advertisement): Response
    {
        $user = $this->getUser();
        $message = new Message();
        $message->setAuthor($user);
        $message->setAdvertisement($advertisement);
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            $this->addFlash('success', 'Votre message a été transmis !');

            return $this->redirectToRoute('advertisement_show', ['id' => $advertisement->getId()]);
        }

        return $this->render('advertisement/show.html.twig', [
            'advertisement' => $advertisement,
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="advertisement_edit", methods="GET|POST")
     */
    public function edit(Request $request, Advertisement $advertisement): Response
    {
        $form = $this->createForm(AdvertisementType::class, $advertisement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('advertisement_edit', ['id' => $advertisement->getId()]);
        }

        return $this->render('advertisement/edit.html.twig', [
            'advertisement' => $advertisement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="advertisement_delete", methods="DELETE")
     */
    public function delete(Request $request, Advertisement $advertisement): Response
    {
        if ($this->isCsrfTokenValid('delete'.$advertisement->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($advertisement);
            $em->flush();
        }

        return $this->redirectToRoute('advertisement_index');
    }
}
