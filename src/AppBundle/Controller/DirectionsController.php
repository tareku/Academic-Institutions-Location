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

class DirectionsController extends Controller
{
  /**
   * @Route("/common/directions", name="directionspage")
   */
  public function directionAction()
  {
    $estabs = $this->getDoctrine()
      ->getRepository('AppBundle:Institution')
      ->findAll();

    return $this->render('common/directions.html.twig', array(
      'estabs' => $estabs,
    ));
  }
}
