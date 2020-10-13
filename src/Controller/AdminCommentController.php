<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comments", name="admin_comments_index")
     */
    public function index(CommentRepository $commentRepo)
    {
        return $this->render('admin/comment/index.html.twig', [
            'comments' => $commentRepo->findAll()
        ]);
    }

    /**
     * @Route("/admin/comments/{id}/edit", name="admin_comments_edit")
     */
    public function edit(Comment $comment, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AdminCommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                "success",
                "Le commentaire n°{$comment->getId()} a bien été modifier"
            );

            return $this->redirectToRoute("admin_comments_index");
        }

        return $this->render("admin/comment/edit.html.twig", [
            'form' => $form->createView(),
            'comment' => $comment
        ]);
    }

    /**
     * For delete a comment
     * @Route("/admin/comments/{id}/delete", name="admin_comments_delete")
     */
    public function delete(Comment $comment, EntityManagerInterface $manager)
    {
        $title = $comment->getId();
        $manager->remove($comment);
        $manager->flush();

        $this->addFlash(
            "success",
            "Le commentaire <strong>{$title}</strong> a bien été supprimé"
        );

        return $this->redirectToRoute("admin_comments_index");
    }
}
