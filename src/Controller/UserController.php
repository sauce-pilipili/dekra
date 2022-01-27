<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminToClientType;
use App\Form\UserEditType;
use App\Form\UserType;
use App\Repository\UserRepository;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


/**
 * @Route("/user")
 * @Security("is_granted('ROLE_USER')")
 */
class UserController extends AbstractController
{

//    valide pour v2
    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $role[] = $form->get('roles')->getData();
            $user->setRoles($role);
            $user->setRgpd(true);
            $user->setCreatedAt(new DateTime());
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('members_members');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    //    valide pou v2
    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit($id,Request $request, User $user,UserRepository $userRepository,UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = $userRepository->find($id);
        if ($user != $this->getUser()){
            $this->addFlash('danger', 'La ressource que vous essayez d\'atteindre ne vous est pas autorisée');
            return $this->render('dashboard_controler/index.html.twig');
        }
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Votre profil a été modifié avec succès');
            return $this->redirectToRoute('dashboard_controler');
        }
        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    //    valide pou v2
    /**
     * @Route("/{id}/delete/user", name="user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user): Response
    {

        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }
        return $this->redirectToRoute('members_members');
    }

    //    valide pou v2
    /**
     * @Route("/{id}/delete/client", name="client_delete", methods={"POST"})
     */
    public function deleteClient(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }
        return $this->redirectToRoute('client');
    }
}
