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

   
    public function getAllEntries($sort_var = NULL) {
        //die("kljbikvbadv");
        $resultSet = $this->select(function (Select $select) use($sort_var) {
            $select->columns(array('id', 'artist', 'title', 'created_at'));
            
            
            if($sort_var){
                $select->order("$sort_var");
            }
            //$select->order('id ASC');
        });
        //echo "<pre>"; print_r($resultSet); exit();
        return $resultSet->toArray();
    }


}
