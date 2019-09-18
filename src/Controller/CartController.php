<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Entity\Product;
use App\Entity\Rows;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route("/cart")
 */
class CartController extends AbstractController
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Route("/", name="cart", methods={"GET", "POST"})
     */
    public function index()
    {
        $cart = $this->session->get('cart');
//        $this->session->clear();

        if ($cart == null) {
            echo 'De cart is leeg';

            return $this->render('cart/index.html.twig');
        } else {
            $cartArray = [];
            $total = 0;

            foreach ($cart as $id => $product) {
                $res = $this->getDoctrine()
                    ->getRepository(Product::class)
                    ->find($id);

                array_push($cartArray, [$id, $product['aantal'], $res]);

                $total = $total + ($product['aantal'] * $res->getPrice());
            }

            return $this->render('cart/index.html.twig', [
                'controller_name' => 'CartController',
                'total' => $total,
                'cart' => $cartArray,
            ]);
        }
    }

    /**
     * @Route("/add/{id}", name="cart_add")
     */
    public function add(Product $product, Request $request)
    {
        $id = $product->getId();

        $getCart = $this->session->get('cart');
        if (isset($getCart[$id])) {
            $getCart[$id]['aantal']++;
        } else {
            $getCart[$id] = array('aantal' => 1);
        }
        $this->session->set('cart', $getCart);

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/checkout", name="checkout")
     */
    public function checkout()
    {
        $getCart = $this->session->get('cart');

        $cartArray = [];
        $total = 0;

        foreach ($getCart as $id => $product) {
            $res = $this->getDoctrine()
                ->getRepository(Product::class)
                ->find($id);

            array_push($cartArray, [$id, $product['aantal'], $res]);

            $total = $total + ($product['aantal'] * $res->getPrice());
        }
        $em = $this->getDoctrine()->getManager();

        $invoice = new Invoice();
        $invoice->setDate(new \DateTime("now"));
        $invoice->setUser($this->getUser());
        $em->persist($invoice);

        $row = new Rows();
        $row->setP($res);
        $row->setI($invoice);
        $row->setRows($product['aantal']);

        $em->persist($row);
        $em->flush();

        $this->session->clear();

        return $this->redirect('/');
//        return $this->render('cart/checkout.html.twig');
    }

    /**
     * @Route("/remove/{id}", name="cart_remove")
     */
    public function remove(Product $product, Request $request)
    {
        $id = $product->getId();

        $getCart = $this->session->get('cart');
        if (isset($getCart[$id])) {
            $getCart[$id]['aantal'] = $getCart[$id]['aantal'] - 1;
            if ($getCart[$id] < 1) {
                unset($getCart[$id]);
            }
        }

        $this->session->set('cart', $getCart);

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/empty", name="empty_cart")
     */
    public function empty()
    {
        $this->session->clear();

        return $this->redirect('/');
    }
}
