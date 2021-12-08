<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 15.07.2015
 */
use yii\db\Schema;
use yii\db\Migration;

class m211208_162718_create_table__cms_session extends Migration
{
    public function safeUp()
    {
        $tableName = '{{%cms_session}}';
        $tableExist = $this->db->getTableSchema($tableName, true);
        if ($tableExist)
        {
            return true;
        }

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

            $this->createTable($tableName, [
                'id' => $this->string(40),

                'expire' => $this->integer(),
                'data' => "blob",

                'cms_site_id' => $this->integer(),
                'cms_user_id' => $this->integer(),

                'updated_at' => $this->integer(),
                'ip' => $this->string(15),
                'https_user_agent' => $this->string(255),

            ], $tableOptions);

            $this->addPrimaryKey("id", $tableName, "id");
            $this->createIndex("{$tableName}__cms_site_id", $tableName, "cms_site_id");
            $this->createIndex("{$tableName}__cms_user_id", $tableName, "cms_user_id");

            $this->addForeignKey(
                "{$tableName}__cms_user_id", $tableName,
                'cms_user_id', '{{%cms_user}}', 'id', 'CASCADE', 'CASCADE'
            );

            $this->addForeignKey(
                "{$tableName}__cms_site_id", $tableName,
                'cms_site_id', '{{%cms_site}}', 'id', 'CASCADE', 'CASCADE'
            );

        } else {
            throw new Exception('!!!');
        }



        $this->createIndex('idx_log_level', $tableName, 'level');
        $this->createIndex('idx_log_category', $tableName, 'category');

    }

    public function safeDown()
    {
        $this->dropTable("{{%log_db_target}}");
    }
}
