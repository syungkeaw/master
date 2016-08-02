<?php

use yii\db\Migration;

/**
 * Handles the creation for table `item_jobs`.
 */
class m160606_053642_create_item_jobs extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%item_jobs}}', [
            'item_id' => $this->integer(),
            'job_id' => $this->integer(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%item_jobs}}');
    }
}
