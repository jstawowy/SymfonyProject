<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class ProductsController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/addProduct', name: 'add_product')]
    public function new(Request $request): Response
    {
        $product = new Product();
        $product->setName('Name of the product');
        $product->setPrice(0);

        $form = $this->createForm(ProductType::class, $product)
            ->add('name', TextType::class)
            ->add('price', IntegerType::class)
            ->add('save', SubmitType::class, ['label' => 'Add product'])
            ;

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $product = $form->getData();

                //$this->entityManager->persist($product);
                //$this->entityManager->flush();

                
                return new Response("<h3>Product added to the database</h3>");
            }

            return $this->render('./productForm.html.twig', [
                'form' => $form,
            ]);
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
