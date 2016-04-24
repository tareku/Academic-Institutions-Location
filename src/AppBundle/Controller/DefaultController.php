<?php

namespace AppBundle\Controller;
use AppBundle\Entity\Institution;
use AppBundle\Entity\Establishment;

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
         * @Route("/backend/create", name="createpage")
         */
         public function createAction(Request $request)
           {
               $estab = new Institution;

               $form = $this->createFormBuilder($estab,  array("action" => $this->generateUrl("createpage")))
                 ->add('name', TextType::class, array('attr' => array('class' => 'form-control input-lg', 'style' => 'margin-bottom:15px')))
                 ->add('type', ChoiceType::class, array('choices' => array('Faculté' => 'Faculté', 'Logement et restaurant universitaire' => 'Logement et restaurant universitaire', 'Rectorat' => 'Rectorat'),'attr' => array('class' => 'form-control input-lg', 'style' => 'margin-bottom:15px')))
                 ->add('address', TextType::class, array('attr' => array('class' => 'form-control input-lg', 'style' => 'margin-bottom:15px','on-place-changed' => 'vm.placeChanged()', 'places-auto-complete' => 'places-auto-complete')))
                 ->add('save', SubmitType::class, array('label' => 'Add Institution', 'attr' => array('class' => 'btn btn-primary', 'style' => 'margin-bottom:15px')))
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
     * @Route("/backend/edit/{id}", name="todo_edit")
     */
    public function editAction($id, Request $request)
    {
        $estab = $this->getDoctrine()
          ->getRepository('AppBundle:Institution')
          ->find($id);

          $estab->setName($estab->getName());
          $estab->setType($estab->getType());
          $estab->setAddress($estab->getAddress());

          $form = $this->createFormBuilder($estab)
            ->add('name', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('type', ChoiceType::class, array('choices' => array('Faculté' => 'Faculté', 'Logement et restaurant universitaire' => 'Logement et restaurant universitaire', 'Rectorat' => 'Rectorat'), 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('address', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('save', SubmitType::class, array('label' => 'Edit Establishment', 'attr' => array('class' => 'btn btn-primary', 'style' => 'margin-bottom:15px')))
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
   * @Route("/frontend/directions", name="directionspage")
   */
  public function directionAction()
  {
    $estabs = $this->getDoctrine()
      ->getRepository('AppBundle:Establishment')
      ->findAll();

    return $this->render('frontend/directions.html.twig', array(
      'estabs' => $estabs,
    ));
  }
}
