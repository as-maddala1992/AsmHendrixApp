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
        $order_by    = $this->params()->fromRoute('order_by');
        $order       = $this->params()->fromRoute('order');
        $order_def = 'ASC';
        $sort_var = NULL;
        if($order_by){
            $sort_var = "$order_by $order";
            if($order == 'ASC'){
                $order_next = 'DESC';
            } else {
                $order_next = $order_def;
            } 
            
        } else {
            $order_next = $order_def;
        }
        
        $albumsTable = $this->getMusicTable()->getAllEntries($sort_var);
        
        $columns_array = array(
          array('title' => 'ID', 'sort_name' => 'id'),
          array('title' => 'Artist', 'sort_name' => 'artist'),  
          array('title' => 'Title', 'sort_name' => 'title'),  
          array('title' => 'Created At', 'sort_name' => 'created_at'),    
        );
        

        return new ViewModel(array(
            'albums'=> $albumsTable, 
            'order_by'=> $order_by, 
            'order'=> $order_next, 
            'order_n' => $order,
            'columns' => $columns_array
                ));
    }

}
