<?php

  class AppError extends ErrorHandler {
      function error404($params) {
        $this->forwardTo404($params);
          $this->_outputMessage('error404');
          
      }

      function missingController($params) {
        $this->forwardTo404($params);
      }
    function missingAction($params) {
        $this->forwardTo404($params);
    }


      function forwardTo404($params){
          header("http/1.0 404 not found");
          $this->_outputMessage('error404');
          /*$Dispatcher = new Dispatcher();
          $Dispatcher->dispatch('/articles', array('broken-url' => '/'.$params['url']));
          exit;*/
    }






  }

