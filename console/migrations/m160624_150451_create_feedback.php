<?php

use yii\db\Migration;

/**
 * Handles the creation for table `feedback`.
 */
class m160624_150451_create_feedback extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('feedback', [
            'id' => $this->primaryKey(),
            'shop_item_id' => $this->integer(),
            'feedback_id' => $this->integer(),
            'feedback_by' => $this->integer(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('feedback');
    }
}
