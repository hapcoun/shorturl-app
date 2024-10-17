<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%short_url_statistics}}`.
 */
class m241017_075518_create_short_url_statistics_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%short_url_statistics}}', [
            'id' => $this->primaryKey(),
            'short_url_id' => $this->bigInteger()->unsigned()->notNull(),
            'clicks' => $this->integer()->notNull()->defaultValue(0),
            'clicked_at' => $this->timestamp()->notNull(),
        ]);

        $this->addForeignKey(
            'fk_short_url_statistics_short_url_id',
            '{{%short_url_statistics}}',
            'short_url_id',
            '{{%short_urls}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_short_url_statistics_short_url_id', '{{%short_url_statistics}}');
        $this->dropTable('{{%short_url_statistics}}');
    }
}
