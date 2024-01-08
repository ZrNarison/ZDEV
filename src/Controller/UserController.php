<?php

namespace App\Controller;

use App\Form\EditCategorieType;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * Visualisation des compte
     * @Route("/Admin/view-admincompte",name="adminviewcompte")
     * @return Response
     */
    public function admincompte(UserRepository $user){
        $User = $user->findAll();
        return $this->render('user/index.html.twig', [
            'User' => $User
        ]);
    }

    /**
     * Visualisation des classement
     * @Route("/Admin/view-adminclassement",name="adminviewclassement")
     * @return Response
     */
    public function viewclass(RoleRepository $cat){
        $categ = $cat->findAll();
        return $this->render('page/AdminClassement.html.twig', [
            'categ' => $categ
        ]);
    }

    /**
     * Editer classement
     * @Route("/Admin/mod{slugrole}", name="classedit")
     * @return Response
     */
    public function Editclassement(string $slugrole,RoleRepository $classement, Request $rqt){
        $slugify = new Slugify();
        $AdClass=$classement->findOneBy(['RoleSlug'=>$slugrole]);
        $form = $this -> createForm(EditCategorieType::class, $AdClass);
        $form -> handleRequest($rqt);
        if($form->isSubmitted()&&$form->isValid()){
            $slug=$slugify ->slugify($form->get('title')->getData());
            $AdClass->setRoleSlug($slug);
            $mng = $this-> getDoctrine()->getManager();
            $mng -> persist ($AdClass);
            $mng-> flush();
            return $this->redirectToRoute('adminviewclassement');
        }
        return $this->render("editer/editclass.html.twig",[
            "form"=> $form->createView(),
            "AdClass"=>$AdClass
        ]);
    }

    /**
     * Suppression de classement
     * @Route("/Suppression/{slugcat}", name="delcateg")
     * @return Response
     */
    public function deleteclassement(string $slugcat,RoleRepository $repository)
    {
        $cat=$repository->findOneBy(['id'=>$slugcat]);
        $mgr = $this -> getDoctrine()->getManager();
        $mgr->remove($cat);
        $mgr->flush();
        return $this->redirectToRoute('adminviewclassement');
    }

}
