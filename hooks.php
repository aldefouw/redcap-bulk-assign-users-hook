<?php
       
    /* 

    ANY OTHER FILES YOU NEED TO REQUIRE UP HERE

    */

    require dirname(__FILE__) . '/hooks/user_rights/bulkUserModuleClass.php';

    function redcap_user_rights($project_id){

        //ONLY AVAILABLE FOR SUPER ADMINS!
        if (SUPER_USER) {
            $add_bulk_users = new bulkUserModuleClass($project_id);
            echo $add_bulk_users->getHTMLOutput();
        }

    }

?>