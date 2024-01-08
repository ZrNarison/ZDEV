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
use App\Repository\SuggestionRepository;
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
     * Suppression de compte
     * @Route("/Admin/Suppression/{slugcompte}", name="comptedelete")
     * @return Response
     */
    public function deletecompte(string $slugcompte,UserRepository $repository)
    {
        $ad=$repository->findOneBy(['id'=>$slugcompte]);
        $mgr = $this -> getDoctrine()->getManager();
        $mgr->remove($ad);
        $mgr->flush();
        return $this->redirectToRoute('adminviewcompte');
    }
    /**
     * Page d'accueil
     * @Route("/",name="home")
     * @Route("/homepage")
     * @return response
     */
    public function home (Request $rq, UserRepository $info)
    {
        $sug = new Suggestion();
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
            ]);
    }
    
    /**
     * Voir les projets
     * @Route("/view-project",name="viewproject")
     * @return Response
     */
    public function viewproject(AdRepository $vproject){
        $project = $vproject->findAll();        
        return $this->render('page/viewproject.html.twig', [
            'projet' => $project
        ]);
    }

    /**
     * Ajout de projet
     * @Route("/Admin/Ad-newproject",name="newproject")
     * @Route("/Admin/Ad-newdev")
     * @return Response
     */
    public function newprojetct(Request $rqt){
        $user = $this->getUser();
        $project = new Ad();
        $mgr = $this-> getDoctrine()->getManager();
        $form = $this -> createForm(AdType::class,$project);
        $form->handleRequest($rqt);
        if($form->isSubmitted()&& $form->isValid()){
            // $user=$user->getId();
            foreach($project->getPosteurs() as $posteur){
                $posteur->setAd($project);
                $photo=$posteur->getPhoto();
                $postfile = md5(uniqid()).'.jpg';
                $directoryposteur=$this->getParameter('pos_directory');
                $posteur->setPhoto($postfile);
                $mgr->persist($posteur);
                if ($photo instanceof UploadedFile) {
                    $photo->move($directoryposteur, $postfile);
                }
                $project->setAuteur($user);
                $mgr -> flush();
            }
            $fichier = $form->get('fichiers')->getData();
            $dtsortie = $form->get('datedesortie')->getData();
            $filename = md5(uniqid()) . '.' . $fichier->guessExtension();
            $directory = $this->getParameter('dev_directory');
            $project->setFichiers($filename);
            $project->setAuteur($user);
            $mgr->persist($project);
            $mgr->flush();
            $fichier->move($directory, $filename);
            $this->addFlash("","Le project N° <strong> {$project->getId()}</strong> dont son nom est <strong>  {$project->getTitle()} </strong> à été bien enregistré !");
            return $this->redirectToRoute('newproject');
        }
        return $this->render("page/Adproject.html.twig",[
            'form'=> $form->createView()
            ]);
    }


    /**
     * Suppression de projet
     * @Route("/Admin/Suppression{slug}", name="del_project")
     * @param Ad $slug
     * @param ObjectManager $mgr
     * @return Response
     */
    public function deleteproject(string $slug,AdRepository $repository)
    {
        $ad=$repository->findOneBy(['id'=>$slug]);
        $mgr = $this -> getDoctrine()->getManager();
        $mgr->remove($ad);
        $mgr->flush();
        return $this->redirectToRoute('adminviewproject');
    }
    
    /**
     * Visualisation des projet
     * @Route("/Admin/view-adminproject",name="adminviewproject")
     * @return Response
     */
    public function viewadmproject(AdRepository $vproject){
        $project = $vproject->findAll();
        return $this->render('page/Adminviewproject.html.twig', [
            'projet' => $project
        ]);
    }
    
    /**
     * Ajout compte d'Administrateur
     * @Route("/Admin/Ad-newcompte", name="newcompte")
     * @return response
     */
    public function newcompte(Request $rqt,UserPasswordEncoderInterface $encoder)
    {
        $newUser = new User();
        $slugify = new Slugify();
        $form=$this->createForm(UserType::class, $newUser);
        $form->handleRequest($rqt);
        if($form->isSubmitted()&& $form->isValid()){
            $fichier = $form->get('ph')->getData();
            $nba = $form->get('Categorie')->getData();
            $name = $form->get('firstname')->getData();
            $fname = $form->get('lastname')->getData();
            $directory=$this->getParameter('user_directory');
            $filename=md5(uniqid()).'.'. $fichier->guessExtension();
            $newUser->setph($filename);
            $hash=$encoder->encodePassword($newUser,$newUser->getpsd());
            $newUser->setpsd($hash);
            $mng = $this -> getDoctrine()->getManager();
            $mng -> persist($newUser);
            $newUser -> addUserRole($nba);
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
    public function OneShow(string $slug,AdRepository $repository,UserRepository $user){
        $users = $user->findAll();
        $ad=$repository->findOneBy(['Slug'=>$slug]);
        return $this->render('page/onedev.html.twig', [
            'ad' => $ad,
            'info' => $users
        ]);
    }
    /**
     * Ajout de nouveau rôle
     * @Route("/Admin/admin-newrole", name="nclass")
     * @return Response
     */
    public function new_class(Request $rqt)
    {
       $nclass= new Role();
       $form = $this -> createForm(CategorieType::class,$nclass);
       $form ->handleRequest($rqt);
       if($form->isSubmitted()&& $form->isValid()){
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
     * Déconnexion
     * @Route("/Deconnexion",name="exit")
     * @return Response
     */
    public function logout_account()
    {
       return $this ->redirectToRoute('home'); 
    }

    /**
     * Editer profil d'administation
     * @Route("/Admin/Edit-Profil/Admin", name="editProfil")
     * @param Request $rqt
     * @return Response
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
     * @Route("/Admin/update-password", name="editpass")
     * @return response
     */
    public function Pass_Update(Request $rqt,UserPasswordEncoderInterface $encoder )
    {
        $oldpass = $this->getUser();
        $newpass = new UpdatePassword();
        $form = $this->createForm(EditPasswordType::class, $newpass);
        $form->handleRequest($rqt);
        if($form->isSubmitted()&& $form->isValid()){
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
    /**
     * Visualisation des suggestion
     * @Route("/view-suggestion",name="adminviewsuggestion")
     * @return Response
     */
    public function viewadmsug(SuggestionRepository $vsug){
        $vsg = $vsug->findAll();
        return $this->render('page/Adminviewsug.html.twig', [
            'vsg' => $vsg
        ]);
    }
    /**
     * Suppression de suggestion
     * @Route("/Admin/{slugsug}", name="sugdelete")
     * @return Response
     */
    public function deletesuggestion(string $slugsug,SuggestionRepository $repository)
    {
        $sug=$repository->findOneBy(['id'=>$slugsug]);
        $mgr = $this -> getDoctrine()->getManager();
        $mgr->remove($sug);
        $mgr->flush();
        return $this->redirectToRoute('adminviewsuggestion');
    }

    /**
     * @Route("/Admin/Editer/{slugproject}", name="Mod_project")
     * @return Response
     */
    public function Editproject(string $slugproject,AdRepository $repository, Request $rqt)
    {
        $ad = $repository->findOneBy(['Slug'=>$slugproject]);
        $form = $this -> createForm(EditAdType::class, $ad);
        $form -> handleRequest($rqt);
        if($form->isSubmitted()&&$form->isValid()){
            $mng = $this-> getDoctrine()->getManager();
            foreach($ad->getPosteurs() as $posteur){
                $posteur->setAd($ad);
                $photo=$posteur->getPhoto();
                $postfile = md5(uniqid()).'.jpg';
                $directoryposteur=$this->getParameter('pos_directory');
                $posteur->setPhoto($postfile);
                $mng->persist($posteur);
                if ($photo instanceof UploadedFile) {
                    $photo->move($directoryposteur, $postfile);
                }
                $mng -> flush();
            }
            $mng -> persist ($ad);
            $mng-> flush();
            return $this->redirectToRoute('adminviewproject');
        }
        return $this->render("editer/editproject.html.twig",[
            "form"=> $form->createView(),
            "ad"=>$ad
        ]);
    }

}