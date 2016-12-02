<?php

/*  Controller for TimeSheets
 *  @author Sudhamsh Maddala <anjani.maddala@impelsys.com>
 */

namespace Timesheets\Controller;

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
    protected $_timesheetsTable;

    /*
     * This function is loaded by default for this controller. It sets the user role, id and name
     */

    function __construct() {
        /*$authManager = new \Zend\Authentication\AuthenticationService();
        if ($authManager->hasIdentity()) {
            $this->userName = $authManager->getIdentity()->username;
            $this->userId = $authManager->getIdentity()->user_id;
            $this->userRole = $authManager->getIdentity()->user_role;
        }*/
    }

    private function getTimeSheetsTable() {
        if (!$this->_timesheetsTable) {
            $sm = $this->getServiceLocator();
            $this->_timesheetsTable = $sm->get('Timesheets\Model\TimeSheetsTable');
        }
        return $this->_timesheetsTable;
    }

    
    public function dashboardAction() {
        return new ViewModel();
    }

    public function currentmonthAction() {
        $tableData = $this->getTimeSheetsTable()->getAllEntries();
       // echo "<pre>"; print_r($tableData); exit();
        return new ViewModel();
    }

    public function archivesAction() {
        return new ViewModel();
    }

    public function previousmonthAction() {
        $monthID = $this->getEvent()->getRouteMatch()->getParam('month_id');
        return new ViewModel(array(
            'month_id' => $monthID,
        ));
    }

    public function statisticsAction() {
        return new ViewModel();
    }


    
}

