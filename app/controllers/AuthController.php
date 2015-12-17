<?php
/**
 *  [API] アカウントに関するクラス
 *
 *  アカウントに関するAPIをまとめたコントローラー
 *  エンドポイント単位でメゾットを定義
 *  Privateメゾットはプリフィックスにアンダーバー[_]をつける
 *
 *  @access public
 *  @author Goma::NanoHa <goma@goma-gz.net>
 *  @extends ControllerBase
 */

class AuthController extends ControllerBase
{

    public function indexAction()
    {

    }

    /**
     *  [POST]アカウントの登録メゾット
     *
     *  Endpoint POST /auth/signup
     *
     *  @access public
     *  @return JSON Responce
     */
    public function signupAction()
    {

        $post = [
            'name' => true,
            'password' => true,
            'screen_name' => true,
            'other_profile' => false
        ];
        if($this->_getPost($post)) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 201;
            $this->_status['response']['detail'] = $post['empty'];
        }
        if($this->_status['response']['status'] && !$this->_checkName($this->_post['name'], $detail)) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 205;
            $this->_status['response']['detail'] = $detail;
        }
        if(!$this->_status['response']['status']) {
            return $this->response->setJsonContent($this->_status);
        }

        $profile = empty($this->_post['other_profile']) ? serialize([]) : serialize(json_decode($this->_post['other_profile'], true));

        $users = new Users();
        $users->assign(
            [
                'name' => $this->_post['name'],
                'password' => $this->security->hash($this->_post['password']),
                'screen_name' => $this->_post['screen_name'],
                'other_profile' => $profile
            ]
        );

        if(!$users->save()) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 102;
            return $this->response->setJsonContent($this->_status);
        }

        return $this->response->setJsonContent($this->_status);

    }

    public function loginAction()
    {

        $post = [
            'name' => true,
            'password' => true
        ];
        if(!$this->_getPost($post)) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 201;
            $this->_status['response']['detail'] = $post['empty'];
        }

        if(!$this->_status['response']['status']) {
            return $this->response->setJsonContent($this->_status);
        }

        $user = Users::findFirstByName($this->_post['name']);

        if(!$user || !$this->security->checkHash($this->_post['password'], $user->password)) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 302;
            return $this->response->setJsonContent($this->_status);
        }

        $token = $this->security->hash($user->id . '+' . $user->name);

        $this->_status['response']['result'] = [
            'id' => $user->id,
            'name' => $user->name,
            'screen_name' => $user->screen_name,
            'token' => $token
        ];

        return $this->response->setJsonContent($this->_status);

    }

    /**
     *  Name の 重複チェック
     *
     *  @param string $name
     *      名前
     *  @param array $detail = array()
     *      詳細
     *  @return boolean
     */
    private function _checkName($name, &$detail = [])
    {
        if(empty($name)) return false;
        $users_name = (bool)Users::findFirstByName($name);
        if($users_name) $detail[] = $name;
        return empty($detail);
    }

}

