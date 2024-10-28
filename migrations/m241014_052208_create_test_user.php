<?php

use yii\db\Migration;
use app\models\User;

/**
 * Class m241014_052208_create_test_user
 */
class m241014_052208_create_test_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $role = Yii::$app->authManager->createRole('admin');
        $role->description = 'Админ';
        Yii::$app->authManager->add($role);

        $role = Yii::$app->authManager->createRole('user');
        $role->description = 'Юзер';
        Yii::$app->authManager->add($role);

        $testUser = new User();
        $testUser->username = 'admin';
        $testUser->setPassword('admin');
        $testUser->generateAuthKey();
        try {
            $testUser->save();
            $userRole = Yii::$app->authManager->getRole('admin');
            Yii::$app->authManager->assign($userRole, $testUser->getId());
        } catch(Exception $e) {
            echo $e;
        }

        $testUser2 = new User();
        $testUser2->username = 'demo';
        $testUser2->setPassword('demo');
        $testUser2->generateAuthKey();
        try {
            $testUser2->save();
            $userRole = Yii::$app->authManager->getRole('user');
            Yii::$app->authManager->assign($userRole, $testUser2->getId());
        } catch(Exception $e) {
            echo $e;
        }

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $admin = User::findByUsername('admin');

        if (!empty($admin)) {
            $admin->delete();
        }

        $demo = User::findByUsername('demo');

        if (!empty($demo)) {
            $demo->delete();
        }

        Yii::$app->authManager->removeAll();

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241014_052208_create_test_user cannot be reverted.\n";

        return false;
    }
    */
}
