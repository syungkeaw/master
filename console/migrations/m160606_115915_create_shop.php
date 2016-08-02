<?php

use yii\db\Migration;

/**
 * Handles the creation for table `shop`.
 */
class m160606_115915_create_shop extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $this->createTable('shop', [
            'id' => $this->primaryKey(),
            'shop_name' => $this->string(),
            'map' => $this->string(),
            'location' => $this->string(),
            'character' => $this->string(),
            'not_found_count' => $this->integer(),
            'status' => $this->integer(),
            'server' => $this->integer(),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'information' => $this->string(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('shop');
    }
}
