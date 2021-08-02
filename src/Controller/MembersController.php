<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SuperAdminToMembersType;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class MembersController
 * @package App\Controller
 * @Security("is_granted('ROLE_SUPER_ADMIN')")
 */
class MembersController extends AbstractController
{
    /**
     * @Route("/members", name="members")
     */
    public function index(): Response
    {
        return $this->render('members/index.html.twig', [
            'controller_name' => 'MembersController',
        ]);
    }

    /**
     * @Route("/members/members", name="members_members", methods={"GET","POST"})
     */
    public function members(Request $request, UserRepository $userRepository, PaginatorInterface $paginator): Response

    {   // chargement de tous les membres
        $userAPAginer = $userRepository->findAll();
        // recherche du membre en ajax
        if ($request->isXmlHttpRequest()) {
            $data = $request->request->get('search');

            $users = $userRepository->findBySearch($data);
            return new JsonResponse([
                'content' => $this->renderView('include/_membersContent.html.twig', compact('users')),
            ]);

        }
        $user = $paginator->paginate($userAPAginer, $request->query->getInt('page', 1), 6);
        return $this->render('members/members.html.twig', [
            'users' => $user,
        ]);
    }

    /**
     * @Route("members/{id}/edit", name="members_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $rolesSiPasDeChangement = $user->getRoles();
        $form = $this->createForm(SuperAdminToMembersType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->container->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
                if($form->get('roles')->getData() === "change"){
                    $user->setRoles($rolesSiPasDeChangement);
                }else {
                    $role[] = $form->get('roles')->getData();
                    $user->setRoles($role);
                }
            }
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'La fiche membres a été modifiée avec succès !');
            return $this->redirectToRoute('members_members');

        }

        return $this->render('members/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
