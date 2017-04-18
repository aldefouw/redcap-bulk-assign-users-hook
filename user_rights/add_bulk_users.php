<div id="addBulkUserRole" style="margin:20px 0;font-size:12px;font-weight:normal;padding:10px;border:1px solid #ccc;background-color:#eee;max-width:630px;">
    <p><strong>Bulk Add Users to Role</strong>: Separate users with a line break.</p>
    <textarea style="width:95%;height:100px;" id="addUsersToRole"></textarea>
    <p><strong>Role:</strong>&nbsp;<?php echo $this->getProjectRoles(); ?></p>

    <input type="checkbox" id="notify_all_with_email" value="1"> Notify ALL Users With Email?

    <p><strong>Double check your list before submitting.</strong></p>
    <p><button class="jqbuttonmed ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" id="bulkUserAddBtn" role="button"><span class="ui-button-text">Assign Users to Role</span></button></p>
</div>

<script type='text/javascript'>

    var messages = '';
    var loaded = [];

    // Assign user to role (via ajax)
    function bulkAssignUserRole(username,role_id,index) {
        showProgress(1);
        checkIfuserRights(username, role_id, function(data){
            if(data == 1){
                // Ajax request
                $.post(app_path_webroot+'UserRights/assign_user.php?pid='+pid, { username: username, role_id: role_id, notify_email_role: sendEmail() }, function(data){
                    if (data == '') { alert(woops); return; }
                    $('#user_rights_roles_table_parent').html(data);
                    messages += $('#user_rights_roles_table_parent div.userSaveMsg')[0].innerHTML + '<br />';
                }).done(function() {
                    loaded[index] = true;
                }).fail(function() {
                    loaded[index] = true;
                    messages = "An error occurred for " + username + '.';
                });

            } else {

                loaded[index] = true;
                messages = "Please note that the role you are trying to assign yourself to does not have User Rights permissions, and thus it would prevent you from accessing the User Rights page. Try selecting another role, or modify the one you are trying to assign.";
            }
        });
    }

    function checkIfLoaded(load){
        return load == true;
    }

    function stopLoading() {
        if(loaded.every(checkIfLoaded)){
            showProgress(0,0);
            simpleDialogAlt(messages, 3);
            enablePageJS();
            messages = '';
            $('textarea#addUsersToRole').val("");
        } else {
            setTimeout(function(){ stopLoading(); },500);
        }
    }

    function sendEmail(){
        if ( $('#notify_all_with_email').prop("checked") ){
            return 1;
        } else {
            return 0;
        }
    }

    $(document).ready(function(){
        $("button#bulkUserAddBtn").click(function(e) {
            e.preventDefault();

            var users_to_add = $('textarea#addUsersToRole').val().split("\n");

            //Trim whitespace and blank lines
            users_to_add = $.map(users_to_add, $.trim);

            for (var i in users_to_add) {
                loaded[i] = false;
                bulkAssignUserRole(users_to_add[i], $("select#bulk_add_roles").val(), i);
            }

            stopLoading();
        });
    });
</script>