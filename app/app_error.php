<?php

  class AppError extends ErrorHandler {


    function missingController($params) {
        $this->error404($params);
    }
    function missingAction($params) {
        $this->error404($params);
    }
    
  }

