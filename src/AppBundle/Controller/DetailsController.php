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

class DetailsController extends Controller
{
    /**
     * @Route("/common/details/{id}", name="detailspage")
     */
    public function detailsAction($id)
    {
      $estab = $this->getDoctrine()
      ->getRepository('AppBundle:Institution')
      ->find($id);

      $now = new\DateTime('now');
      $user = $this->getUser()->getId();
      $historique = new Historique;
      $historique->setUser($user);
      $historique->setEstablishment($estab->getName());
      $historique->setDate($now);

      $em = $this->getDoctrine()->getManager();
      $em->persist($historique);
      $em->flush();

        return $this->render('common/details.html.twig', array(
          'estab' => $estab,
        ));
    }
}
