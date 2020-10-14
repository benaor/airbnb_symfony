<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

class PaginationService
{

    private $entityClass;
    private $limit = 10;
    private $currentPage = 1;
    private $manager;
    private $twig;
    private $route;

    public function __construct(EntityManagerInterface $manager, Environment $twig, RequestStack $request)
    {
        $this->route   = $request->getCurrentRequest()->attributes->get('_route');
        $this->manager = $manager;
        $this->twig    = $twig;
    }

    public function display(){
        $this->twig->display("admin/includes/pagination.html.twig", [
            'page' => $this->currentPage,
            'pages'=> $this->getPages(),
            'route'=> $this->route
        ]);
    }

    public function getPages(){
        $repo  = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findAll());

        $pages = ceil($total / $this->limit);

        return $pages;
    }

    public function getData(){
        $offset = $this->currentPage * $this->limit - $this->limit; 
        $repo   = $this->manager->getRepository($this->entityClass);
        $data   = $repo->findBy([], [], $this->limit, $offset);

        return $data;
    }

    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;

        return $this;
    }

    public function getEntityClass()
    {
        return $this->entityClass;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function setPage($page){
        $this->currentPage = $page;

        return $this;
    }

    public function getPage(){
        return $this->currentPage;
    }

    public function setRoute($route){
        $this->route = $route;

        return $this;
    }

    public function getRoute(){
        return $this->route;
    }
}
