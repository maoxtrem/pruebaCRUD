<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\Type\ProductFormType;
use App\Repository\ProductRepository;
use App\Utils\Util;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class ProductController extends AbstractController
{
   private $HEADER_EXCEL_PRODUCT = array("ID", "Code", "Name", "Description", "Brand", "Category", "Price");
   private $FILE_NAME_EXCEL_PRODUCT = "ProductList.xlsx";


    /**
     * @Route("/Product/list",name="products_list")
     */
    public function listProducts(Request $request, ProductRepository $productRepository): Response
    {
        $Products = $productRepository->listaProductos();
        return $this->render("ProductsList.html.twig", ["products" => $Products]);
    }

    /**
     * @Route("/Product/add",name="product_add")
     */
    public function addProduct(Request $request, EntityManagerInterface $em): Response
    {
        $Producto = new Product();
        $form = $this->createForm(ProductFormType::class, $Producto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $Product = $form->getData();
            $em->persist($Product);
            $em->flush();
            return $this->redirectToRoute('products_list');
        }
        return $this->renderForm("ProductForm.html.twig", ['form' => $form]);
    }

    /**
     * @Route("/Product/update/{id}",name="product_update")
     */
    public function update(Request $request, EntityManagerInterface $em, $id): Response
    {
        $Product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        $form = $this->createForm(ProductFormType::class, $Product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $Product = $form->getData();
            $em->persist($Product);
            $em->flush();
            return $this->redirectToRoute('products_list');
        }
        return $this->renderForm("ProductForm.html.twig", ['form' => $form]);
    }

    /**
     * @Route("/Product/delete/{id}",name="product_delete")
     */
    public function delete(Request $request, EntityManagerInterface $em, $id): Response
    {
        $Product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        $em->remove($Product);
        $em->flush();
        return $this->redirectToRoute('products_list');

    }

    /**
     * @Route("/Product/Export",name="product_export")
     */
    public function export(Request $request, ProductRepository $productRepositor): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $spreadsheet = $this->excelProducts($productRepositor);

        $writer = new Xlsx($spreadsheet);
        $fileName =$this->FILE_NAME_EXCEL_PRODUCT;
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($temp_file);
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    private function excelProducts(ProductRepository $productRepositor): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $indice = 1;
        $letras = Util::$LETRAS;
        foreach ($this->HEADER_EXCEL_PRODUCT as $index => $head) {
            $sheet->setCellValue($letras[$index] . $indice, $head);
        }
        $products = $productRepositor->listaProductos();
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