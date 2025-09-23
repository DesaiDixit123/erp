<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class PostCatTable extends Table
{

    public static function defaultConnectionName()
    {
        return 'blog';
    }

    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('post_cat');  
        $this->setPrimaryKey('id');    
    }
}
