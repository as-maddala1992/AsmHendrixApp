<?php

namespace Timesheets\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

class DayTable extends AbstractTableGateway {

    protected $table = 'day_table';

    /**
     * Set the database adapter
     *
     * @param DB object $adapter
     */
    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }
    
    public function getDatIdByDayName($day){
        $resultSet = $this->select(function (Select $select) use ($day) {
            $select->columns(array('id',));
            $select->where(array('day' => $day));
            //$select->limit(1);
            //$select->order('id ASC');
        });
        $result = $resultSet->toArray();
        //echo "<pre>"; print_r($resultSet); exit();
        return $result[0]['id'];
    }

   
    


}
