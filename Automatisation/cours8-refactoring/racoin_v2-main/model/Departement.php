<?php

namespace model;

use Illuminate\Database\Eloquent\Model;

class Departement extends Model {
    protected $table = 'departement';
    protected $primaryKey = 'id_departement';
    public $timestamps = false;
}

?>