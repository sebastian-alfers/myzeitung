<?php

  class AppError extends ErrorHandler {
    function error404($params) {
      debug('implement logic');
      die();
      $this->controller->redirect(array('controller'=>'papers', 'action'=>'view'));

    }
  }

