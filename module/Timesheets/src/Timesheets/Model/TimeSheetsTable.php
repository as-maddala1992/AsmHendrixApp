<?php

namespace Timesheets\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

class TimeSheetsTable extends AbstractTableGateway {

    protected $table = 'time_sheet_table';

    /**
     * Set the database adapter
     *
     * @param DB object $adapter
     */
    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

   
    public function getAllEntries($sort_order = NULL) {
        
        $resultSet = $this->select(function (Select $select) use ($sort_order) {
            $select->columns(array('id', 'date', 'in_time', 'out_time', 'total_hours', 'excess_deficit', 'status'));
            if($sort_order){
                $select->order("$sort_order");
            } else {
                $select->order('date DESC');
            }
            //$select->order('id ASC');
        });
        //echo "<pre>"; print_r($resultSet); exit();
        return $resultSet->toArray();
    }
    
    public function getEntryById($id) {
        
        $resultSet = $this->select(function (Select $select) use ($id) {
            $select->columns(array('id', 'date', 'in_time', 'out_time', 'total_hours', 'excess_deficit'));
            //$select->order('id ASC');
            $select->where(array('id' => $id));
        });
        //echo "<pre>"; print_r($resultSet); exit();
        return $resultSet->toArray();
    }


}
