<?php
class ReposterHelper extends AppHelper {


    function UserHasAlreadyRepostedPost($reposters = null, $user_id = null){

        if(isset($reposters) && !is_null($reposters) && !empty($reposters)){
            if(!is_array($reposters)){
                $reposters = unserialize($reposters);
            }
            if(in_array($user_id,$reposters)){
                return true;
            }
        }
    return false;

    }

}


?>
