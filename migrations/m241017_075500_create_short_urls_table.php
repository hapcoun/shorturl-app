<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%short_urls}}`.
 */
class m241017_075500_create_short_urls_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%short_urls}}', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'user_id' => $this->integer()->notNull(),
            'original_url' => $this->text()->notNull(),
            'short_code' => $this->string(8)->unique()->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('NOW()'),
        ]);

        $this->createIndex(
            'idx_short_code',
            '{{%short_urls}}',
            'short_code'
        );

        $this->addForeignKey(
            'fk_short_url_user_id',
            '{{%short_urls}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->execute("
            CREATE FUNCTION uuidToShortCode(num BIGINT UNSIGNED) RETURNS VARCHAR(5)
            DETERMINISTIC
            BEGIN
                DECLARE characters CHAR(64) DEFAULT '0abcdefghijklmnopqrstuvwxyz123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ_-';
                DECLARE base INT DEFAULT 64;
                DECLARE encoded VARCHAR(5) DEFAULT '';
                DECLARE remainder INT;

                WHILE num > 0 DO
                    SET remainder = num MOD base;
                    SET encoded = CONCAT(SUBSTRING(characters, remainder + 1, 1), encoded);
                    SET num = FLOOR(num / base);
                END WHILE;
            
                -- WHILE LENGTH(encoded) < 5 DO
                -- SET encoded = CONCAT('0', encoded);
                -- END WHILE;
            
                RETURN encoded;
            END
        ");

        $this->execute("
            CREATE TRIGGER before_insert_short_urls
            BEFORE INSERT ON short_urls
            FOR EACH ROW
            BEGIN
                DECLARE next_id BIGINT;
                SELECT COALESCE(MAX(id), 0) + 1 INTO next_id FROM short_urls;
                SET NEW.short_code = uuidToShortCode(next_id);
            END
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("DROP TRIGGER IF EXISTS after_insert_short_code");
        $this->execute("DROP FUNCTION IF EXISTS uuidToShortCode");
        $this->dropForeignKey('fk_short_url_user_id', '{{%short_urls}}');
        $this->dropIndex('idx_short_code', '{{%short_urls}}');
        $this->dropTable('{{%short_urls}}');
    }
}
