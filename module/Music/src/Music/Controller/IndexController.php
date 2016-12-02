<?php

/*  Controller for Music
 *  @author Sudhamsh Maddala <anjani.maddala@impelsys.com>
 */

namespace Music\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

//use Helper;
//use Zend\InputFilter\Input;
//use Zend\InputFilter\InputFilter;
//use Zend\Validator;

class IndexController extends AbstractActionController {

    //protected $userName;
    //protected $userId;
    //protected $userRole;
    protected $_musicTable;

    /*
     * This function is loaded by default for this controller. It sets the user role, id and name
     */

    function __construct() {
        /* $authManager = new \Zend\Authentication\AuthenticationService();
          if ($authManager->hasIdentity()) {
          $this->userName = $authManager->getIdentity()->username;
          $this->userId = $authManager->getIdentity()->user_id;
          $this->userRole = $authManager->getIdentity()->user_role;
          } */
    }

    private function getMusicTable() {
        if (!$this->_musicTable) {
            $sm = $this->getServiceLocator();
            $this->_musicTable = $sm->get('Music\Model\MusicTable');
        }
        return $this->_musicTable;
    }

    public function albumlistingAction() {
        //return new ViewModel();
        //die("ljkdabvldavlo");
        $albumsTable = $this->getMusicTable()->getAllEntries();
        
        $order_by = $this->params()->fromRoute('order_by') ? $this->params()->fromRoute('order_by') : 'id';
        $order = $this->params()->fromRoute('order') ? $this->params()->fromRoute('order') : 'ASC';


        return new ViewModel(array(
            'albums' => $albumsTable,
            'order_by' => $order_by,
            'order' => $order,
        ));
    }

}
