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
            if ($sort_order) {
                $select->order("$sort_order");
            } else {
                $select->order('date DESC');
            }
            //$select->order('id ASC');
        });
        //echo "<pre>"; print_r($resultSet); exit();
        $resultSet->buffer();
        return $resultSet;
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

    public function saveOrUpdate($data, $id = NULL) {
        if ($id) {
            try {
                if ($this->update($data, array('id' => $id))) {
                    return array('status' => 'update_successful', 'message' => 'Successfully updated the timesheet entry.');
                } else {
                    return array('status' => 'update_failed', 'message' => 'Something went wrong. Please try again.');
                }
            } catch (\Exception $ex) {
                return array('status' => 'update_failed', 'message' => $ex->getMessage());
            }
        } else {
            try {
                if ($this->insert($data)) {
                    return array('status' => 'insert_successful', 'message' => 'Successfully inserted the timesheet entry.');
                } else {
                    return array('status' => 'insert_failed', 'message' => 'Something went wrong. Please try again.');
                }
            } catch (\Exception $ex) {
                return array('status' => 'insert_failed', 'message' => $ex->getMessage());
            }
        }
    }

    public function deleteEntry($id) {
        try {
            if ($this->delete(array('id' => (int) $id))) {
                return array('status' => 'delete_successful', 'message' => 'Successfully deleted the timesheet entry.');
            } else {
                return array('status' => 'delete_failed', 'message' => 'Something went wrong. Please try again.');
            }
        } catch (\Exception $ex) {
            return array('status' => 'delete_failed', 'message' => $ex->getMessage());
        }
    }

}
