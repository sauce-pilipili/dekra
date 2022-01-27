<?php

namespace App\Controller;

use App\Entity\Reference;
use App\Form\ReferenceType;
use App\Repository\ReferenceRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("is_granted('ROLE_SUPER_ADMIN')")
 * @Route("/reference")
 */
class ReferenceController extends AbstractController
{
    /**
     * @Route("/edit/", name="reference_ajax_edit", methods={"POST"})
     */
    public function editAjax(Request $request, ReferenceRepository $referenceRepository)
    {
        $ref=$request->request->get('input');
        $refAvalider = $referenceRepository->find($ref);
        $refAvalider->setComplet(1);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('call_center');
    }
}
