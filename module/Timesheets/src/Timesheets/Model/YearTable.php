<?php

namespace Timesheets\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

class YearTable extends AbstractTableGateway {

    protected $table = 'year_table';

    /**
     * Set the database adapter
     *
     * @param DB object $adapter
     */
    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getYearIdByYear($year){
        $resultSet = $this->select(function (Select $select) use ($year) {
            $select->columns(array('id',));
            $select->where(array('year' => $year));
            //$select->limit(1);
            //$select->order('id ASC');
        });
        $result = $resultSet->toArray();
        //echo "<pre>"; print_r($resultSet); exit();
        return $result[0]['id'];
    }
    


}
