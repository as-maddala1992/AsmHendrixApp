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

   
    public function getAllEntries() {
        
        $resultSet = $this->select(function (Select $select) {
           // $select->columns(array('day', 'year_month_id'));
            $select->order('id ASC');
        });
        //echo "<pre>"; print_r($resultSet); exit();
        return $resultSet->toArray();
    }


}
