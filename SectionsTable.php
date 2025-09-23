<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class SectionsTable extends Table
{
    // connection name define
    public static function defaultConnectionName()
    {
        return 'blog';
    }

    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('sections');
        $this->setPrimaryKey('id');
    }
}
