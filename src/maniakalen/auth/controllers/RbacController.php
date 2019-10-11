<?php

namespace maniakalen\auth\controllers;

use maniakalen\auth\models\AuthControlManager;
use maniakalen\auth\models\NotTheAuthUserAccessRule;
use Yii;
use maniakalen\auth\models\AuthItem;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\rbac\Permission;
use yii\rbac\Role;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * RbacController implements the CRUD actions for AuthItem model.
 */
class RbacController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    ['actions' => ['index'], 'allow' => true, 'permissions' => ['auth/rbac']],
                    ['actions' => ['view'], 'allow' => true, 'permissions' => ['auth/rbac/view']],
                    ['actions' => ['create'], 'allow' => true, 'permissions' => ['auth/rbac/create']],
                    ['actions' => ['update'], 'allow' => true, 'permissions' => ['auth/rbac/update']],
                    ['actions' => ['delete'], 'allow' => true, 'permissions' => ['auth/rbac/delete']],
                    ['actions' => ['remove-role-user'], 'allow' => true, 'permissions' => ['auth/rbac/remove_role_user']],
                    ['actions' => ['remove-role-child'], 'allow' => true, 'permissions' => ['auth/rbac/remove_role_child']],
                    ['actions' => ['add-role-user'], 'allow' => true, 'permissions' => ['auth/rbac/add_role_user']],
                    ['actions' => ['add-users-role'], 'allow' => true, 'permissions' => ['auth/rbac/add_role_user']],
                    ['actions' => ['add-role-child'], 'allow' => true, 'permissions' => ['auth/rbac/update']],
                    ['actions' => ['set-role-rule'], 'allow' => true, 'permissions' => ['auth/rbac/update']],
                    ['actions' => ['set-role-rule-post'], 'allow' => true, 'permissions' => ['auth/rbac/update']],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                    'remove-role-user' => ['post'],
                    'add-users-role' => ['post'],
                ],
            ]
        ];
    }

    /**
     * Lists all AuthItem models.
     * @return mixed
     */
    public function actionCreatePermission()
    {

    }

    /**
     * Lists all AuthItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = AuthItem::find();
        $query->andWhere(['type' => Role::TYPE_ROLE]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $columns = $this->getACModel()->getRolesListColumns();
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'columns' => $columns
        ]);
    }

    /**
     * Displays a single AuthItem model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $permDataProvider = new ActiveDataProvider([
            'query' => $this->getACModel()->getChildAuthItemsQuery($id)->andWhere(['type' => Permission::TYPE_PERMISSION]),
        ]);
        $rolesDataProvider = new ActiveDataProvider([
            'query' => $this->getACModel()->getChildAuthItemsQuery($id, Role::TYPE_ROLE),
        ]);
        $usersDataProvider = new ActiveDataProvider([
            'query' => $this->getACModel()->getRoleUsersQuery($id),
        ]);

        return $this->render('view', [
            'model' => $this->getACModel()->findAuthItemModel($id),
            'permDataProvider' => $permDataProvider,
            'userDataProvider' => $usersDataProvider,
            'rolesDataProvider' => $rolesDataProvider,
        ]);
    }

    /**
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = \Yii::createObject(AuthItem::class);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $permissions = Yii::$app->request->post('permissions', []);
            $this->getACModel()->updatePermissionsForRoleId($model->name, $permissions);
            return $this->redirect(['view', 'id' => $model->name]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'permissions' => AuthItem::findAll(['type' => Role::TYPE_PERMISSION]),
				'rules' => $this->getACModel()->getRulesList(),
				'children' => [],
                'roles' => new ActiveDataProvider([
                    'query' => AuthItem::find()
                        ->innerJoin(['c' => 'auth_item_child'], AuthItem::tableName() . '.name = c.child')
                        ->where(['type' => Role::TYPE_ROLE, 'c.parent' => $model->name])
                ]),
            ]);
        }
    }

    /**
     * Updates an existing AuthItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->getACModel()->findAuthItemModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $permissions = Yii::$app->request->post('permissions', []);
            $this->getACModel()->updatePermissionsForRoleId($model->name, $permissions);
            return $this->redirect(['view', 'id' => $model->name]);
        } else {
            $usersDataProvider = new ActiveDataProvider([
                'query' => $this->getACModel()->getRoleUsersQuery($id),
            ]);
            return $this->render('update', [
                'model' => $model,
                'permissions' => AuthItem::find()
                    ->where(['type' => Role::TYPE_PERMISSION])
                    ->orderBy(['description' => SORT_ASC])
                    ->all(),
                'children' => $this->getACModel()->getChildAuthItems($id),
                'userDataProvider' => $usersDataProvider,
                'rules' => $this->getACModel()->getRulesList(),
                'roles' => new ActiveDataProvider([
                    'query' => AuthItem::find()
                        ->innerJoin(['c' => 'auth_item_child'], AuthItem::tableName() . '.name = c.child')
                        ->where(['type' => Role::TYPE_ROLE, 'c.parent' => $model->name])
                ]),
            ]);
        }
    }

    /**
     * Deletes an existing AuthItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->getACModel()->findAuthItemModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionRemoveRoleUser()
    {
        $id = Yii::$app->request->get('id');
        $role = Yii::$app->request->post('role');

        return json_encode(['status' => $this->getACModel()->removeRoleAssignment($role, $id)]);
    }

    public function actionAddRoleUser($id)
    {

        return $this->renderAjax('_usersList', [
            'users' => new ActiveDataProvider([
                'query' => $this->getACModel()->getNotRoleUsersQuery($id),
            ])
        ]);
    }

    public function actionAddRoleChild($id)
    {
        $roles = Yii::$app->request->post('roles');
        if ($roles) {
            $manager = Yii::$app->authManager;
            $parent = $manager->getRole($id);
            if ($parent) {
                foreach ($roles as $role) {
                    $child = $manager->getRole($role);
                    $manager->addChild($parent, $child);
                }
                return json_encode(['status' => true]);
            }
            return json_encode(['status' => false]);
        }
        return $this->renderAjax('_rolesList', [
            'roles' => new ActiveDataProvider([
                'query' => $this->getACModel()->getRolesNotChildQuery($id),
                'pagination' => [
                    'pageSize' => 0,
                ],
            ])
        ]);
    }

    public function actionRemoveRoleChild($id)
    {
        $role = Yii::$app->request->post('role');

        return json_encode(['status' => $this->getACModel()->removeRoleChild($role, $id)]);
    }
    public function actionSetRoleRule($id)
    {
        $model = $this->getACModel()->findAuthItemModel($id);
        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return \Yii::t('rbac', 'All done') . '<script>location.reload();</script>';
        }
        return $this->renderAjax('_formRulesDropdown', [
            'model' => $model,
            'rulesList' => array_merge(['' => Yii::t('yii', 'Choose option')], $this->getACModel()->getRulesList()),
            'inter' => [
                'category' => 'rbac',
                'key' => 'rbac.select_rule_to_apply'
            ],
            'close' => [
                'intel' => [
                    'category' => 'rbac',
                    'key' => 'rbac.close'
                ]
            ],
            'confirm' => [
                'id' => 'modal_set_rule',
                'inter' => [
                    'category' => 'rbac',
                    'key' => 'rbac.set_rule'
                ]
            ]
        ]);
    }
    public function actionSetRoleRulePost($id)
    {
        $model = $this->getACModel()->findAuthItemModel($id);
        $data = Yii::$app->request->post('rule_name')?:null;

        $model->rule_name = $data;

        return json_encode(['status' => $model->save()]);
    }
    public function actionAddUsersRole($id)
    {
        $userIds = Yii::$app->request->post('users');
        $done = $this->getACModel()->addRoleAssignments($id, $userIds);
        return json_encode(['status' => (int)$done]);
    }
    private function getACModel()
    {
        return $this->module->controlManager;
    }
}
