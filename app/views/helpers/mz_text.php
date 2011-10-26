<?php
App::import('Helper', 'Text');

class MzTextHelper extends TextHelper {

    public $helpers = array('Session');

    function getSubscribeUrl(){
        $subscribe_link = '/login';
        if($this->Session->read('Auth.User.id')){
            $subscribe_link = '#';
        }
        return $subscribe_link;
    }



    /**
     * @param  $user - array
     * @return string
     */
    function getUsername($user){

        return "<h3>" . $this->generateDisplayname($user, true). "</h3>";
    }


    function generateDisplayname($user, $both = false){

        if(isset($user['name']) && !empty($user['name'])){
            if($both){
                return $user['name'].' ('.$user['username'].')';
            }else{
                return $user['name'];
            }
        }else{
            return $user['username'];
        }


    }

    /**
     * @param  $string
     * @return string
     *
     * add possessive "s", "'s" or "'" to a string depending on language and string.
     */

    function possessive($string){
        $lang = $this->Session->read('Config.language');
        if(empty($lang)){
            $lang = Configure::read('Config.language');
        }
        if($lang == 'deu'){
            //german grammar  Hans -> "Hans' Artikel" Tim -> "Tims Artikel"
            if(in_array(substr($string, -1),array('s','S','ß','z','Z','x','X'))){
                $addition = "'";
            }else{
                $addition = "s";
            }
            return $string.$addition;
        //english as backup
        }else{
            //english grammar   Steve Jobs -> "Steve Jobs' articles"  Steve -> "Steve's articles"
            return  $string.(substr($string, -1) == 's' ? "'" : "'s");
        }
    }



}
?>