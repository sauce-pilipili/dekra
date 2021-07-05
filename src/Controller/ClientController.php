<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminToClientType;
use App\Form\UserEditType;
use App\Repository\UserRepository;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ClientController extends AbstractController
{
    /**
     * @Route("/client", name="client")
     */
    public function index(Request $request,UserRepository $userRepository,PaginatorInterface $paginator): Response
    {
        $clientAPAginer = $userRepository->findClient($this->getUser());
        $client = $paginator->paginate($clientAPAginer,$request->query->getInt('page',1),6);
        return $this->render('client/index.html.twig', [
            'clients' => $client,
        ]);
    }
    /**
     * @Route("/client/new", name="client_new", methods={"GET","POST"})
     */
    public function new(Request $request,UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(AdminToClientType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $role= [];
            $role[0]= "ROLE_CLIENT";
            $user->setRoles($role);
            $user->setPassword($passwordEncoder->encodePassword($user,'password'));
            $user->setRgpd(true);
            $user->setCreatedAt(new DateTime());
            $user->addAdminID($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success','le client a été pris en compte');
            return $this->redirectToRoute('client');
        }

        return $this->render('client/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}/edit", name="client_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder): Response
    {

            $form = $this->createForm(AdminToClientType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->container->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
            }
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success','le client a été pris en compte');
                return $this->redirectToRoute('client');


        }

        return $this->render('client/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
