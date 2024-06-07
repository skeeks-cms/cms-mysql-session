<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 15.07.2015
 */
use yii\db\Schema;
use yii\db\Migration;

class m240607_162718_alter_table__cms_session extends Migration
{
    public function safeUp()
    {
        //$tableName = '{{%cms_session}}';
        $tableName = 'cms_session';
        $this->alterColumn($tableName, "ip", $this->string(255));
    }

    public function safeDown()
    {
        $this->dropTable("{{%cms_session}}");
    }
}
