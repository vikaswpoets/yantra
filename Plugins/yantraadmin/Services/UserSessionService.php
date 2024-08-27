<?php

namespace Plugins\yantraadmin\Services;

use Exception;
use Plugins\yantraadmin\Models\UserModel;
use Plugins\yantraadmin\Models\UserSessionModel;
use System\Cookie;
use System\Session;
use System\Utilities\EncryptionUtil;

class UserSessionService
{
    private UserModel $userModel;
    private UserSessionModel $sessionModel;
    private Cookie $coolie;
    private Session $session;

    public array $clientInfo;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->sessionModel = new UserSessionModel();
        $this->coolie = new Cookie();
        $this->session = new Session();
        $this->clientInfo = $this->getClientInfo();
    }

    /**
     * Authenticate user and create a session.
     *
     * @param string $email
     * @param string $password
     * @param bool $rememberMe
     * @return int
     * @throws Exception
     */
    public function login(string $email, string $password, bool $rememberMe):int
    {
        $user = $this->userModel->query()
            ->where('user_email', '=', $email)
            ->getResult();

        if($user){
            $password_check = password_verify($password, $user['user_pass']);
            $liveSessions = $this->sessionModel->getSessionsForUser($user['ID']);
            $valid = apply_filter('validate_session',$password_check,$user, $liveSessions,false);
            if($valid===false)
            {
                $this->coolie->delete('session_id');
                $this->session->destroy();
                do_action('login_fail',$email,$password,1,false);
                return 1;
            }
            else
            {

                $sessionId = bin2hex(random_bytes(32));
                if($this->coolie->has('session_id'))
                    $sessionId = $this->coolie->get('session_id');
                $this->coolie->set('session_id',$sessionId,(time() + 3600 * 24 * 30));
                $this->session->set('user_id', $email);

                $this->sessionModel->createSession($user['ID'],$sessionId,'1','desktop',json_encode($this->clientInfo));
                if ($rememberMe)
                {
                    $encryptedUsername = EncryptionUtil::encrypt($email);
                    $this->coolie->set('user_id',$encryptedUsername,time() + 864000);
                }
                else {
                    $this->coolie->delete('user_id');
                }
                do_action('login_success',$user,$sessionId,false);
                return 0;
            }
        }
        do_action('login_fail',$email,$password,2,false);
        return 2;
    }
    /**
     * @throws Exception
     */
    private function autoLogin(): void
    {
        $encryptedUsername = $this->coolie->get('user_id');
        if(empty($encryptedUsername))
            return;

        $email = EncryptionUtil::decrypt($encryptedUsername);

        $user = $this->userModel->query()
            ->where('user_email', '=', $email)
            ->getResult();

        if($user){
            $liveSessions = $this->sessionModel->getSessionsForUser($user['ID']);
            $valid = apply_filter('validate_session',true,$user, $liveSessions,true);

            if($valid===false)
            {
                $this->coolie->delete('session_id');
                $this->session->destroy();
                do_action('login_fail',$email,$encryptedUsername,1,true);
            }
            else
            {
                $sessionId = bin2hex(random_bytes(32));
                if($this->coolie->has('session_id'))
                    $sessionId = $this->coolie->get('session_id');
                $this->coolie->set('session_id',$sessionId,(time() + 3600 * 24 * 30));
                $this->session->set('user_id', $email);
                do_action('login_success',$user,$sessionId,true);
            }
            return;
        }
        do_action('login_fail',$email,$encryptedUsername,2,true);
    }
    /**
     * @throws Exception
     */
    public function loginCheck():bool{

        $this->sessionModel->reset()
            ->where('last_online < DATE_SUB(NOW(), INTERVAL 32 DAY)')
            ->delete();

        $this->sessionModel->reset('update')
            ->set('closed',1)
            ->where('last_online < DATE_SUB(NOW(), INTERVAL 24 HOUR)')
            ->where('closed','=',0)
            ->update();

        $this->autoLogin();
        if($this->session->isLoggedIn()){
            $email = $this->session->get('user_id');
            $session_id = $this->coolie->get('session_id');
            $rcount = $this->sessionModel->query()
                ->join('ya_users','ya_users.ID','=','ya_user_sessions.user_id')
                ->where('ya_users.user_email', '=', $email)
                ->where('ya_user_sessions.session_id','=',$session_id)
                ->where('ya_user_sessions.closed','=',0)
                ->where('ya_user_sessions.deleted','=',0)
                ->count();
            return $rcount>0;
        }
        return  false;
    }
    /**
     * End the user session.
     *
     * @return void
     * @throws Exception
     */
    public function logout(): void
    {
        if($this->coolie->has('session_id')){
            $sessionId = $this->coolie->get('session_id');
            $this->sessionModel->close($sessionId);
            $this->coolie->delete('session_id');
        }
        $this->session->destroy();
    }
    /**
     * @throws Exception
     */
    public function logoutAll($user_id): void
    {
        if($this->coolie->has('session_id')){
            $this->sessionModel->closeAll($user_id);
        }
    }
    /**
     * Get user details from session ID.
     *
     * @param string $sessionId
     * @return array|false
     * @throws Exception
     */
    public function getUserFromSession(string $sessionId): array|false
    {
        $session = $this->sessionModel->getSessionById($sessionId);

        if ($session) {
            return $this->userModel->get($session['user_id']);
        }

        return false;
    }
    /**
     * Check if a user is logged in based on session ID.
     *
     * @param string $sessionId
     * @return bool
     * @throws Exception
     */
    public function isUserLoggedIn(string $sessionId): bool
    {
        return $this->getUserFromSession($sessionId) !== false;
    }
    /**
     * @throws Exception
     */
    public function getSessionsForUser(int $userId): array|bool
    {
        return $this->sessionModel->getSessionsForUser($userId);
    }
    public function detectDeviceType($userAgent): string
    {
        $mobilePattern = '/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i';
        $tabletPattern = '/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i';

        if (preg_match($tabletPattern, $userAgent)) {
            return 'tablet';
        } elseif (preg_match($mobilePattern, $userAgent)) {
            return 'mobile';
        } else {
            return 'desktop';
        }
    }
    private function getClientInfo(): array
    {
        $clientDetails = [];
        $clientDetails['ip_address'] = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
        $clientDetails['browser'] = $this->getBrowser($_SERVER['HTTP_USER_AGENT']);
        $clientDetails['operating_system'] = $this->getOperatingSystem($_SERVER['HTTP_USER_AGENT']);
        $clientDetails['server_protocol'] = $_SERVER['SERVER_PROTOCOL'] ?? 'Unknown';
        return $clientDetails;
    }
    private function getBrowser($userAgent): string
    {
        $browsers = [
            'Edge' => 'Edge',
            'Opera' => 'Opera',
            'OPR' => 'Opera',
            'Firefox' => 'Firefox',
            'Chrome' => 'Chrome',
            'Safari' => 'Safari',
            'MSIE' => 'Internet Explorer',
            'Trident' => 'Internet Explorer'
        ];

        foreach ($browsers as $key => $browser) {
            if (str_contains($userAgent, $key)) {
                return $browser;
            }
        }

        return 'Unknown';
    }
    private function getOperatingSystem($userAgent): string
    {
        $osArray = [
            'Windows' => 'Windows',
            'Macintosh' => 'Mac OS',
            'Linux' => 'Linux',
            'Android' => 'Android',
            'iPhone' => 'iOS',
            'iPad' => 'iOS',
        ];

        foreach ($osArray as $key => $os) {
            if (str_contains($userAgent, $key)) {
                return $os;
            }
        }

        return 'Unknown';
    }

    /**
     * @throws Exception
     */
    public function getUserDetails(): object
    {
        $userid = $this->session->get('user_id');

        $us = new UserService();
        $user = $us->findByEmail($userid);
        if(is_array($user)){
            return $us->getUserDetails($user['ID']);
        }
        return new \stdClass();
    }
}