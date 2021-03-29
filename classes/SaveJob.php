<?php

class SaveJob{

    public $_items = null,
            $_totalQty = 0;

    public function __construct($oldJob = null)
    {
        if($oldJob)
        {
            $this->_items = $oldJob->_items;
            $this->_totalQty = $oldJob->_totalQty;
        }
    }



    public function add($id, $job)
    {
        $store_jobs = ['id' => '', 'job' => $job];
    }

// end
}