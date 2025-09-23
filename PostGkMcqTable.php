<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class PostGkMcqTable extends Table
{
    // connection name define
    public static function defaultConnectionName()
    {
        return 'blog'; // config/app.php માં DB connection name
    }

    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('post_gk_mcq');   // DB table name
        $this->setPrimaryKey('id');    // primary key
    }
}
