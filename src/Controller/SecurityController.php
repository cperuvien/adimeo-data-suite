<?php

namespace App\Controller;

use Elasticsearch\Common\Exceptions\NoNodesAvailableException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class SecurityController extends AdimeoDataSuiteController
{

    public function loginAction(Request $request)
    {

      try {

        $this->getIndexManager()->initStore();

        /** @var AuthenticationUtils $authenticationUtils */
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        if($error != null) {
          $errorText = get_class($error) == BadCredentialsException::class ? 'Bad credentials' : $error->getMessage();
        }
        else {
          $errorText = '';
        }

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $noCluster = false;

      } catch (NoNodesAvailableException $ex) {
        $lastUsername = '';
        $error = true;
        $noCluster = true;
        $errorText = $ex->getMessage();
      }

      return $this->render('login.html.twig', array(
        'title' => $this->get('translator')->trans('Login'),
        'main_menu_item' => 'login',
        'no_menu' => true,
        'error' => $error,
        'errorText' => $errorText,
        'lastUsername' => $lastUsername,
        'noCluster' => $noCluster,
      ));
    }

  public function logoutAction(Request $request)
  {

  }
}
