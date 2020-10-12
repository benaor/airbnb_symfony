<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/admin/comment/{id}/edit", name="admin_comments_edit")
     */
    public function edit(Comment $comment)
    {
        $form = $this->createForm(CommentType::class, $comment);

        return $this->render("admin/comment/edit.html.twig", [
            'form' => $form->createView(),
            'comment' => $comment
        ]);
    }
}
