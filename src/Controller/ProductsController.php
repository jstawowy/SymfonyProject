<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/index', name: 'index')]
    public function index(): Response
    {
        $product = new Product();
        $product->setName("Lampka");
        $product->setPrice(99);



 //       $this->entityManager->persist($product);
 //       $this->entityManager->flush();

        $database = $this->entityManager->getRepository(Product::class)->findAll();

        return $this->render('./database.html.twig', ['database' => $database]);
    }
}
