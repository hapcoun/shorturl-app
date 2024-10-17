<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users}}`.
 */
class m241017_075457_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            'email' => $this->string(255)->notNull()->unique(),
            'username' => $this->string(255)->notNull()->unique(),
            'password' => $this->string()->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('NOW()'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('NOW()')->append('ON UPDATE NOW()'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%users}}');
    }
}
