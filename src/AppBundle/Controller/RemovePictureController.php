<?php

namespace AppBundle\Controller;
use AppBundle\Entity\Institution;
use AppBundle\Entity\Establishment;
use AppBundle\Entity\Gallery;
use AppBundle\Entity\Historique;
use AppBundle\Entity\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class RemovePictureController extends Controller
{
               /**
                * @Route("/common/remove/{id}", name="removepage")
                */
               public function removeAction($id, Request $request)
               {
                 $em = $this->getDoctrine()->getManager();
                 $gallery = $em->getRepository('AppBundle:Gallery')->find($id);

                 $em->remove($gallery);
                 $em->flush();

                 $this->addFlash(
                   'notice',
                   'Picture Removed'
                 );
                //  return $this->redirectToRoute('listpage');
                 return $this->redirect($request->headers->get('referer'));
               }
}
