<?php
/**
 * ZF2 Buch Kapitel 17
 * 
 * Das Buch "Zend Framework 2 - Von den Grundlagen bis zur fertigen Anwendung"
 * von Ralf Eggert ist im Addison-Wesley Verlag erschienen. 
 * ISBN 978-3-8273-2994-3
 * 
 * @package    User
 * @author     Ralf Eggert <r.eggert@travello.de>
 * @copyright  Alle Listings sind urheberrechtlich geschützt!
 * @link       http://www.zendframeworkbuch.de/ und http://www.awl.de/2994
 */

/**
 * namespace definition and usage
 */
namespace User\Controller;

use Zend\Http\PhpEnvironment\Response;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;
use User\Service\UserServiceInterface;

/**
 * User controller
 * 
 * Handles the user pages
 * 
 * @package    User
 */
class UserController extends AbstractActionController
{
    /**
     * @var UserServiceInterface
     */
    protected $userService;
    
    /**
     * set the user service
     *
     * @param UserServiceInterface
     */
    public function setUserService(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    
        return $this;
    }
    
    /**
     * Get the user service
     *
     * @return UserServiceInterface
     */
    public function getUserService()
    {
        return $this->userService;
    }
    
    /**
     * Handle user page
     */
    public function indexAction()
    {
        return new ViewModel(array());
    }
    
    /**
     * Handle login page
     */
    public function loginAction()
    {
        return new ViewModel(array());
    }

    /**
     * Handle logout page
     */
    public function logoutAction()
    {
        return new ViewModel(array());
    }

    /**
     * Handle register page
     */
    public function registerAction()
    {
        // prepare Post/Redirect/Get Plugin
        $prg = $this->prg(
            $this->url()->fromRoute('user/action', array('action' => 'register')), 
            true
        );

        // check PRG plugin for redirect to send
        if ($prg instanceof Response) {
            return $prg;
            
        // check PRG for redirect to process
        } elseif ($prg !== false) {
            // check for cancel
            if (isset($prg['cancel'])) {
                // Redirect to list of blogs
                return $this->redirect()->toRoute('user');
            }
            
            // register with redirected data
            $user = $this->getUserService()->save($prg);
            
            // check user
            if ($user) {
                // add messages to flash messenger
                $this->flashMessenger()->addMessage(
                    $this->getUserService()->getMessage()
                );
                
                // Redirect to home page
                return $this->redirect()->toRoute(
                    'user/action', array('action' => 'login')
                );
            }
        }
        
        // get form
        $form = $this->getUserService()->getForm('register');
        
        // add messages to flash messenger
        if ($this->getUserService()->getMessage()) {
            $this->flashMessenger()->addMessage(
                $this->getUserService()->getMessage()
            );
        }
        
        // no post or registration unsuccesful
        return new ViewModel(array(
            'form' => $form,
        ));
    }

    /**
     * Handle forbidden page
     */
    public function forbiddenAction()
    {
        return new ViewModel();
    }
}
