<?php

class CommentController extends ControllerBase
{

    public function setAction()
    {

        $post = [
            'name' => false,
            'email' => false,
            'comment' => true
        ];
        if($this->_status['response']['status'] && !$this->_getPost($post)) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 201;
            $this->_status['response']['detail'] = $post['empty'];
        }

        $urlId = $this->dispatcher->getParam('id');
        if(!$this->_status['response']['status'] && empty($urlId)) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 203;
        }

        $posts = Posts::findFirst($urlId);
        if($this->_status['response']['status'] && !$posts) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 404;
        }

        if(!$this->_status['response']['status']) {
            return $this->response->setJsonContent($this->_status);
        }

        $comments = new Comments();
        $comments->assign(
            [
                'posts_id' => $posts->id,
                'name' => $this->_post['name'],
                'email' => $this->_post['email'],
                'comment' => $this->_post['comment']
            ]
        );

        if(!$comments->save()) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 102;
        }

        return $this->response->setJsonContent($this->_status);

    }

    public function listAction()
    {

        $comments = Comments::find([
            'order' => 'id ASC'
        ]);

        if(!count($comments)) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 102;
            return $this->response->setJsonContent($this->_status);
        }

        foreach($comments as $comment) {

            $this->_status['response']['comment'][] = [
                'post_id' => $comment->posts->id,
                'posts_title' => $comment->posts->title,
                'comment_name' => $comment->name,
                'comment_email' => $comment->email,
                'comment' => $comment->comment,
                'date' => $comment->created_at
            ];

        }

        return $this->response->setJsonContent($this->_status);

    }

    /**
     *  Jsonのマージ及び必要項目のNullチェック
     *
     *  @access private
     *  @param JSON &$json
     *      リスト
     *  @param array $templateList
     *      リストのテンプレート
     *  @param array &$conditions
     *      リストの必要項目
     *  @return boolean
     */
    private function _mergeArray(&$json, $templateList, &$conditions)
    {
        $array = json_decode($json, true);
        $json = array_merge($templateList, $array);
        $status = [];
        foreach($conditions as $key) {
            if(empty($json[$key])) $status[] = $key;
        }
        $conditions = $status;
        return empty($status);
    }

}

