<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 03.07.2015
 */
use yii\db\Schema;
use yii\db\Migration;

class m150819_162718_create_table__comments2_message extends Migration
{
    public function up()
    {
        $tableExist = $this->db->getTableSchema("{{%comments2_message}}", true);
        if ($tableExist)
        {
            return true;
        }

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable("{{%comments2_message}}", [
            'id'                    => Schema::TYPE_PK,

            'created_by'            => Schema::TYPE_INTEGER . ' NULL',
            'updated_by'            => Schema::TYPE_INTEGER . ' NULL',

            'created_at'            => Schema::TYPE_INTEGER . ' NULL',
            'updated_at'            => Schema::TYPE_INTEGER . ' NULL',
            'published_at'          => Schema::TYPE_INTEGER . ' NULL',

            'processed_by'          => Schema::TYPE_INTEGER . ' NULL', //пользователь который принял заявку
            'processed_at'          => Schema::TYPE_INTEGER . ' NULL', //пользователь который принял заявку

            'element_id'            => Schema::TYPE_INTEGER . ' NOT NULL',
            'content_id'            => Schema::TYPE_INTEGER . ' NULL',

            'comments'              => Schema::TYPE_TEXT . ' NULL',

            'status'                => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0', //статус, активна некативна, удалено

            'ip'                    => Schema::TYPE_STRING . '(32) NULL',
            'page_url'              => Schema::TYPE_TEXT . ' NULL',

            'data_server'           => Schema::TYPE_TEXT . ' NULL',
            'data_session'          => Schema::TYPE_TEXT . ' NULL',
            'data_cookie'           => Schema::TYPE_TEXT . ' NULL',
            'data_request'          => Schema::TYPE_TEXT . ' NULL',

            'site_code'             => "CHAR(15) NULL",

            'user_name'                 => Schema::TYPE_STRING . '(255) NULL',
            'user_email'                => Schema::TYPE_STRING . '(255) NULL',
            'user_phone'                => Schema::TYPE_STRING . '(255) NULL',
            'user_city'                 => Schema::TYPE_STRING . '(255) NULL',

        ], $tableOptions);

        $this->execute("ALTER TABLE {{%comments2_message}} ADD INDEX(updated_by);");
        $this->execute("ALTER TABLE {{%comments2_message}} ADD INDEX(created_by);");

        $this->execute("ALTER TABLE {{%comments2_message}} ADD INDEX(created_at);");
        $this->execute("ALTER TABLE {{%comments2_message}} ADD INDEX(updated_at);");

        $this->execute("ALTER TABLE {{%comments2_message}} ADD INDEX(published_at);");

        $this->execute("ALTER TABLE {{%comments2_message}} ADD INDEX(processed_at);");
        $this->execute("ALTER TABLE {{%comments2_message}} ADD INDEX(processed_by);");

        $this->execute("ALTER TABLE {{%comments2_message}} ADD INDEX(status);");

        $this->execute("ALTER TABLE {{%comments2_message}} ADD INDEX(element_id);");
        $this->execute("ALTER TABLE {{%comments2_message}} ADD INDEX(content_id);");

        $this->execute("ALTER TABLE {{%comments2_message}} ADD INDEX(ip);");
        $this->execute("ALTER TABLE {{%comments2_message}} ADD INDEX(site_code);");

        $this->execute("ALTER TABLE {{%comments2_message}} ADD INDEX(user_name);");
        $this->execute("ALTER TABLE {{%comments2_message}} ADD INDEX(user_phone);");
        $this->execute("ALTER TABLE {{%comments2_message}} ADD INDEX(user_email);");
        $this->execute("ALTER TABLE {{%comments2_message}} ADD INDEX(user_city);");

        $this->execute("ALTER TABLE {{%comments2_message}} COMMENT = 'Комментарии';");

        $this->addForeignKey(
            'comments2_message_created_by', "{{%comments2_message}}",
            'created_by', '{{%cms_user}}', 'id', 'SET NULL', 'SET NULL'
        );

        $this->addForeignKey(
            'comments2_message_updated_by', "{{%comments2_message}}",
            'updated_by', '{{%cms_user}}', 'id', 'SET NULL', 'SET NULL'
        );


        $this->addForeignKey(
            'comments2_message_site_code_fk', "{{%comments2_message}}",
            'site_code', '{{%cms_site}}', 'code', 'SET NULL', 'SET NULL'
        );

        $this->addForeignKey(
            'comments2_message_element_id', "{{%comments2_message}}",
            'element_id', '{{%cms_content_element}}', 'id', 'CASCADE', 'CASCADE'
        );

        $this->addForeignKey(
            'comments2_message_content_id', "{{%comments2_message}}",
            'content_id', '{{%cms_content}}', 'id', 'SET NULL', 'SET NULL'
        );

        $this->addForeignKey(
            'comments2_message_processed_by', "{{%comments2_message}}",
            'processed_by', '{{%cms_user}}', 'id', 'SET NULL', 'SET NULL'
        );

    }

    public function down()
    {
        $this->dropForeignKey("comments2_message_created_by", "{{%comments2_message}}");
        $this->dropForeignKey("comments2_message_updated_by", "{{%comments2_message}}");
        $this->dropForeignKey("comments2_message_site_code_fk", "{{%comments2_message}}");
        $this->dropForeignKey("comments2_message_element_id", "{{%comments2_message}}");
        $this->dropForeignKey("comments2_message_content_id", "{{%comments2_message}}");
        $this->dropForeignKey("comments2_message_processed_by", "{{%comments2_message}}");

        $this->dropTable("{{%comments2_message}}");
    }
}
