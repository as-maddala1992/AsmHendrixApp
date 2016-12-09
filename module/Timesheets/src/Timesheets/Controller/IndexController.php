<?php

/*  Controller for TimeSheets
 *  @author Sudhamsh Maddala <anjani.maddala@impelsys.com>
 */

namespace Timesheets\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as paginatorIterator;
//use Helper;
//use Zend\InputFilter\Input;
//use Zend\InputFilter\InputFilter;
//use Zend\Validator;

class IndexController extends AbstractActionController {

    //protected $userName;
    //protected $userId;
    //protected $userRole;
    protected $_timesheetsTable;
    protected $_yearTable;
    protected $_monthTable;
    protected $_dayTable;

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

    private function getYearTable() {
        if (!$this->_yearTable) {
            $sm = $this->getServiceLocator();
            $this->_yearTable = $sm->get('Timesheets\Model\YearTable');
        }
        return $this->_yearTable;
    }

    private function getMonthTable() {
        if (!$this->_monthTable) {
            $sm = $this->getServiceLocator();
            $this->_monthTable = $sm->get('Timesheets\Model\MonthTable');
        }
        return $this->_monthTable;
    }

    private function getDayTable() {
        if (!$this->_dayTable) {
            $sm = $this->getServiceLocator();
            $this->_dayTable = $sm->get('Timesheets\Model\DayTable');
        }
        return $this->_dayTable;
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
        
        //echo "<pre>";        print_r($tableData->toArray());        exit();
        
        $itemsPerPage = 5;
        $tableData->current();
        $paginator = new Paginator(new paginatorIterator($tableData));
        $paginator->setCurrentPageNumber($page)->setItemCountPerPage($itemsPerPage)->setPageRange(3);
        
        
        $columns_and_menu = $this->getTableColumnsAndSideMenu();
        //echo "<pre>"; print_r($tableData); exit();cols_array
        return new ViewModel(array(
            'time_sheets_listing' => $paginator, 'columns' => $columns_and_menu['cols_array'], 'page' => $page, 'total_ed' => $this->getTotalExcessDeficitTime($tableData),
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
        $request = $this->getRequest();
        if ($request->isPost()) {
            if ($request->getPost('now') == 'earlier') {
                $in_time = date('H:i:s', strtotime($request->getPost('hours') . ":" . $request->getPost('minutes')));
            } else {
                $in_time = date('H:i:s');
            }
            $date_array = explode('-', date('Y-m-d'));

            $data_array = array(
                'date' => date('Y-m-d'), 'day_id' => $this->getDayTable()->getDatIdByDayName(date("l")), 'month_id' => $date_array[1],
                'year_id' => $this->getYearTable()->getYearIdByYear($date_array[0]), 'in_time' => $in_time, 'status' => 1
            );

            $add_entry = $this->getTimeSheetsTable()->saveOrUpdate($data_array);
            $namespace = 'success';
            if ($add_entry['status'] == 'insert_failed') {
                $namespace = 'error';
            }

            $this->flashMessenger()->setNamespace($namespace)->addMessage($add_entry['message']);
            return $this->redirect()->toRoute("currentmonth");
        } else {
            return $this->redirect()->toRoute("currentmonth");
        }
    }

    public function editentryAction() {
        $entryId = $this->getEvent()->getRouteMatch()->getParam('id');
        $tableData = $this->getTimeSheetsTable()->getEntryById($entryId);
        echo "<pre>";
        print_r($tableData);
        exit();
        return new ViewModel();
    }

    public function deleteentryAction() {
        $entryId = $this->getEvent()->getRouteMatch()->getParam('id');
        $del_entry = $this->getTimeSheetsTable()->deleteEntry($entryId);
        
        $namespace = 'success';
        if ($del_entry['status'] == 'insert_failed') {
            $namespace = 'error';
        }

        $this->flashMessenger()->setNamespace($namespace)->addMessage($del_entry['message']);
        return $this->redirect()->toRoute("currentmonth");
    }

    public function checkoutAction() {

        $entryId = $this->getEvent()->getRouteMatch()->getParam('id');
        $tableData = $this->getTimeSheetsTable()->getEntryById($entryId);
        $total_time = strtotime(date('H:i:s')) - strtotime($tableData[0]['in_time']);
        $hours = round($total_time / 3600, 2);
        
        if($tableData[0]['day_id'] == 6 || $tableData[0]['day_id'] == 7){
            $excess_deficit = $hours;
        } else {
            $excess_deficit = $hours - 8.75;
        }

        $data_array = array(
            'out_time' => date('H:i:s'), 'total_hours' => $hours,
            'excess_deficit' => $excess_deficit, 'status' => 0
        );

        $update_entry = $this->getTimeSheetsTable()->saveOrUpdate($data_array, $tableData[0]['id']);
        $namespace = 'success';
        if ($update_entry['status'] == 'insert_failed') {
            $namespace = 'error';
        }
        $this->flashMessenger()->setNamespace($namespace)->addMessage($update_entry['message']);
        return $this->redirect()->toRoute("currentmonth");
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
    
    private function getTotalExcessDeficitTime($timesheetData){
        $data = $timesheetData->toArray();
        $val = 0;
        foreach ($data as $value){
            $val = $val + $value['excess_deficit'];
        }
        
        return $val;
    }

}
