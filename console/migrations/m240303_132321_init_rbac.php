<?php

use yii\db\Migration;

/**
 * Class m240303_132321_init_rbac
 */
class m240303_132321_init_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$auth = Yii::$app->authManager;

        // add "createPerson" permission
        $createPerson = $auth->createPermission('createPerson');
        $createPerson->description = 'Create a Person';
        $auth->add($createPerson);

        // add "updatePerson" permission
        $updatePerson = $auth->createPermission('updatePerson');
        $updatePerson->description = 'Update Person';
        $auth->add($updatePerson);

        // add "createDocument" permission
        $createDocument = $auth->createPermission('createDocument');
        $createDocument->description = 'Create a Document';
        $auth->add($createDocument);

        // add "updateDocument" permission
        $updateDocument = $auth->createPermission('updateDocument');
        $updateDocument->description = 'Update Document';
        $auth->add($updateDocument);
        
        // add "createDocumentType" permission
        $createDocumentType = $auth->createPermission('createDocumentType');
        $createDocumentType->description = 'Create a DocumentType';
        $auth->add($createDocumentType);

        // add "updateDocumentType" permission
        $updateDocumentType = $auth->createPermission('updateDocumentType');
        $updateDocumentType->description = 'Update DocumentType';
        $auth->add($updateDocumentType);
        
        // add "createUnit" permission
        $createUnit = $auth->createPermission('createUnit');
        $createUnit->description = 'Create a Unit';
        $auth->add($createUnit);

        // add "updateUnit" permission
        $updateUnit = $auth->createPermission('updateUnit');
        $updateUnit->description = 'Update Unit';
        $auth->add($updateUnit);

        // add "createDrug" permission
        $createDrug = $auth->createPermission('createDrug');
        $createDrug->description = 'Create a Drug';
        $auth->add($createDrug);

        // add "updateDrug" permission
        $updateDrug = $auth->createPermission('updateDrug');
        $updateDrug->description = 'Update Drug';
        $auth->add($updateDrug);
        
        // add "createReceipt" permission
        $createReceipt = $auth->createPermission('createReceipt');
        $createReceipt->description = 'Create a Receipt';
        $auth->add($createReceipt);

        // add "updateReceipt" permission
        $updateReceipt = $auth->createPermission('updateReceipt');
        $updateReceipt->description = 'Update Receipt';
        $auth->add($updateReceipt);

        // add "createUser" permission
        $createUser = $auth->createPermission('createUser');
        $createUser->description = 'Create a User';
        $auth->add($createUser);

        // add "updateUser" permission
        $updateUser = $auth->createPermission('updateUser');
        $updateUser->description = 'Update User';
        $auth->add($updateUser);

        // add "operator" role and give this role the "createPerson", "updatePerson",
        // "createDocument", "updateDocument", "createDrug", "updateDrug", 
        // "createReceipt", "updateReceipt" permission
        $operator = $auth->createRole('operator');
        $auth->add($operator);
        $auth->addChild($operator, $createPerson);
        $auth->addChild($operator, $updatePerson);
        $auth->addChild($operator, $createDocument);
        $auth->addChild($operator, $updateDocument);
        $auth->addChild($operator, $createDrug);
        $auth->addChild($operator, $updateDrug);
        $auth->addChild($operator, $createReceipt);
        $auth->addChild($operator, $updateReceipt);

        // add "admin" role and give this role the "createUser", "updateUser",
        // "createDocumentType", "updateDocumentType", "createUnit", "updateUnit" permission
        // as well as the permissions of the "operator" role
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $createUser);
        $auth->addChild($admin, $updateUser);
        $auth->addChild($admin, $createDocumentType);
        $auth->addChild($admin, $updateDocumentType);
        $auth->addChild($admin, $createUnit);
        $auth->addChild($admin, $updateUnit);
        $auth->addChild($admin, $operator);

        // Assign roles to users. 1 and 2 are IDs returned by IdentityInterface::getId()
        // usually implemented in your User model.
        //$auth->assign($operator, 2);
        $auth->assign($admin, 1);
        
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;

        $auth->removeAll();

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240303_132321_init_rbac cannot be reverted.\n";

        return false;
    }
    */
}
