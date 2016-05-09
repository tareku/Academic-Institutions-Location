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

class GalleryController extends Controller
{
          /**
            * @Route("/common/gallery/{id}", name="gallerypage")
            */
           public function galleryAction($id, Request $request)
           {

             $estab = $this->getDoctrine()
               ->getRepository('AppBundle:Institution')
               ->find($id);

             $pictures = $this->getDoctrine()
               ->getRepository('AppBundle:Gallery')
               ->findByEstablishment($id);

               $gallery = new Gallery;

                   $galleryform = $this->createFormBuilder($gallery,  array("action" => $this->generateUrl("gallerypage", array('id' => 'id'))))
                     ->add('Url', TextType::class, array('attr' => array('class' => 'form-control input-lg margin-buttom-10', 'placeholder' => 'Picture URL')))
                     ->add('Establishment', HiddenType::class, array('attr' => array('class' => 'form-control input-lg margin-buttom-10', 'placeholder' => 'Establishment ID', 'value' => $id)))
                     ->add('save', SubmitType::class, array('label' => 'Add to gallery', 'attr' => array('class' => 'btn btn-primary btn-block btn-lg')))
                     ->getForm();
                   $galleryform->handleRequest($request);

                   if($galleryform->isSubmitted() && $galleryform->isValid()){

                     $url = $galleryform['Url']->getData();
                     $estabid = $galleryform['Establishment']->getData();

                     $gallery->setUrl($url);
                     $gallery->setEstablishment($estabid);

                     $em = $this->getDoctrine()->getManager();
                     $em->persist($gallery);
                     $em->flush();

                     $this->addFlash(
                       'notice',
                       'Picture Added'
                     );
                     return $this->redirectToRoute('listpage');
                   }

                 return $this->render('common/gallery.html.twig', array(
                   'estab' => $estab,
                   'pictures' => $pictures,
                   'gallery' => $gallery,
                   'galleryform' => $galleryform->createView()
                 ));
           }
}
