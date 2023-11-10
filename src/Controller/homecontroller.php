<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Role;
use App\Entity\User;
use App\Form\AdType;
use App\Form\SugType;
use App\Form\UserType;
use App\Entity\Posteur;
use App\Form\EditAdType;
use App\Entity\Suggestion;
use App\Form\EditUserType;
use Cocur\Slugify\Slugify;
use App\Form\CategorieType;
use App\Entity\UpdatePassword;
use App\Form\EditPasswordType;
use App\Form\EditCategorieType;
use App\Repository\AdRepository;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormError;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class homecontroller extends AbstractController{
    /**
     * Page d'accueil
     * @Route("/",name="home")
     * @Route("/homepage")
     *
     * @return response
     */
    public function home (Request $rq, UserRepository $info)
    {
        $sug = new Suggestion();
        $i = $info->findAll();
        $form = $this -> createForm(SugType::class,$sug);
        $form->handleRequest($rq);
        if($form->isSubmitted()){
            $mgr = $this-> getDoctrine()->getManager();
            $mgr -> persist($sug);            
            $mgr -> flush();
            return $this->redirectToRoute('home');
        }
        return $this->render("page/homepage.html.twig",[
            'form'=> $form->createView(),
            'info' => $i
            ]);
    }
    /**
     * Voir les projets
     * @Route("/view-project",name="viewproject")
     */
    public function viewproject(AdRepository $vproject){
        $project = $vproject->findAll();        
        return $this->render('page/viewproject.html.twig', [
            'projet' => $project
        ]);
    }

    /**
     * Ajout de projet
     * @Route("/Ad-newproject",name="newproject")
     * @Route("/Ad-newdev")
     */
    public function newprojetct(Request $rqt){
         $user = $this->getUser();
         $slugify = new Slugify();
        $project = new Ad();
        // $photo = new Posteur();
        // $photo ->setPhoto('')
        //        ->setCaption('');
        // $project->addPosteur($photo);
        $form = $this -> createForm(AdType::class,$project);
        $form->handleRequest($rqt);
        if($form->isSubmitted()){
            foreach($project->getPosteurs ()as $posteurs){
                    $posteurs->setAd($project);
            }
            $mgr = $this-> getDoctrine()->getManager();
            $fichier = $form->get('fichiers')->getData();
            $posteurphoto = $form->get('photo')->getData();
            $project  ->setAuteur($user); 
            $directory=$this->getParameter('dev_directory');
            $directoryposteur=$this->getParameter('posteur_directory');
            $filename=md5(uniqid()).'.'. $fichier->guessExtension();
            $posteurfile=md5(uniqid()).'.'. $posteurphoto->guessExtension();
            $project->setFichiers($filename);
            $photo->setphoto($posteurfile);
            $photo ->addPosteur($project);
            $mgr -> persist($photo);            
            $mgr -> persist($project);            
            $fichier->move($directory,$filename);
            $posteurphoto->move($directoryposteur,$posteurfile);
            $mgr -> flush();
            $this->addFlash("success","Le project N° <strong> {$project->getId()}</strong> dont son nom est <strong>  {$project->getTitle()} </strong> à été bien enregistré !");
            return $this->redirectToRoute('newproject');
        }
        return $this->render("page/Adproject.html.twig",[
            'form'=> $form->createView()
            ]);
    }
    /**
     * Suppression de projet
     * @Route("/Suppression/{id}", name="delproject")
     * @return Response
     */
    public function deleteproject(Ad $supp):Response
    {
        $mgr = $this -> getDoctrine()->getManager();
        $mgr->remove($supp);
        $mgr->flush();
        return $this->redirectToRoute('Adminviewproject');
    }
    
    /**
     * Visualisation des projet
     * @Route("/view-adminproject",name="adminviewproject")
     */
    public function viewadmproject(AdRepository $vproject){
        $project = $vproject->findAll();
        return $this->render('page/Adminviewproject.html.twig', [
            'projet' => $project
        ]);
    }
    /**
     * @Route("/Editer/{eproject}", name="Modproject")
     * @return Response
     */
    public function Editproject(string $eproject,AdRepository $repository, Request $rqt){
        $ad=$repository->findOneBy(['Slug'=>$eproject]);
        $form = $this -> createForm(EditAdType::class, $ad);
        $form -> handleRequest($rqt);
        if($form->isSubmitted()&&$form->isValid()){
            $mng = $this-> getDoctrine()->getManager();
            $mng -> persist ($ad);
            $mng-> flush();
            return $this->redirectToRoute('adminviewproject');
        }
        return $this->render("editer/editproject.html.twig",[
            "form"=> $form->createView(),
            "ad"=>$ad
        ]);
    }
    /**
     * Editer classement
     * @Route("/Editer/{slug}", name="class_edit")
     * @return Response
     */
    public function Editclassement(string $slug,RoleRepository $classement, Request $rqt){
        $AdClass=$classement->findOneBy(['RoleSlug'=>$slug]);
        $form = $this -> createForm(EditCategorieType::class, $AdClass);
        $form -> handleRequest($rqt);
        if($form->isSubmitted()&&$form->isValid()){
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
     * Visualisation des classement
     * @Route("/view-adminclassement",name="adminviewclassement")
     */
    public function viewclass(RoleRepository $cat){
        $categ = $cat->findAll();
        return $this->render('page/AdminClassement.html.twig', [
            'categ' => $categ
        ]);
    }

    

    /**
     * Ajout compte d'Administrateur
     * @Route("/Ad-newcompte", name="newcompte")
     * @Route("/Ad/newcompte")
     * @Route("/Ad/ncompte")
     * @return response
     */
    public function newcompte(Request $rqt,UserPasswordEncoderInterface $encoder)
    {
        $newUser = new User();
        $userRoles = new Role();
        $slugify = new Slugify();
        $form=$this->createForm(UserType::class, $newUser);
        $form->handleRequest($rqt);
        if($form->isSubmitted()&& $form->isValid()){
            $fichier = $form->get('ph')->getData();
            $name = $form->get('firstname')->getData();
            $fname = $form->get('lastname')->getData();
            $directory=$this->getParameter('user_directory');
            $filename=md5(uniqid()).'.'. $fichier->guessExtension();
            $newUser->setph($filename);
            $hash=$encoder->encodePassword($newUser,$newUser->getpsd());
            $newUser->setpsd($hash);
            $mng = $this -> getDoctrine()->getManager();
            $mng -> persist($newUser);
            $newUser -> addUserRole($userRoles);
            $fichier->move($directory,$filename);            
            $mng -> flush();
            $this->addFlash("success"," {$newUser->getlastname()}</strong> Votre compte a été bien enregistrer");
                    return $this->redirectToRoute("newcompte");
        }
        return $this->render("security/newuser.html.twig",[
            "form"=> $form->createView()
        ]);
    }
    /**
     * Afficher un seul projet
     * @Route("/view/{slug}", name="vueonedev")
     * @return Response
     */
    public function OneShow(string $slug,AdRepository $repository){
        $ad=$repository->findOneBy(['Slug'=>$slug]);
        // dd($ad);
        return $this->render('page/onedev.html.twig', [
            'ad' => $ad
        ]);
    }
    /**
     * Ajout de nouveau rôle
     * @Route("/admin-newrole", name="nclass")
     * @Route("/admin-newclass")
     * @return Response
     */
    public function new_class(Request $rqt)
    {
       $nclass= new Role();
       $form = $this -> createForm(CategorieType::class,$nclass);
       $form ->handleRequest($rqt);
       if($form->isSubmitted()){
           $mgr = $this -> getDoctrine()->getManager();
           $mgr -> persist($nclass);
           $mgr -> flush(); 
           $this->addFlash(
               "success",
               "La class N° <strong> {$nclass->getId()}</strong> dont son nom est <strong>  {$nclass->getTitle()} </strong> à été bien enregistré !"
           );
           return $this->redirectToRoute('nclass');           
       }
       return $this->render("page/newclass.html.twig",[
           'form'=> $form->createView()
       ]);
    }

    /**
     * Connexion
     * @Route("/login", name="login_acces")
     * @return response
     */
    public function acces_login(AuthenticationUtils $verif)
    {
        $err = $verif -> getLastAuthenticationError();
        $username=$verif->getLastUsername();
        return $this->render('security/login.html.twig',[
            'err'=> $err !== null,
            'uti'=>$username
        ]);
    }

    /**
     * Déconnexion
     * @Route("/Deconnexion",name="exit")
     * @return Response
     */
    public function logout_account()
    {
       return $this->redirectToRoute('home'); 
    }

    /**
     * Editer profil d'administation
     * @Route("/Edit-Profil/Admin", name="editProfil")
     *
     * @param Request $rqt
     * @return void
     */
    public function editProfil(Request $rqt)
      {
          $user = $this->getUser();
          $form=$this->createForm(EditUserType::class, $user);
          $form->handleRequest($rqt);
        if($form->isSubmitted()&& $form->isValid()){
            $mng = $this -> getDoctrine()->getManager();
            $mng -> persist($user);        
            $mng -> flush();
                    return $this->redirectToRoute("home");
        }
        return $this->render("security/edituser.html.twig",[
            'form'=> $form->createView()
        ]);
      }
    
    
    /**
     * Modifier mot de pass d'administration
     * @Route("/update-password", name="editpass")
     * @Route("/updatepass")
     * 
     * @return response
     */
    public function Pass_Update(Request $rqt,UserPasswordEncoderInterface $encoder )
    {
        $oldpass = $this->getUser();
        $newpass = new UpdatePassword();
        $form = $this->createForm(EditPasswordType::class, $newpass);
        $form->handleRequest($rqt);
        if($form->isSubmitted()&& $form->isValid()){
            //Verification de l'ancien mot de passe
            if(!password_verify($newpass->getOldPassword(),$oldpass->getPsd())){
                $form->get('OldPassword')->addError(new FormError("L'ancien mot de passe que vous avez entrer n'est pas votre mot de pass valide !") );
            }else{
                $newp=$newpass->getNewPassword();
                $hash=$encoder->encodePassword($oldpass,$newp);
                $oldpass->setPsd($hash);
                $mng = $this -> getDoctrine()->getManager();
                $mng -> persist($oldpass);
                $mng -> flush();
                $this->addFlash("success","Votre mot de passe a était bien modifier !");
                        return $this->redirectToRoute("home");
            }
        }
        return $this->render("security/editpass.html.twig",[
            'form'=> $form->createView()
        ]);
    }
    
    /**
     * Suppression de classement
     * @Route("/Suppression/{suppid}", name="delcateg")
     * @return Response
     */
    public function deleteclassement(Role $suppid):Response
    {
        $mgr = $this -> getDoctrine()->getManager();
        $mgr->remove($suppid);
        $mgr->flush();
        return $this->redirectToRoute('Adminviewproject');
    }
    /**
     * @Route("Z-LINK/A_propos", name="z-apropos")
     * @return Response
     */
    public function z_apropos()
    {
        return $this->render("page/zapropos.html.twig");
    }
    /**
     * @Route("/A_propos ", name="userapropos")
     * @return Response
     */
    public function user_apropos()
    {
        $tdat = date("Y");
        return $this->render("complement/folio.html.twig",[
            'dat'=>$tdat
        ]);
    }
}