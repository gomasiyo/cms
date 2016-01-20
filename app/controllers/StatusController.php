<?php

class StatusController extends ControllerBase
{

    public function initialize()
    {

        $this->view->disable();

    }

    public function code404Action()
    {
        $this->response->setStatusCode(404, 'Not Dound');

        echo 404;
    }

}
