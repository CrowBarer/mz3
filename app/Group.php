<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'groups';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @param array $data
     */
    public function setProperties(array $data) :void
    {
        $this->group_id = $data['GroupID'];
        $this->name = $data['GroupName'];
        $this->order = $data['GroupOrderID'];
    }


}
