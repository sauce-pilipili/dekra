<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SuperAdminToMembersType;
use App\Form\UserEditType;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
     * @Route("/members/members", name="members_members")
     */
    public function members(Request $request,UserRepository $userRepository,PaginatorInterface $paginator): Response

    {
        $userAPAginer = $userRepository->findAll();

        $user = $paginator->paginate($userAPAginer,$request->query->getInt('page',1),6);
        return $this->render('members/members.html.twig',[
            'users' => $user
        ]);
    }
    /**
     * @Route("members/{id}/edit", name="members_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder): Response
    {

        $form = $this->createForm(SuperAdminToMembersType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->container->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
                $role[] = $form->get('roles')->getData();
                $user->setRoles($role);
//                $region[] = $form->get('region')->getData();
//                $user->setRegion($region);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('members_members');

        }

        return $this->render('members/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
