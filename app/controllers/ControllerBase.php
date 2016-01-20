<?php

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{

    /**
     *  ステータス用の保管庫
     */
    protected $_status;
    protected $_post;
    protected $_id;
    protected $_name;
    protected $_token;

    /**
     *  ログイン用POST保存
     */
    public function initialize()
    {
        /**
         *  Header 追加
         */
        $this->response->setHeader('Content-Type', 'application/json; charset=utf-8');
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setRawHeader('HTTP/1.1 200 OK');

        /**
         *  ログイン用POST取得
         */
        $this->_id = $this->request->getPost('id', null, null);
        $this->_name = $this->request->getPost('name', null, null);
        $this->_token = $this->request->getPost('token', null, null);

        /**
         *  Status内容
         */
        $this->_status = [
            'response' => [
                'status' => true
            ]
        ];

        /**
         *  View 非ロード
         */
        $this->view->disable();

    }

    /**
     *  Tokenチェック
     *
     *  @access protected
     *  @return boolean
     */
    protected function _checkToken()
    {
        if(!(empty($this->_id) || empty($this->_name) || empty($this->_token))) {
            return !$this->security->checkHash($this->_id . '+' . $this->_name, $this->_token);
        } else {
            return true;
        }
    }

    /**
     *  Post取得及びチェック
     *
     *  @access protected
     *  @param array &$post
     *      POSTデータ
     *  @return boolean
     */
    protected function _getPost(&$post)
    {
        $result = [
            'status' => true,
        ];
        foreach($post as $key => $val) {
            $this->_post[$key] = $this->request->getPost($key, null, null);
            if($val) {
                if(empty($this->_post[$key])) {
                    $result['status'] = false;
                    $result['empty'][] = $key;
                }
            }
        }
        $post = $result;
        return $result['status'];
    }

}
