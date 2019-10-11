<?php

namespace maniakalen\auth\models;

use Yii;
use yii\base\Object;
use yii\db\Query;
use yii\rbac\Role;

/**
 * This is the model class for table "auth_role_related".
 *
 * @property integer $id
 * @property string $role
 * @property string $role_related
 *
 * @property AuthItem $roleRelated
 * @property AuthItem $role0
 */
class AuthRoleRelated extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_role_related';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role', 'role_related'], 'required'],
            [['role', 'role_related'], 'string', 'max' => 64],
            [['role', 'role_related'], 'unique', 'targetAttribute' => ['role', 'role_related'], 'message' => 'The combination of Role and Role Related has already been taken.'],
            [['role_related'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::className(), 'targetAttribute' => ['role_related' => 'name']],
            [['role'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::className(), 'targetAttribute' => ['role' => 'name']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'role' => Yii::t('app', 'Role'),
            'role_related' => Yii::t('app', 'Role Related'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoleRelated()
    {
        return $this->hasOne(AuthItem::className(), ['name' => 'role_related']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole0()
    {
        return $this->hasOne(AuthItem::className(), ['name' => 'role']);
    }
    
    /**
     * Get All Related Roles by Role name (id) assigned and non asigned with status (isChecked)
     * 
     * @param string $role
     * @param boolean $isQuery
     */
    public static function getRolesRelatedByRoleFull($role, $isQuery=false){
        $query = (new Query())
        ->select(['rel.id', 'r.name role_related', 'IF(rel.role_related IS NOT NULL, 1, 0) is_checked'])
        ->from('auth_item r')
        ->leftJoin('auth_role_related rel', 'rel.role_related = r.name AND rel.role = :role')
        ->where(['r.type' => Role::TYPE_ROLE])
        ->andWhere('r.name != :role')
        ->addParams([':role' => $role]);
        
        if(!$isQuery){
            $query = $query->all();
        }
        
        return $query;        
    }
    
    /**
     * Get Related Roles by Role name (id), assigned only
     * 
     * @param string $role
     */
    public static function getRolesRelatedByRoles(array $roles){
        $query = (new Query())
        ->select(['rel.role_related name'])
        ->from('auth_role_related rel')
        ->where(['rel.role' => $roles])
        ->andWhere(['NOT IN', 'rel.role_related', $roles])
        ->groupBy(['rel.role_related'])
        ->all();
        
        return $query;        
    }
    
}
