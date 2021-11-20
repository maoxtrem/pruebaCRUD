<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\Type\CategoryFormType;
use App\Repository\CategoryRepository;
use App\Utils\Util;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    private $HEADER_EXCEL_CATEGORY = array("ID",  "Name", "State");
    private $FILE_NAME_EXCEL_CATEGORY = "CategotyList.xlsx";


    /**
     * @Route("/Category/list",name="category_list")
     */
    public function listProducts(Request $request, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render("CategotyList.html.twig", ["categories" => $categories]);
    }

    /**
     * @Route("/Category/add",name="category_add")
     */
    public function addProduct(Request $request, EntityManagerInterface $em): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('category_list');
        }
        return $this->renderForm("CategoryForm.html.twig", ['form' => $form]);
    }

    /**
     * @Route("/Category/update/{id}",name="category_update")
     */
    public function update(Request $request, EntityManagerInterface $em, CategoryRepository $categoryRepository, $id): Response
    {
        $category = $categoryRepository->find($id);
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('category_list');
        }
        return $this->renderForm("CategoryForm.html.twig", ['form' => $form]);
    }

    /**
     * @Route("/Category/delete/{id}",name="category_delete")
     */
    public function delete(Request $request, EntityManagerInterface $em,CategoryRepository $categoryRepository, $id): Response
    {
        $category = $categoryRepository->find($id);
        $em->remove($category);
        $em->flush();
        return $this->redirectToRoute('category_list');

    }

    /**
     * @Route("/Category/Export",name="category_export")
     */
    public function export(Request $request, CategoryRepository $categoryRepository): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $spreadsheet = $this->excelProducts($categoryRepository);

        $writer = new Xlsx($spreadsheet);
        $fileName =$this->FILE_NAME_EXCEL_CATEGORY;
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($temp_file);
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    private function excelProducts(CategoryRepository $categoryRepository): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $indice = 1;
        $letras = Util::$LETRAS;
        foreach ($this->HEADER_EXCEL_CATEGORY as $index => $head) {
            $sheet->setCellValue($letras[$index] . $indice, $head);
        }
        $products = $categoryRepository->listCategories();
        $indice++;
        foreach ($products as $product) {
            $indiceLetras = 0;
            foreach ($product as $value) {
                $sheet->setCellValue($letras[$indiceLetras] . $indice, $value);
                $indiceLetras++;
            }
            $indice++;
        }
        return $spreadsheet;
    }
}