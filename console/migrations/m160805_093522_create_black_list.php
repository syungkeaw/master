<?php

use yii\db\Migration;

/**
 * Handles the creation for table `black_list`.
 */
class m160805_093522_create_black_list extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('black_list', [
            'id' => $this->primaryKey(),
            'character_name' => $this->string()->notNull(),
            'reason' => $this->string()->notNull(),
            'server' => $this->integer()->notNull(),
            'parent_id' => $this->integer(),
            'youtube' => $this->string(),
            'facebook' => $this->string(),
            'status' => $this->integer(),
            'bad_point' => $this->integer(),
            'good_point' => $this->integer(),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('black_list');
    }
}
