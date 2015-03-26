<?php
/**
 * @author Nikola Kostadinov<nikolakk@gmail.com>
 * Date: 25.03.2015
 * Time: 13:56 ч.
 */

namespace nkostadinov\user\components;


use nkostadinov\user\models\UserSearch;
use yii\web\User as BaseUser;

class User extends BaseUser
{
    public $identityClass = 'nkostadinov\user\models\User';

    public function listUsers($params)
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $dataProvider;
    }
}