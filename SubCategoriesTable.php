<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class SubCategoriesTable extends Table
{
    // connection name define
    public static function defaultConnectionName()
    {
        return 'blog'; 
    }

    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('sub_categories');   
        $this->setPrimaryKey('id');   
    }
}
