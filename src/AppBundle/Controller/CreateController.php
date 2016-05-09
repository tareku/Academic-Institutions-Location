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

class CreateController extends Controller
{
        /**
         * @Route("/backend/create", name="createpage")
         */
         public function createAction(Request $request)
           {
               $estab = new Institution;

               $form = $this->createFormBuilder($estab,  array("action" => $this->generateUrl("createpage")))
                 ->add('name', TextType::class, array('attr' => array('class' => 'form-control input-lg margin-buttom-30', 'placeholder' => 'Institution name')))
                 ->add('type', ChoiceType::class, array('choices' => array('Faculté' => 'Faculté', 'Logement et restaurant universitaire' => 'Logement et restaurant universitaire', 'Rectorat' => 'Rectorat'),'attr' => array('class' => 'form-control input-lg margin-buttom-30')))
                 ->add('address', TextType::class, array('attr' => array('class' => 'form-control input-lg margin-buttom-30', 'on-place-changed' => 'vm.placeChanged()', 'places-auto-complete' => 'places-auto-complete')))
                 ->add('Latitude', TextType::class, array('attr' => array('class' => 'form-control input-lg margin-buttom-30', 'placeholder' => 'Latitude', 'value' => '{{vm.lat}}')))
                 ->add('Longitude', TextType::class, array('attr' => array('class' => 'form-control input-lg margin-buttom-30', 'placeholder' => 'Longitude', 'value' => '{{vm.lng}}')))
                 ->add('save', SubmitType::class, array('label' => 'Add Institution', 'attr' => array('class' => 'btn btn-primary btn-block btn-lg')))
                 ->getForm();

                 $form->handleRequest($request);

                 if($form->isSubmitted() && $form->isValid()){
                   $name = $form['name']->getData();
                   $type = $form['type']->getData();
                   $address = $form['address']->getData();

                   $estab->setName($name);
                   $estab->setType($type);
                   $estab->setAddress($address);

                   $em = $this->getDoctrine()->getManager();
                   $em->persist($estab);
                   $em->flush();

                   $this->addFlash(
                     'notice',
                     'Institution Added'
                   );
                   return $this->redirectToRoute('listpage');
                 }

               return $this->render('backend/create.html.twig', array(
                 'form' => $form->createView()
               ));
           }
}
