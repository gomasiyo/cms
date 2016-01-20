<?php

class StatusController extends ControllerBase
{

    public function code404Action()
    {
        $this->response->setStatusCode(404, 'Not Found');
        $this->_status['response']['status'] = false;
        $this->_status['response']['code'] = 404;
        return $this->response->setJsonContent($this->_status);
    }

}
