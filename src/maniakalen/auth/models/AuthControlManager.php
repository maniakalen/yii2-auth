<?php

namespace maniakalen\auth\models;

use yii\db\ActiveRecordInterface;
use yii\di\Instance;
use yii\helpers\Html;
use yii\rbac\Permission;
use yii\rbac\Role;
use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;
use Yii;

/**
 * Class AuthControlManager
 * @package backend\models
 *
 * Class to implement the logic of roles/permissions control.
 * Generates different combinations of queries for ActiveDataProvider,
 * defines column combinations for DataGrid widget assign roles to users,
 * removes roles from users, etc.
 */
class AuthControlManager
{
    public $userClass;

    public function init()
    {
        parent::init();
        $this->userClass = Instance::ensure($this->userClass, IdentityInterface::class);
        $this->userClass = Instance::ensure($this->userClass, ActiveRecordInterface::class);
    }
    /**
     * Returns an instance of AuthItem corresponding to the id provided
     * Throws exception if no model is found
     *
     * @param int $id
     * @return AuthItem
     * @throws NotFoundHttpException
     */
    public function findAuthItemModel($id)
    {
        if (($model = AuthItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Returns array list of columns for the roles list in auth/rbac/index page
     * depending on the permissions some action buttons are shown for the user to interact with the list
     *
     * @return array
     */
    public function getRolesListColumns()
    {
        $columns = [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'description:ntext',
            'rule_name',
            'data:ntext',
            'created_at:datetime',
            'updated_at:datetime',
        ];
        $canView = Yii::$app->user->can('auth/rbac/view');
        $canUpdate = Yii::$app->user->can('auth/rbac/update');
        $canDelete = Yii::$app->user->can('auth/rbac/delete');
        if ($canView || $canUpdate || $canDelete) {
            $actions = [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function($url, $model, $key) use ($canView)
                        {
                            if (!$canView) {
                                return '';
                            }
                            $options = [
                                'title' => Yii::t('yii', 'View'),
                                'aria-label' => Yii::t('yii', 'View'),
                                'data-pjax' => '0',
                                'id' => 'view_' . $model->name
                            ];
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, $options);
                        },
                    'update' => function($url, $model, $key) use ($canUpdate)
                    {
						if (!$canUpdate) {
							return '';
						}
                        $options = [
                            'title' => Yii::t('yii', 'Update'),
                            'aria-label' => Yii::t('yii', 'Update'),
                            'data-pjax' => '0',
                            'id' => 'update_' . $model->name
                        ];
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, $options);
                    },
                    'delete' => function($url, $model, $key) use ($canDelete)
                    {
						if (!$canDelete) {
							return '';
						}
                        $options = [
                            'title' => Yii::t('yii', 'Delete'),
                            'aria-label' => Yii::t('yii', 'Delete'),
                            'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                            'id' => 'delete_' . $model->name
                        ];
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, $options);
                    }
                ]
            ];
            $columns[] = $actions;
        }
        return $columns;

    }
    
    /**
     * Returns array list of columns for the  related roles list
     *
     * @return array
     */
    public function getRolesRelatedListColumns()
    {
        $columns = [
            ['class' => 'yii\grid\SerialColumn'],
    
            'name',
           [
               //'authRoleRelated'
               'label'  => Yii::t('app', 'hgigoc.rolesrelated'),
               'value' => function ($data) {
               //return $data->authRoleRelated; // $data['name'] for array data, e.g. using SqlDataProvider.
               $authRoleRelated_sting = '';
               if(count($data->authRoleRelated)>0){
                   foreach ($data->authRoleRelated as $value){
                       $authRoleRelated_sting .= $value->role_related.', ';
                   }
                   $authRoleRelated_sting = substr_replace($authRoleRelated_sting, "", -2);
               }               
               return $authRoleRelated_sting;
               },
                
           ],
        ];
        $canView = Yii::$app->user->can('rolerelated/view');
        $canUpdate = Yii::$app->user->can('rolerelated/update');
        $canDelete = Yii::$app->user->can('rolerelated/delete');
        $canDelete = false;
        if ($canView || $canUpdate || $canDelete) {
            $actions = [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function($url, $model, $key) use ($canView)
                    {
                        if (!$canView) {
                            return '';
                        }
                        $options = [
                            'title' => Yii::t('yii', 'View'),
                            'aria-label' => Yii::t('yii', 'View'),
                            'data-pjax' => '0',
                            'id' => 'view_' . $model->name
                        ];
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, $options);
                    },
                    'update' => function($url, $model, $key) use ($canUpdate)
                    {
                        if (!$canUpdate) {
                            return '';
                        }
                        $options = [
                            'title' => Yii::t('yii', 'Update'),
                            'aria-label' => Yii::t('yii', 'Update'),
                            'data-pjax' => '0',
                            'id' => 'update_' . $model->name
                        ];
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, $options);
                    },
                    'delete' => function($url, $model, $key) use ($canDelete)
                    {
                        if (!$canDelete) {
                            return '';
                        }
                        $options = [
                            'title' => Yii::t('yii', 'Delete'),
                            'aria-label' => Yii::t('yii', 'Delete'),
                            'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                            'id' => 'delete_' . $model->name
                        ];
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, $options);
                    }
                ]
                ];
            $columns[] = $actions;
        }
        return $columns;
    
    }
    
    /**
     * Returns array list of columns for update list of related roles
     *
     * @param string $role
     * 
     * @return array
     */
    public function getRolesRelatedForUpdateColumns(array $params=null)
    {
        $role = $params['role'];
        
        $columns = [
            ['class' => 'yii\grid\SerialColumn'],    
            [
                'label'  => Yii::t('app', 'hgigoc.rolesrelated'),
                'attribute'  => 'role_related',                
            ],
            [
                'class' => 'yii\grid\CheckboxColumn',
                'checkboxOptions' => function ($model, $key, $index, $column) {
                    return [
                        'value' => $model['role_related'], 
                        'checked' => $model['is_checked'],
                        'data-rolerelated-id' => $model['id'],
                        'class' => 'rolerelated-update-checkbox',
                    ];
                },
                'header' => Html::checkBox('role', false, [
                    'label' => Yii::t('app', 'hgigoc.rolesrelated.assign'),
                    'class' => 'hidden',
                    'value' => $role,
                    'id' => 'rolerelated-role-id',
                ]),                
                
            ],
        ];
        return $columns;
    
    }

    /**
     * Returns child items (permissions) for the given role
     * @param int $id
     * @return array
     */
    public function getChildAuthItems($id)
    {
        $children = AuthItemChild::findAll(['parent' => $id]);
        $names = [];
        foreach ($children as $child) {
            $names[] = $child->getAttribute('child');
        }

        return $names;
    }

    /**
     * Prepares a query for ActiveDataProvider to use for generating GridView
     * @param int $id
     * @return \yii\db\ActiveQuery
     */
    public function getChildAuthItemsQuery($id, $type = null)
    {
        $names = $this->getChildAuthItems($id);
        $query = AuthItem::find();
        $where = ['name' => $names];
        if ($type) {
            $where['type'] = $type;
        }
        $query->where($where);

        return $query;
    }

    public function getRolesNotChildQuery($id)
    {
        $list = [$id];
        foreach (AuthItemChild::findAll(['parent' => $id]) as $item) {
            $list[] = $item->child;
        }

        return AuthItem::find()
            ->where(sprintf('name NOT IN (\'%s\') AND type = %d', implode("','", $list),Role::TYPE_ROLE));
    }
    /**
     * Changes the permissions assigned to a role.
     *
     * @param int $id
     * @param array $permissions
     */
    public function updatePermissionsForRoleId($id, $permissions)
    {
        $auth = Yii::$app->getAuthManager();
        $item = $auth->getRole($id);
        $children = $auth->getChildren($id);
        foreach ($children as $child) {
            if ($child instanceof Permission) {
                $auth->removeChild($item, $child);
            }
        }
        foreach ($permissions as $permission) {
            if ($permission != '0') {
                $perm = $auth->getPermission($permission);
                $auth->addChild($item, $perm);
            }
        }
    }

    /**
     * Returns an ActiveQuery object for a GridView to prepare a list of all users assigned to a role
     *
     * @param string $id name of role to search for
     * @return \yii\db\ActiveQuery
     */
    public function getRoleUsersQuery($id)
    {
        $userClass = $this->userClass;
        $query = $userClass::find();
        $query->innerJoin('auth_assignment', 'id = auth_assignment.user_id');
        $query->where(['status' => 1, 'auth_assignment.item_name' => $id]);
        return $query;
    }

    /**
     * Returns ActiveQuery object defining extraction for all users not assigned to a role
     *
     * @param string $id name of role to ignore
     * @return \yii\db\ActiveQuery
     */
    public function getNotRoleUsersQuery($id)
    {
        $userClass = $this->userClass;
        $query = $userClass::find();
        $query->where(['status' => 1]);
        $query->andWhere(sprintf("id not IN (%s)", AuthAssignment::find()
            ->select('user_id')
            ->where(['item_name' => $id])
            ->createCommand()
            ->getRawSql()));
        return $query;
    }

    /**
     * Assign a role to a list of users provided
     * @param string $role
     * @param array $userIds array of user ids
     * @return bool
     * @throws \Exception
     */
    public function addRoleAssignments($role, array $userIds)
    {
        if (count($userIds) == 0) {
            throw new \Exception("No user ids provided");
        }
        $auth = Yii::$app->getAuthManager();
        $role = $auth->getRole($role);
        if (!($role instanceof Role)) {
            throw new \Exception("Role not found.");
        }
        foreach ($userIds as $id) {
            $auth->assign($role, $id);
        }
        return true;
    }

    /**
     * Revokes a role for a given user
     * @param string $role
     * @param int $userId
     * @return bool
     * @throws \Exception
     */
    public function removeRoleAssignment($role, $userId)
    {
        $auth = Yii::$app->getAuthManager();
        $role = $auth->getRole($role);
        if (!($role instanceof Role)) {
            throw new \Exception("Role not found.");
        }
        return $auth->revoke($role, $userId);
    }

    /**
     * Returns a list of rules defined in the system
     * @return array
     */
    public function getRulesList()
    {
        $rules = [];
        foreach (AuthRule::find()->all() as $rule) {
            $rules[$rule->name] = $rule->name;
        }
        return $rules;
    }

    /**
     * Since having an empty rule_name for a role is not allowed we need to unset it from the request before we load it
     * as AuthItem data
     *
     * @return array|mixed
     */
    public function getRoleUpdateRequest()
    {
        $post = Yii::$app->request->post();

        if (isset($post['AuthItem']) && isset($post['AuthItem']['rule_name']) && $post['AuthItem']['rule_name'] == '') {
            unset($post['AuthItem']['rule_name']);
        }
        return $post;
    }

    /**
     * Returns array with the names of all defined roles in the system
     *
     * @return array
     */
    public function getRolesNamesList()
    {
        $names = [];
        foreach (AuthItem::find()->select('name')->where(['type' => Role::TYPE_ROLE])->all() as $role) {
            $names[$role->name] = $role->name;
        }
        return $names;
    }

    public function removeRoleChild($parent, $child)
    {
        try {
            $manager = Yii::$app->authManager;
            $parent = $manager->getRole($parent);
            $child = $manager->getRole($child);
            return $manager->removeChild($parent, $child);
        } catch (\Exception $ex) {
            return false;
        }
    }

}