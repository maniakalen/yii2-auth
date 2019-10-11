# Yii2 Auth to give the yii2-rbac module a GUI

Yii2 Auth extends the yii2-rbac and is useful for when you use database for defining roles and permissions.

To execute migrations:
    
    php yii migrate/up --migration-namespaces=maniakalen\\auth\\migrations
    
This will import all the permissions required to manipulate the database.

Module configuration
    
    'auth' => [
        'class' => \maniakalen\auth\Module::class,
        'components' => [
            'controlManager' => [
                'class' => \maniakalen\auth\models\AuthControlManager::class,
                'userClass' => User::class
            ]
        ]
    ]
    
The module defines rbac category for translations.     