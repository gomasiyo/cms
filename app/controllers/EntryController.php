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

class EntryController extends ControllerBase
{

    public function addAction()
    {

        if($this->_status['response']['status'] && $this->_checkToken()) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 301;
        }

        $post = [
            'entry' => true
        ];
        if($this->_status['response']['status'] && !$this->_getPost($post)) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 201;
            $this->_status['response']['detail'] = $post['empty'];
        }

        $templateList = [
            'title' => null,
            'tag' => null,
            'category' => null,
            'content' => null,
        ];
        $conditions = [
            'content'
        ];
        if($this->_status['response']['status'] && !$this->_mergeArray($this->_post['entry'], $templateList, $conditions)) {
            $this->_status['response']['status'] = false;

            $this->_status['response']['code'] = 202;
            $this->_status['response']['detail'] = $conditions;
        }

        if(!$this->_status['response']['status']) {
            return $this->response->setJsonContent($this->_status);
        }

        $posts = new Posts();
        $posts->assign(
            [
                'author' => $this->_id,
                'title' => $this->_post['entry']['title'],

                'content' => $this->_post['entry']['content']
            ]

        );

        if(!$posts->save()) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 102;
            return $this->response->setJsonContent($this->_status);
        }

        if(!empty($this->_post['entry']['tag'])) {

            if(is_string($this->_post['entry']['tag'])) $this->_post['entry']['tag'] = (array)$this->_post['entry']['tag'];

            foreach($this->_post['entry']['tag'] as $tag) {

                $tags = new Tags();
                $tags->assign(
                    [
                        'posts_id' => $posts->id,
                        'tag' => $tag
                    ]
                );

                if(!$tags->save()) {
                    $this->_status['response']['status'] = false;
                    $this->_status['response']['code'] = 102;
                    return $this->response->setJsonContent($this->_status);
                }

            }

        }


        if(!empty($this->_post['entry']['category'])) {

            if(is_string($this->_post['entry']['category'])) $this->_post['entry']['category'] = (array)$this->_post['entry']['category'];

            foreach($this->_post['entry']['category'] as $category) {

                $categories = new Categories();
                $categories->assign(
                    [
                        'posts_id' => $posts->id,
                        'category' => $category
                    ]
                );

                if(!$categories->save()) {
                    $this->_status['response']['status'] = false;
                    $this->_status['response']['code'] = 102;
                    return $this->response->setJsonContent($this->_status);
                }

            }

        }

        return $this->response->setJsonContent($this->_status);

    }

    public function updateAction()
    {

        if($this->_status['response']['status'] && $this->_checkToken()) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 301;
        }

        $post = [
            'entry' => true
        ];
        if($this->_status['response']['status'] && !$this->_getPost($post)) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 201;
            $this->_status['response']['detail'] = $post['empty'];
        }

        $templateList = [
            'title' => null,
            'content' => null,
        ];
        $conditions = [
            'content'
        ];
        if($this->_status['response']['status'] && !$this->_mergeArray($this->_post['entry'], $templateList, $conditions)) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 202;
            $this->_status['response']['detail'] = $conditions;
        }

        $urlId = $this->dispatcher->getParam('id');
        if(!$this->_status['response']['status'] && empty($urlId)) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 203;
        }

        if(!$this->_status['response']['status']) {
            return $this->response->setJsonContent($this->_status);
        }

        $updateContent = [];
        foreach($this->_post['entry'] as $post => $content) {
            if(isset($content) && array_key_exists($post, $templateList)) {
                $updateContent[$post] = $content;
            }
        }

        $posts = Posts::findFirst($urlId);

        if(!$post) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 404;
            return $this->response->setJsonContent($this->_status);
        }

        $posts->assign($updateContent);

        if(!$posts->save()) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 102;
            return $this->response->setJsonContent($this->_status);
        }

        return $this->response->setJsonContent($this->_status);

    }

    public function allAction()
    {

        $posts = Posts::find([
            'order' => 'id ASC'
        ]);

        foreach($posts as $post) {

            $content = preg_split('/\[more\]/', $post->content);

            $tags = Tags::findByPosts_id($post->id);
            $tag_array = [];
            if($tags) {
                foreach($tags as $tag) {
                    $tag_array[] = $tag->tag;
                }
            }

            $categories = Categories::findByPosts_id($post->id);
            $category_array = [];
            if($categories) {
                foreach($categories as $category) {
                    $category_array[] = $category->category;
                }
            }

            $this->_status['response']['entry'][] = [
                'id' => $post->id,
                'author' => $post->users->name,
                'title' => $post->title,
                'content' => $content,
                'tags' => $tag_array,
                'categoris' => $category_array
            ];

        }

        return $this->response->setJsonContent($this->_status);

    }

    public function articleAction()
    {

        $article_id = $this->dispatcher->getParam('id');
        $post = Posts::findFirst($article_id);

        if(!$post) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 404;
            return $this->response->setJsonContent($this->_status);
        }

        $content = preg_split('/\[more\]/', $post->content);
        $content = implode('', $content);

        $tags = Tags::findByPosts_id($post->id);
        $tag_array = [];
        if($tags) {
            foreach($tags as $tag) {
                $tag_array[] = $tag->tag;
           }
        }

        $categories = Categories::findByPosts_id($post->id);
        $category_array = [];
        if($categories) {
            foreach($categories as $category) {
                $category_array[] = $category->category;
            }
        }

        $this->_status['response']['entry'] = [
            'id' => $post->id,
            'author' => $post->users->name,
            'title' => $post->title,
            'content' => $content,
            'tags' => $tag_array,
            'categoris' => $category_array
        ];

        return $this->response->setJsonContent($this->_status);

    }

    public function tagArticleAction()
    {

        $tag_name = ltrim($this->dispatcher->getParam('tag'), '/');
        $tags = Tags::findByTag($tag_name);

        if(!count($tags)) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 404;
            return $this->response->setJsonContent($this->_status);
        }

        $this->_status['response']['entry']['tag_title'] = $tag_name;

        foreach($tags as $tag) {

            $content = preg_split('/\[more\]/', $tag->posts->content);

            $tags = Tags::findByPosts_id($tag->id);
            $tag_array = [];
            if($tags) {
                foreach($tags as $tag) {
                    $tag_array[] = $tag->tag;
                }
            }

            $categories = Categories::findByPosts_id($tag->id);
            $category_array = [];
            if($categories) {
                foreach($categories as $category) {
                    $category_array[] = $category->category;
                }
            }

            $this->_status['response']['entry'][] = [
                'id' => $tag->posts_id,
                'author' => $tag->posts->users->name,
                'title' => $tag->posts->title,
                'content' => $content,
                'tags' => $tag_array,
                'categoris' => $category_array
            ];

        }

        return $this->response->setJsonContent($this->_status);

    }


    public function categoryArticleAction()
    {

        $category_name = ltrim($this->dispatcher->getParam('category'), '/');
        $categories = Categories::findByCategory($category_name);

        if(!count($categories)) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 404;
            return $this->response->setJsonContent($this->_status);
        }

        foreach($categories as $category) {

            $content = preg_split('/\[more\]/', $category->posts->content);

            $tags = Tags::findByPosts_id($category->id);
            $tag_array = [];
            if($tags) {
                foreach($tags as $tag) {
                    $tag_array[] = $tag->tag;
                }
            }

            $categories = Categories::findByPosts_id($category->id);
            $category_array = [];
            if($categories) {
                foreach($categories as $category) {
                    $category_array[] = $category->category;
                }
            }

            $this->_status['response']['entry'][] = [
                'id' => $category->posts->id,
                'author' => $category->posts->users->name,
                'title' => $category->posts->title,
                'content' => $content,
                'tags' => $tag_array,
                'categoris' => $category_array
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

