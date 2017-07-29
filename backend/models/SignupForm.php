<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2017/7/29
 * Time: 16:56
 */

namespace backend\models;


use yii\base\Model;

class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $created_at;
    public $updated_at;

    public function rules()
    {
        return [
            ['username','filter','filter'=>'trim'],
            ['username','required','message'=>'用户名不可以为空'],
            ['username','unique','targetClass' => '\backend\models\UserBackend', 'message' => '用户名已存在.'],
            ['username','string','min'=>2,'max'=>255],
            ['email','filter','filter'=>'trim'],
            ['email','required','message'=>'邮箱不能为空'],
            ['email','unique','targetClass' => '\backend\models\UserBackend', 'message' => '邮箱已存在.'],
            ['email','string','max'=>255],
            ['email','email'],
            ['password','required','message'=>'密码不能为空'],
            ['password','string','min'=>6,'max'=>'20','message'=>'密码长度为6-20位'],
            [['created_at','updated_at'],'default','value'=>date('Y-m-d H:i:s')],
        ];
    }

    public function signup()
    {
        if(!$this->validate()){
            return '验证失败';
        }

        $user = new UserBackend();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->created_at = $this->created_at;
        $user->updated_at = $this->updated_at;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        return $user->save(false);
    }
}