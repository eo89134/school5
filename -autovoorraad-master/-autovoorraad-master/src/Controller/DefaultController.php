<?php

namespace App\Controller;

use App\Entity\Autos;
use App\Form\AutoType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/default', name: 'app_default')]
    public function index(): Response
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
    #[Route('/addcars', name: 'app_addcars')]
    public function addcars(EntityManagerInterface $em, Request $request): Response
    {
        $autos = new Autos();
        $form = $this->createForm(AutoType::class, $autos);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $autos = $form->getData();
            $em->persist($autos);
            $em->flush();
            $this->addFlash('success', 'Auto is toegevoegd !');

            return $this->redirectToRoute('app_dashboard');
        }
        return $this->renderForm('default/addcars.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/dashboard', name: 'app_dashboard')]
    public function dashboard(EntityManagerInterface $em): Response
    {
        $autos = $em->getRepository(Autos::class)->findAll();
        return $this->render('default/dashboard.html.twig', [
            'autos' => $autos,
        ]);
    }
    #[Route('/details/{id}', name: 'app_details')]
    public function  details(EntityManagerInterface $em, int $id): Response
    {
        $autos = $em->getRepository(Autos::class)->find($id);
        return $this->render('default/details.html.twig', [
            'auto' => $autos,
        ]);
    }
    #[Route('/update/{id}', name: 'app_update')]
    public function update(EntityManagerInterface $em, Request $request, int $id): Response
    {
        $autos = $em->getRepository(Autos::class)->find($id);
        $form = $this->createForm(AutoType::class, $autos);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $autos = $form->getData();
            $em->persist($autos);
            $em->flush();
            $this->addFlash('success', 'Auto is geupdated!');

            return $this->redirectToRoute('app_dashboard');
        }

        return $this->renderForm('default/update.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/delete/{id}', name: 'app_delete')]
    public function delete(EntityManagerInterface $em, int $id): Response
    {
        $autos = $em->getRepository(Autos::class)->find($id);
        $em->remove($autos);
        $em->flush();
        $this->addFlash('success', 'Auto is  verwijdert !');
        return $this->redirectToRoute('app_dashboard');
    }
}
