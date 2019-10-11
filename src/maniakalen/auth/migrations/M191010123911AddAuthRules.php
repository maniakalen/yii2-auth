<?php

namespace maniakalen\auth\migrations;

use yii\db\Migration;
use yii\rbac\Item;

class M191010123911AddAuthRules extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        try {

            $this->execute('SET FOREIGN_KEY_CHECKS = 0');
            $time = time();
            $this->batchInsert(
                '{{%auth_item}}',
                ['name','type','description','rule_name','data','created_at','updated_at'],
                [
                    ['auth/rbac', Item::TYPE_PERMISSION, 'Access RBAC Module', NULL, NULL, $time, $time],
                    ['auth/rbac/view', Item::TYPE_PERMISSION, 'View RBAC configurations', NULL, NULL, $time, $time],
                    ['auth/rbac/create', Item::TYPE_PERMISSION, 'Create new RBAC Rules', NULL, NULL, $time, $time],
                    ['auth/rbac/update', Item::TYPE_PERMISSION, 'Update RBAC configuration', NULL, NULL, $time, $time],
                    ['auth/rbac/delete', Item::TYPE_PERMISSION, 'Delete RBAC configuration', NULL, NULL, $time, $time],
                    ['auth/rbac/remove_role_user', Item::TYPE_PERMISSION, 'Remove user from RBAC Role', NULL, NULL, $time, $time],
                    ['auth/rbac/remove_role_child', Item::TYPE_PERMISSION, 'Remove child Role', NULL, NULL, $time, $time],
                    ['auth/rbac/add_role_user', Item::TYPE_PERMISSION, 'Add user to RBAC Role', NULL, NULL, $time, $time],
                ]);
            $this->execute('SET FOREIGN_KEY_CHECKS = 1');
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            return false;
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        try {
            $this->delete('{{%auth_item}}', ['like', 'name', ['auth/rbac/%']]);
        } catch (\Exception $ex) {
            return false;
        }

        return true;
    }
}