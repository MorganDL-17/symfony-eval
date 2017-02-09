<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class PageController
 * @package AppBundle\Controller
 */
class PageController extends Controller
{
    /**
     * Index action.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('AppBundle:Page:index.html.twig');
    }

    /**
     * Contact action.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function publierAction(Request $request)
    {
        $data = [
            'title' => null,
            'author' => null,
            'publishedAt' => null
        ];

        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('app_publier'))
            ->setMethod('POST')

            ->add('title', Type\TextareaType::class)
            ->add('author', Type\TextareaType::class)
            ->add('publishedAt', Type\DateType::class)
            ->add('send', Type\SubmitType::class)
            ->getForm();

        $form->handleRequest($request); // Before createView()

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            /* Symfony\Component\HttpFoundation\File\UploadedFile */
            $file = $data['attachment'];

            // $fileName = $file->getClientOriginalName();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            $dir = $this->getParameter('kernel.root_dir') . '/../var/data';

            // Move the file to the directory
            $file->move($dir, $fileName);
        }
        return $this->render('AppBundle:Page:contact.html.twig', [
            'form' => $form->createView(),
            'data' => $data,
        ]);
    }
}
