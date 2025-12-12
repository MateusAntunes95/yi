<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%club}}`.
 */
class m251208_164318_add_detail_column_to_club_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%club}}', 'detail', $this->json());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%club}}', 'detail');
    }
}
