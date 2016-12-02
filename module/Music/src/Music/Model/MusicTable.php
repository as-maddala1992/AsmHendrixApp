<?php

namespace Music\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

class MusicTable extends AbstractTableGateway {

    protected $table = 'music';

    /**
     * Set the database adapter
     *
     * @param DB object $adapter
     */
    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

   
    public function getAllEntries() {
        //die("kljbikvbadv");
        $resultSet = $this->select(function (Select $select) {
            $select->order('id ASC');
        });
        //echo "<pre>"; print_r($resultSet); exit();
        return $resultSet->toArray();
    }


}
