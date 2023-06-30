<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/category', name: 'category')]
    public function handleAddCategoryForm(Request $request): Response
    {
        $newCategory = new Category();
        $newCategory->setCode(0);
        
        $form = $this->createForm(CategoryType::class, $newCategory)
        ->add('code', IntegerType::class)
        ->add('save', SubmitType::class, ['label' => 'Create new Category']);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newCategory = $form->getData();
            if(!$this->checkForRecordInDatabase($newCategory)){
                $this->entityManager->persist($newCategory);
                $this->entityManager->flush();
    
                return $this->redirect($request->getUri());
            }
            else{
                if($newCategory->getCode()==0)
                    echo ("<h4>Incorrect category number</h4>");             
                else
                    echo ("<h4>Category already defined in the database</h4>");
            }
            
        }
        $actualCategories = $this->entityManager->getRepository(Category::class)->findAll();
        
        return $this->render('./categoryForm.html.twig', [
            'form' => $form,
            'categories' => $actualCategories,
        ]);
    }
    public function checkForRecordInDatabase(Category $category): bool{
        $code = $category->getCode();
        $database = $this->entityManager->getRepository(Category::class)->findBy(['code'=>$code]);
        if(count($database)==0 && $code != 0)
            return false;
        else
            return true;
            
    }



    #[Route('/categoryCreated', name: 'categoryCreated')]
    public function categoryCreated(){
        $database = $this->entityManager->getRepository(Category::class)->findAll();
        return $this->render('./categoryAdded.html.twig', [
            'database' => $database,
        ]);
    }
}
