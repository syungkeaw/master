<?php

use yii\db\Migration;

/**
 * Handles the creation for table `item`.
 */
class m160606_052930_create_item extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%item}}', [
            'id' => $this->primaryKey(),
            'source_id' => $this->integer()->unique(),
            'item_name' => $this->string()->notNull(),
            'item_slot' => $this->integer(1),
            'item_slot_spare' => $this->integer(1),
            'item_num_hand' => $this->integer(1),
            'item_type_id' => $this->integer(2),
            'item_type' => $this->string(15),
            'item_class' => $this->string(15),
            'item_attack' => $this->string(),
            'item_defense' => $this->integer(4),
            'item_required_lvl' => $this->integer(2),
            'item_weapon_lvl' => $this->integer(1),
            'item_description' => $this->string(),
            'item_prefix_suffix' => $this->string(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%item}}');
    }
}
