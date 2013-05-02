<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController{

    public function indexAction(){
        return new ViewModel();
    }

	public function historieAction(){
        return new ViewModel();
    }

	public function standorteAction(){
        return new ViewModel();
    }

	public function ansprechpartnerAction(){
        return new ViewModel();
    }

	public function einkaufAction(){
        return new ViewModel();
    }

	public function qualitaetAction(){
        return new ViewModel();
    }

	public function unternehmenAction(){
        return new ViewModel();
    }

	public function kabelkonfektionAction(){
        return new ViewModel();
    }

	public function baugruppenfertigungAction(){
        return new ViewModel();
    }

	public function kunststoffumspritzungAction(){
        return new ViewModel();
    }

	public function vulkanisationAction(){
        return new ViewModel();
    }

	public function baugruppenmontageAction(){
        return new ViewModel();
    }

	public function prueftechnologienAction(){
        return new ViewModel();
    }

	public function contactAction(){
        return new ViewModel();
    }

	public function impressumAction(){
        return new ViewModel();
    }
}
