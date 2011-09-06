<?php
$title = '';

//frontpage
if($this->params['controller'] == 'home'){
    $title = 'myZeitung';
}else{
    //user controller
    if($this->params['controller'] == 'users'){
        if($this->params['action'] == 'view'){
            if(isset($user['User']['username'])){
                $title = sprintf(__('%1$s Articles',true),$this->MzText->possessive($user['User']['username']));
            }
        }elseif($this->params['action'] == 'viewSubscriptions'){
            if(isset($user['User']['username'])){
                $title = sprintf(__('%1$s Papers',true),$this->MzText->possessive($user['User']['username']));
            }
        }elseif($this->params['action'] == 'index'){
            $title = __('Browse Authors',true);
        }elseif($this->params['action'] == 'login'){
            $title = __('Login',true);
        }elseif( $this->params['action'] == 'login'){
            $title = __('Login',true);
        }elseif( $this->params['action'] == 'forgotPassword'){
            $title = __('I forgot my password',true);
        }elseif( $this->params['action'] == 'accGeneral'){
            $title = __('Account', true).' - '.__('General Settings',true);
        }elseif( $this->params['action'] == 'accPrivacy'){
            $title = __('Account', true).' - '.__('Privacy',true);
        }elseif( $this->params['action'] == 'accSocial'){
            $title = __('Account', true).' - '.__('Social Media',true);
        }elseif( $this->params['action'] == 'accAboutMe'){
            $title = __('Account', true).' - '.__('About Me',true);
        }
    //papers controller
    }elseif($this->params['controller'] == 'papers'){
        if($this->params['action'] == 'view'){
            $title = $paper['Paper']['title'];
        }elseif($this->params['action'] == 'index'){
            $title = __('Browse Papers', true);
        }elseif($this->params['action'] == 'edit'){
            $title = __('edit paper', true);
        }elseif($this->params['action'] == 'add'){
            $title = __('new paper', true);
        }
    //posts controller
    }elseif($this->params['controller'] == 'posts'){
        if($this->params['action'] == 'index'){
            $title = __('Browse Articles', true);
        }elseif($this->params['action'] == 'view'){
            $title = $post['Post']['title'].' - '.$user['User']['username'];
        }elseif($this->params['action'] == 'edit'){
            $title = __('Edit Article',true);
        }elseif($this->params['action'] == 'add'){
            $title = __('New Article',true);
        }
    //search controller
    }elseif($this->params['controller'] == 'search'){
        $title = __('Searchresults', true);
        
    //conversations controller
    }elseif($this->params['controller'] == 'conversations'){
        $title = __('Messages', true);
    }


    if(!empty($title)){
        $title = $title.' | myZeitung';
    }else{
        $title = 'myZeitung';
    }


}
echo $title;
?>