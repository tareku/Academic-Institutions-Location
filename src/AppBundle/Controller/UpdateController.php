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

class UpdateController extends Controller
{
           /**
            * @Route("/backend/update/{id}", name="updatepage")
            */
           public function updateAction($id, Request $request)
           {
               $estab = $this->getDoctrine()
                 ->getRepository('AppBundle:Institution')
                 ->find($id);

                 $form = $this->createFormBuilder($estab)
                   ->add('name', TextType::class, array('attr' => array('class' => 'form-control input-lg margin-buttom-10', 'placeholder' => 'Institution name')))
                   ->add('type', ChoiceType::class, array('choices' => array('Faculté' => 'Faculté', 'Logement universitaire' => 'Logement universitaire', 'Restaurant universitaire' => 'Restaurant universitaire', 'Rectorat' => 'Rectorat'),'attr' => array('class' => 'form-control input-lg margin-buttom-30')))
                   ->add('save', SubmitType::class, array('label' => 'Update Institution', 'attr' => array('class' => 'btn btn-primary btn-block btn-lg')))
                   ->getForm();

                   $form->handleRequest($request);
                   if($form->isSubmitted() && $form->isValid()){

                     $name = $form['name']->getData();
                     $type = $form['type']->getData();


                     $em = $this->getDoctrine()->getManager();
                     $estab = $em->getRepository('AppBundle:Institution')->find($id);

                     $estab->setName($name);
                     $estab->setType($type);

                     $em->flush();
                     $this->addFlash(
                       'notice',
                       'Institution Edited'
                     );
                     return $this->redirectToRoute('listpage');
                   }

                 return $this->render('backend/update.html.twig', array(
                   'estab' => $estab,
                   'form' => $form->createView(),
                 ));
           }
}
