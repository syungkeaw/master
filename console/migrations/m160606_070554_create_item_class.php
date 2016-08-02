<?php

use yii\db\Migration;

/**
 * Handles the creation for table `item_class`.
 */
class m160606_070554_create_item_class extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%item_class}}', [
            'id' => $this->primaryKey(),
            'class_name' => $this->string(),
            'class_type_id' => $this->integer(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%item_class}}');
    }
}
