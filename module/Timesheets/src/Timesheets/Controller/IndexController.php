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
        /* $authManager = new \Zend\Authentication\AuthenticationService();
          if ($authManager->hasIdentity()) {
          $this->userName = $authManager->getIdentity()->username;
          $this->userId = $authManager->getIdentity()->user_id;
          $this->userRole = $authManager->getIdentity()->user_role;
          } */
    }

    private function getTimeSheetsTable() {
        if (!$this->_timesheetsTable) {
            $sm = $this->getServiceLocator();
            $this->_timesheetsTable = $sm->get('Timesheets\Model\TimeSheetsTable');
        }
        return $this->_timesheetsTable;
    }

    public function dashboardAction() {
        $columns_and_menu = $this->getTableColumnsAndSideMenu();
        return new ViewModel(array('menu' => $columns_and_menu['menu_array']));
    }

    public function currentmonthAction() {
        $order_by = $this->params()->fromRoute('order_by');
        $order = $this->params()->fromRoute('order');
        $page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;
        
        $order_def = 'ASC';
        $sort_var = NULL;
        if ($order_by) {
            $sort_var = "$order_by $order";
            if ($order == 'ASC') {
                $order_next = 'DESC';
            } else {
                $order_next = $order_def;
            }
        } else {
            $order_next = $order_def;
        }
        $tableData = $this->getTimeSheetsTable()->getAllEntries($sort_var);
        $columns_and_menu = $this->getTableColumnsAndSideMenu();
        //echo "<pre>"; print_r($tableData); exit();cols_array
        return new ViewModel(array(
            'time_sheets_listing' => $tableData, 'columns' => $columns_and_menu['cols_array'], 'page' => $page,
            'menu' => $columns_and_menu['menu_array'], 'order_by' => $order_by, 'order' => $order_next, 'order_n' => $order,
        ));
    }

    public function archivesAction() {
        $columns_and_menu = $this->getTableColumnsAndSideMenu();
        return new ViewModel(array('menu' => $columns_and_menu['menu_array']));
    }

    public function previousmonthAction() {
        $monthID = $this->getEvent()->getRouteMatch()->getParam('month_id');
        return new ViewModel(array(
            'month_id' => $monthID,
        ));
    }

    public function statisticsAction() {
        $columns_and_menu = $this->getTableColumnsAndSideMenu();
        return new ViewModel(array('menu' => $columns_and_menu['menu_array']));
    }
    
    public function addentryAction() {
        //$entryId = $this->getEvent()->getRouteMatch()->getParam('id');
        //$tableData = $this->getTimeSheetsTable()->getEntryById($entryId);
        //echo "<pre>"; print_r($tableData); exit();
        return new ViewModel();
    }

    public function editentryAction() {
        $entryId = $this->getEvent()->getRouteMatch()->getParam('id');
        $tableData = $this->getTimeSheetsTable()->getEntryById($entryId);
       // echo "<pre>"; print_r($tableData); exit();
        return new ViewModel();
    }

    private function getTableColumnsAndSideMenu() {
        $columns_array = array(
            array('title' => 'Date', 'sort_name' => 'date'),
            array('title' => 'In time', 'sort_name' => 'in_time'),
            array('title' => 'Out time', 'sort_name' => 'out_time'),
            array('title' => 'Total Hours', 'sort_name' => 'total_hours'),
            array('title' => 'Excess/Deficit', 'sort_name' => 'excess_deficit'),
        );

        $menu_array = array(
            //array('menu_name' => 'Dashboard', 'menu_url' => 'dashboard'),
            array('menu_name' => 'Present Month', 'menu_url' => 'currentmonth'),
            array('menu_name' => 'Archives', 'menu_url' => 'archives'),
            //array('menu_name' => 'Statistics', 'menu_url' => 'statistics'),
        );


        return array('cols_array' => $columns_array, 'menu_array' => $menu_array);
    }

}
