<?php

namespace Plugins\yantraadmin\Models;

use PDO;
use System\Model;
use Exception;

class UserMetaModel extends Model
{
    public function __construct()
    {
        parent::__construct('ya_users_meta', 'meta_id');
    }

    /**
     * Get all meta records for a user.
     *
     * @param int $user_id
     * @return array
     * @throws Exception
     */
    public function get_all_meta(int $user_id): array
    {
        // Step 1: Retrieve distinct meta keys
        $metaKeysQuery = $this->query()
            ->select('meta_key')
            ->where('user_id', '=', $user_id)
            ->distinct()
            ->getResults(PDO::FETCH_COLUMN);

        // Step 2: Construct the dynamic SELECT part of the query
        $queryBuilder = $this->query()
            ->select('user_id')
            ->where('user_id', '=', $user_id)
            ->groupBy('user_id');

        foreach ($metaKeysQuery as $metaKey) {
            $escapedMetaKey = $this->quote($metaKey); // Safely escape meta_key
            $queryBuilder->selectRaw("MAX(CASE WHEN meta_key = {$escapedMetaKey} THEN meta_value END) AS `{$metaKey}`");
        }

        // Step 3: Execute the dynamically constructed query and fetch results
        return $queryBuilder->getResults(PDO::FETCH_ASSOC);
    }

    /**
     * Get a specific meta record for a user.
     *
     * @param int $user_id
     * @param string $meta_key
     * @return array
     * @throws Exception
     */
    public function get_meta(int $user_id, string $meta_key): array
    {
        return $this->query()
            ->select('meta_value')
            ->where('user_id', '=', $user_id)
            ->where('meta_key', '=', $meta_key)
            ->getResult(PDO::FETCH_ASSOC);
    }

    /**
     * Delete a specific meta record for a user.
     *
     * @param int $user_id
     * @param string $meta_key
     * @return int
     * @throws Exception
     */
    public function delete_meta(int $user_id, string $meta_key): int
    {
        return $this->query('delete')
            ->where('user_id', '=', $user_id)
            ->where('meta_key', '=', $meta_key)
            ->executeQuery()->rowCount();
    }

    /**
     * Save a meta record for a user.
     *
     * @param int $user_id
     * @param string $meta_key
     * @param string $meta_value
     * @return int
     * @throws Exception
     */
    public function save_meta(int $user_id, string $meta_key, string $meta_value): int
    {
        $data = [
            'user_id' => $user_id,
            'meta_key' => $meta_key,
            'meta_value' => $meta_value
        ];

        $updateColumns = [
            'meta_value' => $meta_value
        ];

        return $this->save($data, $updateColumns);
    }
}