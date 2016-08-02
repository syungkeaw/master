<?php

use yii\db\Migration;

/**
 * Handles the creation for table `job`.
 */
class m160606_053650_create_job extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%job}}', [
            'id' => $this->primaryKey(),
            'job_name' => $this->string(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%job}}');
    }
}
