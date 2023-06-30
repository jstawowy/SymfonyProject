<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Psr\Log\LoggerInterface;


class ProductsController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private MailerInterface $mailer;
    private LoggerInterface $logger;

    public function __construct(EntityManagerInterface $entityManager, MailerInterface $mailer, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    #[Route('/addProduct', name: 'add_product')]
    public function new(Request $request): Response
    {
        $product = new Product();
        $product->setName('Name of the product');
        $product->setPrice(0);

        $form = $this->createForm(ProductType::class, $product);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $product = $form->getData();
                
                if($product->getPrice()==0){
                    echo("<h4>Product can't be free</h4>");
                }
                elseif($this->checkDatabase($product)){
                    echo("Product already in the database");
                }
                else{
                    
                        
                
                    $this->entityManager->persist($product);
                    $this->entityManager->flush();
    
                    $this->sendMail($product);
                    return new Response("<h3>Product added to the database. Log saved. Email sent.</h3>" );
                }
                
                
            }

            return $this->render('./productForm.html.twig', [
                'form' => $form,
            ]);
    }
    private function checkDatabase(Product $product):bool{
        $database = $this->entityManager->getRepository(Product::class);
        
        if($database->findOneBy(['name' => $product->getName(), 'price' =>$product->getPrice()])){
            return true;
        }
        else return false;
    }
    private function sendMail(Product $product){
        $this->logger->info("Product name: " . strval($product->getName()) . "\n" . " Product price: " .  strval($product->getPrice()));

        $productCategories = "Categories of the product: \n";
        foreach ($product->getCategory() as $category){
            $productCategories = $productCategories . strval($category->getCode()) . "\n";
        }
                
        $email = (new Email())
            ->from('kodanoprojectemail@gmail.com')
            ->to('jakubst2000@wp.pl')
            ->subject('New product added to the database')
            ->text("Product name: " . strval($product->getName()) . "\n" . " Product price: " .  strval($product->getPrice() . "\n" . strval($productCategories)));

        
        $this->mailer->send($email);
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
