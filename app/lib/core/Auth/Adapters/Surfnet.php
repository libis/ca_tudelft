<?php
/**
 * Created by PhpStorm.
 * User: NaeemM
 * Date: 18/01/2018
 */

require_once(__CA_LIB_DIR__.'/core/Auth/BaseAuthAdapter.php');
require_once(__CA_LIB_DIR__.'/core/Auth/Adapters/CaUsers.php');


//require_once('vendor/simplesamlphp/lib/_autoload.php');
include_once "/libis/CA/production/tools/simplesamlphp/lib/_autoload.php";


class SurfnetAuthAdapter extends BaseAuthAdapter implements IAuthAdapter {

    /**
     * Authenticates user
     *
     * @param string $ps_username user name
     * @param string $ps_password cleartext password
     * @param null $pa_options Associative array of options
     * @return boolean
     */
    public function authenticate($ps_username, $ps_password = "", $pa_options = null)
    {
        $notification_manager = $pa_options['va_notification'];
        // 0. Login type : collective access or samal login
        if($pa_options['logintype'] === "collectiveaccess"){

            if(!$ps_username) { // for login type collectiveaccess username should be provided
                return false;
            }
            return $this->ca_login($ps_username, $ps_password);
        }
        else{
            //1. Surfnet authentication
            //$as = new SimpleSAML_Auth_Simple('SURFconext');
            $as = new SimpleSAML_Auth_Simple('default-sp');
            $as->requireAuth(); 
            $attributes = $as->getAttributes();
            $bool = $as->isAuthenticated();

            if(!empty($attributes['urn:mace:dir:attribute-def:mail'])){
                $email = current($attributes['urn:mace:dir:attribute-def:mail']);
                $sur_name = current($attributes['urn:mace:dir:attribute-def:sn']);
                $given_name = current($attributes['urn:mace:dir:attribute-def:givenName']);
            }
            //todo: login.php hide login form and change login button text to proceed

            if($bool && !empty($email)){
                // Valid surfnet user
                $ps_username = $email;
                $ps_password = current(explode("@", $email))."_";
                $vs_surf_email = $email;

                //2. Login to CA
                //if(!$this->ca_login($ps_username, $ps_password)){
                if(!$this->ca_login($ps_username, $ps_password)){
                    // authentication failed: user does not exist or password changed
                    // check if user record exists
                    $ca_existing_user = new ca_users($ps_username);
                    $user_id = $ca_existing_user->getUserID();
                    //$ca_existing_user =false;
                    if(!empty($user_id)){
                        //3. User exists but cannot login, unmatched passwords. Update to new password as surfnet has validated the user with new password
                        $ca_existing_user->setMode(ACCESS_WRITE);
                        $ca_existing_user->set('password', $ps_password);
                        if($ca_existing_user->update()){
                            return $this->ca_login($ps_username, $ps_password);
                        }
                        return false;   // error in updating password, display login error message
                    }
                    else{
                        //4. User does not exist, create new user
                        $ca_new_user = new ca_users();
                        $ca_new_user->setMode(ACCESS_WRITE);
                        $ca_new_user->set('user_name', $ps_username);
                        $ca_new_user->set('password', $ps_password);
                        $ca_new_user->set('email', $vs_surf_email);
                        $ca_new_user->set('fname', $sur_name);
                        $ca_new_user->set('lname', $given_name);
                        $ca_new_user->set('userclass', 0);  // 0 full access,  1 public access
                        $ca_new_user->set('active', false);
                        $user_created = $ca_new_user->insert(); // insert new user record
                        //$user_created = true;
                        if($user_created){
                            $ca_new_user->addRoles('cataloguer'); // add role (default role cataloger)
                            //5. Send email to collective access administrator to enable user
                            //   At the time of activation an email to user can be sent by enabling 'email_user_when_account_activated' in app.conf
                            $admin_mail_message = "A new Collective Access user account with email '".$vs_surf_email."' and login name '".$ps_username."' has been created, which needs to be activated by Administrator.";
                            if($this->sendEmail(__CA_ADMIN_EMAIL__, $admin_mail_message))
                            {
                                $vs_message = _t("Error in sending email to admin.");
                                throw new SAMLException($vs_message);
                            }
                            //6. Display message
                            $vs_message = _t("Your account needs to be activated, you will be notified via email once activated.<br>");
                            $notification_manager->addNotification(_t($vs_message), __NOTIFICATION_TYPE_INFO__);
                            return false; // default should be false to not allow newly created user to access CA
                        }
                    }
                }
                else // user valid, login to CA
		        {
                    return true;
		        }
            }
            else // Invalid user, surfnet validation failed
                return false;
        }
    }

    public function getUserInfo($ps_username, $ps_password)
    {
        parent::getUserInfo($ps_username, $ps_password); // TODO: Change the autogenerated stub
    }
    # --------------------------------------------------------------------------------

    public function createUserAndGetPassword($ps_username, $ps_password) {
        // ca_users takes care of creating the backend record for us. There's nothing else to do here
        return create_hash($ps_password);
    }
    # --------------------------------------------------------------------------------

    public function supports($pn_feature) {
        switch($pn_feature){
            case __CA_AUTH_ADAPTER_FEATURE_RESET_PASSWORDS__:
            case __CA_AUTH_ADAPTER_FEATURE_UPDATE_PASSWORDS__:
                return true;
            case __CA_AUTH_ADAPTER_FEATURE_AUTOCREATE_USERS__:
            default:
                return false;
        }
    }
    # --------------------------------------------------------------------------------

    public function updatePassword($ps_username, $ps_password) {
        // ca_users takes care of creating the backend record for us. There's nothing else to do here
        return create_hash($ps_password);
    }
    # --------------------------------------------------------------------------------

    public function deleteUser($ps_username) {
        // ca_users takes care of deleting the db row for us. Nothing else to do here.
        return true;
    }
    # --------------------------------------------------------------------------------

    public function getAccountManagementLink()
    {
        return parent::getAccountManagementLink();
    }

    public function ca_login($username, $password){
        $ca_user_adapter = new CaUsersAuthAdapter();
        AuthenticationManager::init('CaUsers');
        //return $ca_user_adapter->authenticate($username,$password);

        $ca_user = new ca_users();
        return $ca_user->authenticate($username,$password, array('logintype' => "collectiveaccess"));

    }

    public function sendEmail($email, $message){
        if(isset($email))
           return caSendmail($email,__CA_ADMIN_EMAIL__, "New Collective Access User", $message);

        return false;
    }

}

class SAMLException extends Exception {}

/**
 * changes:
 * themes/default/views/system/login_html.php
 * app/conf/local/app.conf
 * app/controllers/system/AuthController.php
 * app/lib/core/Controller/Request/RequestHTTP.php
*/
