<?php
namespace Plugins\yantraadmin\Models;

use Exception;
use System\Model;

class UserSessionModel extends Model
{
    public function __construct()
    {
        parent::__construct('ya_user_sessions', 'session_id');
    }

    /**
     * Create a new session.
     *
     * @param int $userId
     * @param string $deviceType
     * @param int $tabId
     * @return void
     * @throws Exception
     */
    public function createSession(int $userId,string $sessionId, int $tabCount, string $deviceType, string $info): void
    {
        // Check if the user is already logged in from more than 2 devices
       /* if ($this->countActiveDevices($userId,$sessionId) >= 2) {
            throw new Exception("User is already logged in from the maximum number of devices.");
        }*/

        // Check if the user is already logged in from the same device
        //if ($this->isDeviceActive($userId, $deviceType)) {
         //   throw new Exception("User is already logged in from this device.");
       // }

        // Ensure only one tab per device
        //if ($this->isTabActive($userId, $deviceType, $tabId)) {
        //    throw new Exception("User is already logged in from another tab on this device.");
        //}

        // Insert new session into the database
        $sessionData = [
            'session_id' => $sessionId,
            'user_id' => $userId,
            'device_type' => $deviceType,
            'info'=>$info,
            'tab_count' => $tabCount
        ];
        // Insert new session into the database
        $onupdate = [
            'device_type' => $deviceType,
            'info'=>$info,
            'tab_count' => $tabCount
        ];

        try {
            $this->onDuplicateKeyUpdate($onupdate)->insert($sessionData);
        } catch (Exception $e) {
            throw new Exception("Failed to create session: " . $e->getMessage());
        }
    }

    /**
     * Count the number of active devices for a user.
     *
     * @param int $userId
     * @return int
     * @throws Exception
     */
    public function countActiveDevices(int $userId, string $sessionId): int
    {
        try {
            return $this->query()
                ->where('user_id', '=', $userId)
                ->where('deleted','=',0)
                ->where('closed','=',0)
                ->where('session_id','!=',$sessionId)
                ->count();
        } catch (Exception $e) {
            throw new Exception("Failed to count active devices: " . $e->getMessage());
        }
    }

    /**
     * Check if the user has an active session on the given device.
     *
     * @param int $userId
     * @param string $deviceType
     * @return bool
     * @throws Exception
     */
    public function isDeviceActive(int $userId, string $deviceType): bool
    {
        try {
            return $this->query()
                    ->where('user_id', '=', $userId)
                    ->where('device_type', '=', $deviceType)
                    ->count() > 0;
        } catch (Exception $e) {
            throw new Exception("Failed to check device activity: " . $e->getMessage());
        }
    }
    /**
     * Delete a session by its ID.
     *
     * @param string $sessionId
     * @return void
     * @throws Exception
     */
    public function deleteSession(string $sessionId): void
    {
        try {
            $this->query()
                ->where('session_id', '=', $sessionId)
                ->delete();
        } catch (Exception $e) {
            throw new Exception("Failed to delete session: " . $e->getMessage());
        }
    }

    /**
     * Delete all sessions for a user.
     *
     * @param int $userId
     * @return void
     * @throws Exception
     */
    public function deleteAllSessions(int $userId): void
    {
        try {
            $this->query()
                ->where('user_id', '=', $userId)
                ->delete();
        } catch (Exception $e) {
            throw new Exception("Failed to delete all sessions: " . $e->getMessage());
        }
    }

    /**
     * Get all sessions for a user.
     *
     * @param int $userId
     * @return array
     * @throws Exception
     */
    public function getSessionsForUser(int $userId): array|bool
    {
        try {
            return $this->query()
                ->where('user_id', '=', $userId)
                ->where('closed', '=', 0)
                ->where('deleted', '=', 0)
                ->getResults();
        } catch (Exception $e) {
            throw new Exception("Failed to get sessions for user: " . $e->getMessage());
        }
    }

    /**
     * Get a session by its ID.
     *
     * @param string $sessionId
     * @return array|null
     * @throws Exception
     */
    public function getSessionById(string $sessionId): ?array
    {
        try {
            return $this->query()
                ->where('session_id', '=', $sessionId)
                ->getResult();
        } catch (Exception $e) {
            throw new Exception("Failed to get session by ID: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function close(string $sessionId): int
    {
        return $this->reset('update')
            ->set('closed',1)
            ->where('session_id','=',$sessionId)
            ->update();

    }

    /**
     * @throws Exception
     */
    public function closeAll($user_id): int
    {
        return $this->reset('update')
            ->set('closed',1)
            ->where('user_id','=',$user_id)
            ->update();
    }
}