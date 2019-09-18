<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Entity\Rows;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class InvoiceController extends AbstractController
{
    /**
     * @Route("/invoice/{id}", name="invoice")
     */
    public function index($id)
    {
        $em = $this->getDoctrine()->getManager();
        $invoice = $em->getRepository(Invoice::class)->findBy(['user' => $id]);

        $row = $em->getRepository(Rows::class)->findBy(['I' => $invoice[0]->getId()]);

        return $this->render('invoice/index.html.twig', [
            'controller_name' => 'InvoiceController',
            'invoice' => $invoice,
            'row' => $row
        ]);
    }

    /**
     * @Route("/allInvoices/", name="allInvoices")
     */
    public function AllInvoices()
    {
        $em = $this->getDoctrine()->getManager();
        $invoice = $em->getRepository(Invoice::class)->findAll();

        return $this->render('invoice/AllInvoice.html.twig', [
            'invoice' => $invoice,
        ]);
    }
}
