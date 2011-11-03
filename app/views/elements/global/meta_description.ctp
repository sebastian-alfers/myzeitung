<?php
$desc = '';

//frontpage
if($this->params['controller'] == 'home'){
    $desc = 'Mit myZeitung werden Nachrichten schnell, direkt und unzensiert im Internet transparent gemacht. Ein innovatives Nachrichtenportal, dass grundsätzlich für alle Autoren und Leser kostenlos ist.';
}else{
    //user controller
    if($this->params['controller'] == 'users'){
        if($this->params['action'] == 'view'){
            if(isset($user['User']['description']) && !empty($user['User']['description'])){
               $desc = substr(strip_tags($user['User']['description']),0,200);
            }else{
               $desc = sprintf(__('Profile and posts of %s on myZeitung.de', true),$this->MzText->generateDisplayname($user['User'],true));
            }
        }elseif($this->params['action'] == 'viewSubscriptions'){
            if($this->params['own_paper'] == Paper::FILTER_OWN){
                $desc = sprintf(__('Browse papers that were created by %s.', true),$this->MzText->generateDisplayname($user['User'],true));
            }elseif($this->params['own_paper'] == Paper::FILTER_SUBSCRIBED){
                $desc = sprintf(__('Browse all paper subscriptions of %s.', true),$this->MzText->generateDisplayname($user['User'],true));
            }
        }elseif($this->params['action'] == 'index'){
            $desc = __('Browse the authors that publish interesting content on myZeitung.de',true);
        }elseif($this->params['action'] == 'login'){

        }elseif( $this->params['action'] == 'login'){
            $desc = __('Login to myZeitung.de',true);
        }elseif( $this->params['action'] == 'forgotPassword'){
            $desc = __('You forgot your password to access myZeitung? Request a new one!',true);
/*        }elseif( $this->params['action'] == 'accGeneral'){

        }elseif( $this->params['action'] == 'accPrivacy'){

        }elseif( $this->params['action'] == 'accSocial'){

         }elseif( $this->params['action'] == 'accInvitations'){

        }elseif( $this->params['action'] == 'accAboutMe'){
*/
        }elseif($this->params['action'] == 'add'){
            __('Register to start publishing articles or to create your own papers.',true);
        }
    //papers controller
    }elseif($this->params['controller'] == 'papers'){
        if($this->params['action'] == 'view'){
            if(isset($paper['Paper']['description']) && !empty($paper['Paper']['description'])){
                 $desc = substr(strip_tags($paper['Paper']['description']),0,200);
            }else{
                $desc = __('Browse this and many other interesting papers on myZeitung.de - or create your own paper!',true);
            }
        }elseif($this->params['action'] == 'index'){
            $desc = __('Browse all of the interesting papers on myZeitung.de', true);
/*        }elseif($this->params['action'] == 'edit'){

        }elseif($this->params['action'] == 'add'){
*/
        }
    //posts controller
    }elseif($this->params['controller'] == 'posts'){
        if($this->params['action'] == 'index'){
            $desc = __('Browse through the interesting articles on myZeitung.de', true);
        }elseif($this->params['action'] == 'view'){
            $desc = substr(strip_tags($post['Post']['content']),0,200);
/*        }elseif($this->params['action'] == 'edit'){

        }elseif($this->params['action'] == 'add'){
*/
        }
    //search controller
//    }elseif($this->params['controller'] == 'search'){

        
    //conversations controller
//    }elseif($this->params['controller'] == 'conversations'){

    }
/*    elseif($this->params['controller'] == 'pages'){

        switch($this->params['pass'][0]){
            case "impressum":
                $desc = __('Legal Notice', true);
            case "agb":
                $desc = __('Terms and Conditions', true);
            case "dsr":
                $desc = __('Privacy Policy', true);
            case "kontakt":
                $desc = __('Contact', true);
            case "kontakt":
                $desc = __('Contact', true);

        }

    } */



}
if(!empty($desc)){
    echo $this->Html->meta('description',$desc);
}
?>