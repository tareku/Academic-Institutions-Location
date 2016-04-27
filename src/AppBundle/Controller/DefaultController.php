<?php

namespace AppBundle\Controller;
use AppBundle\Entity\Institution;
use AppBundle\Entity\Establishment;
use AppBundle\Entity\Gallery;
use AppBundle\Entity\Historique;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
      $estabs = $this->getDoctrine()
        ->getRepository('AppBundle:Institution')
        ->findAll();

      return $this->render('default/index.html.twig', array(
        'estabs' => $estabs,
      ));
    }
    /**
     * @Route("/common/list", name="listpage")
     */
    public function listAction(Request $request)
    {
      $estabs = $this->getDoctrine()
        ->getRepository('AppBundle:Institution')
        ->findAll();

      return $this->render('common/list.html.twig', array(
        'estabs' => $estabs,
      ));
    }

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
                   //create data
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
                   return $this->redirectToRoute('homepage');
                 }

               return $this->render('backend/create.html.twig', array(
                 'form' => $form->createView()
               ));
           }

           /**
            * @Route("/backend/edit/{id}", name="editpage")
            */
           public function editAction($id, Request $request)
           {
               $estab = $this->getDoctrine()
                 ->getRepository('AppBundle:Institution')
                 ->find($id);

                 $estab->setName($estab->getName());
                 $estab->setType($estab->getType());
                 $estab->setAddress($estab->getAddress());

                 $form = $this->createFormBuilder($estab,  array("action" => $this->generateUrl("editpage", array('id' => 'id'))))
                   ->add('name', TextType::class, array('attr' => array('class' => 'form-control input-lg margin-buttom-10', 'placeholder' => 'Institution name')))
                   ->add('type', ChoiceType::class, array('choices' => array('Faculté' => 'Faculté', 'Logement et restaurant universitaire' => 'Logement et restaurant universitaire', 'Rectorat' => 'Rectorat'),'attr' => array('class' => 'form-control input-lg margin-buttom-30')))
                   ->add('address', TextType::class, array('attr' => array('class' => 'form-control input-lg margin-buttom-10', 'on-place-changed' => 'vm.placeChanged()', 'places-auto-complete' => 'places-auto-complete')))
                   ->add('Latitude', TextType::class, array('attr' => array('class' => 'form-control input-lg margin-buttom-10', 'placeholder' => 'Latitude', 'value' => '{{vm.lat}}')))
                   ->add('Longitude', TextType::class, array('attr' => array('class' => 'form-control input-lg margin-buttom-10', 'placeholder' => 'Longitude', 'value' => '{{vm.lng}}')))
                   ->add('save', SubmitType::class, array('label' => 'Edit Institution', 'attr' => array('class' => 'btn btn-primary btn-block btn-lg')))
                   ->getForm();

                   $form->handleRequest($request);
                   if($form->isSubmitted() && $form->isValid()){
                     //create data
                     $name = $form['name']->getData();
                     $type = $form['type']->getData();
                     $address = $form['address']->getData();

                     $em = $this->getDoctrine()->getManager();
                     $estab = $em->getRepository('AppBundle:Institution')->find($id);

                     $estab->setName($name);
                     $estab->setType($type);
                     $estab->setAddress($address);
                     $em->flush();
                     $this->addFlash(
                       'notice',
                       'Institution Edited'
                     );
                     return $this->redirectToRoute('homepage');
                   }
                 return $this->render('backend/edit.html.twig', array(
                   'estab' => $estab,
                   'form' => $form->createView()
                 ));
           }


           /**
            * @Route("/backend/update/{id}", name="updatepage")
            */
           public function updateAction($id, Request $request)
           {
               $estab = $this->getDoctrine()
                 ->getRepository('AppBundle:Institution')
                 ->find($id);

                 $estab->setName($estab->getName());
                 $estab->setType($estab->getType());
                 $estab->setAddress($estab->getAddress());

                 $form = $this->createFormBuilder($estab)
                   ->add('name', TextType::class, array('attr' => array('class' => 'form-control input-lg margin-buttom-10', 'placeholder' => 'Institution name')))
                   ->add('type', ChoiceType::class, array('choices' => array('Faculté' => 'Faculté', 'Logement et restaurant universitaire' => 'Logement et restaurant universitaire', 'Rectorat' => 'Rectorat'),'attr' => array('class' => 'form-control input-lg margin-buttom-30')))
                   ->add('address', TextType::class, array('attr' => array('class' => 'form-control input-lg margin-buttom-10')))
                   ->add('save', SubmitType::class, array('label' => 'Edit Institution', 'attr' => array('class' => 'btn btn-primary btn-block btn-lg')))
                   ->getForm();

                   $form->handleRequest($request);
                   if($form->isSubmitted() && $form->isValid()){
                     //create data
                     $name = $form['name']->getData();
                     $type = $form['type']->getData();
                     $address = $form['address']->getData();

                     $em = $this->getDoctrine()->getManager();
                     $estab = $em->getRepository('AppBundle:Institution')->find($id);

                     $estab->setName($name);
                     $estab->setType($type);
                     $estab->setAddress($address);
                     $em->flush();
                     $this->addFlash(
                       'notice',
                       'Institution Edited'
                     );
                     return $this->redirectToRoute('listpage');
                   }
                 return $this->render('backend/update.html.twig', array(
                   'estab' => $estab,
                   'form' => $form->createView()
                 ));
           }


               /**
                * @Route("/backend/delete/{id}", name="deletepage")
                */
               public function deleteAction($id)
               {
                 $em = $this->getDoctrine()->getManager();
                 $estab = $em->getRepository('AppBundle:Institution')->find($id);

                 $em->remove($estab);
                 $em->flush();

                 $this->addFlash(
                   'notice',
                   'Institution Removed'
                 );
                 return $this->redirectToRoute('homepage');

               }

    /**
     * @Route("/common/details/{id}", name="detailspage")
     */
    public function detailsAction($id)
    {
        $estab = $this->getDoctrine()
          ->getRepository('AppBundle:Institution')
          ->find($id);

        return $this->render('common/details.html.twig', array(
          'estab' => $estab
        ));
    }

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
