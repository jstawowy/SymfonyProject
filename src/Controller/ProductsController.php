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
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


class ProductsController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private MailerInterface $mailer;

    public function __construct(EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
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
                $email = (new Email())
                    ->from('kodanoprojectemail@gmail.com')
                    ->to('jakubst2000@wp.pl')
                    ->subject('New product added to the database')
                    ->text("Product name: " . strval($product->getName()) . "\n" . " Product price: " .  strval($product->getPrice()));

                $this->mailer->send($email);
                //$this->entityManager->persist($product);
                //$this->entityManager->flush();

                
                return new Response("<h3>Product added to the database</h3>" );
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
