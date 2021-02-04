<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/dashboard/project")
 */
class ProjectController extends AbstractController
{
    /**
     * @Route("/list", name="project_index", methods={"GET"})
     */
    public function index(ProjectRepository $projectRepository): Response
    {
        return $this->render('dashboard/project/index.html.twig', [
            'projects' => $projectRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="project_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère les images transmises 
            $image = $form->get('image')->getData();

            if ( in_array($image->guessExtension(), Project::FILE_ACCEPTED['image']) ){
                if ( !Project::isMaxFileSize((int) $image->getSize()) ) {
                    // On génère un nouveau nom de fichier 
                    $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                    // On copie le fichier dans le dossier uploads
                    $image->move(
                        $this->getParameter('images_directory'),
                        $fichier,
                    );
                    // On stocke l'image dans la base de données
                    $project->setImage($fichier);  
        
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($project);
                    $entityManager->flush();
                    
                    return $this->redirectToRoute('project_index');
                } else {
                    $this->addFlash('danger', 'Ce fichier est trop lourd, limite de 10mo');
                }

            } else {
                $this->addFlash('danger', 'Seuls le fichiers .png, .git, .jpg, .jpeg et .svg sont acceptés .');
            }

        }

        return $this->render('dashboard/project/new.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="project_show", methods={"GET"})
     */
    public function show(Project $project): Response
    {
        return $this->render('dashboard/project/show.html.twig', [
            'project' => $project,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="project_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Project $project): Response
    {
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // dd($request->files->get('project')['image']);
            // On récupère les images transmises 
            $image = $form->get('image')->getData();

            if ( in_array($image->guessExtension(), Project::FILE_ACCEPTED['image']) ){
                if ( !Project::isMaxFileSize((int) $image->getSize()) ) {

                    // On génère un nouveau nom de fichier 
                    $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                    // On copie le fichier dans le dossier uploads
                    $image->move(
                        $this->getParameter('images_directory'),
                        $fichier,
                    );
                    // On stocke l'image dans la base de données
                    $project->setImage($fichier);  
        
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($project);
                    $entityManager->flush();
                    
                    return $this->redirectToRoute('project_index');
                } else {
                    $this->addFlash('danger', 'Ce fichier est trop lourd, limite de 10mo');
                }

            } else {
                $this->addFlash('danger', 'Seuls le fichiers .png, .git, .jpg, .jpeg et .svg sont acceptés .');
            }

        }

        return $this->render('dashboard/project/edit.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="project_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Project $project): Response
    {
        if ($this->isCsrfTokenValid('delete'.$project->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($project);
            $entityManager->flush();
        }

        return $this->redirectToRoute('project_index');
    }
}
