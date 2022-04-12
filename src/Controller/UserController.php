<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route ("/", name="user_connection")
     */
    public function connection()
    {
        return $this->render('connection.html.twig');
    }


    /**
     * @Route ("/", name="user_profile")
     */
    public function inscription()
    {
        return $this->render('profile.html.twig');
    }




}