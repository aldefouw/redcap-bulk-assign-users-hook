<?php
class bulkUserModuleClass {

    private $add_bulk_user_path;
    private $project_id;

    function __construct($project_id) {
        $this->setProjectID($project_id);
        $this->setAddBulkUsersPath();
    }

    function setProjectID($project_id){
        $this->project_id = $project_id;
    }

    function getProjectID(){
        return $this->project_id;
    }

    function setAddBulkUsersPath(){
        $this->add_bulk_user_path = dirname(__FILE__).'/add_bulk_users.php';
    }

    function getAddBulkUsersPath(){
        return $this->add_bulk_user_path;
    }

    function getProjectRoles(){
        $sql = "SELECT role_id, role_name FROM redcap_user_roles WHERE project_ID = ".$this->getProjectID();
        $q = db_query($sql);

        $select_menu = '<select id="bulk_add_roles">';
        while ($row = db_fetch_assoc($q)) {
            $select_menu .= '<option value="'.$row['role_id'].'">'.$row['role_name'].'</option>';
        }
        $select_menu .= '</select>';

        if(db_num_rows($q)) return $select_menu;
    }

    function getHTMLOutput(){
        if( is_file( $this->getAddBulkUsersPath() ) && strlen($this->getProjectRoles()) ) {

            //Output buffer will fetch the output of this PHP file
            ob_start();
            require($this->getAddBulkUsersPath());
            $output = ob_get_contents();
            ob_end_clean();

            return $output;
        }
    }
}
?>
