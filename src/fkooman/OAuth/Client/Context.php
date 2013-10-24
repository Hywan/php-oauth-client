<?php

/**
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Lesser General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Lesser General Public License for more details.
 *
 *  You should have received a copy of the GNU Lesser General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace fkooman\OAuth\Client;

use fkooman\OAuth\Common\Scope;

class Context
{
    /** @var string */
    private $clientConfigId;

    /** @var string */
    private $userId;

    /** @var fkooman\OAuth\Common\Scope */
    private $scope;

    public function __construct($clientConfigId, $userId, array $scope = array())
    {
        $this->setClientConfigId($clientConfigId);
        $this->setUserId($userId);
        $this->setScope($scope);
    }

    public static function fromArray(array $data)
    {
        foreach (array('client_config_id', 'user_id', 'scope') as $key) {
            if (!array_key_exists($key, $data)) {
                throw new TokenException(sprintf("missing field '%s'", $key));
            }
        }

        return new self(
            $data['client_config_id'],
            $data['user_id'],
            $data['scope']
        );
    }

    public function setClientConfigId($clientConfigId)
    {
        if (!is_string($clientConfigId) || 0 >= strlen($clientConfigId)) {
            throw new ContextException("clientConfigId needs to be a non-empty string");
        }
        $this->clientConfigId = $clientConfigId;
    }

    public function getClientConfigId()
    {
        return $this->clientConfigId;
    }

    public function setUserId($userId)
    {
        if (!is_string($userId) || 0 >= strlen($userId)) {
            throw new ContextException("userId needs to be a non-empty string");
        }
        $this->userId = $userId;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setScope(array $scope)
    {
        $this->scope = new Scope($scope);
    }

    public function getScope()
    {
        return $this->scope;
    }

    public function toArray()
    {
        return array(
            "client_config_id" => $this->getClientConfigId(),
            "user_id" => $this->getUserId(),
            "scope" => $this->getScope()->toArray()
        );
    }

    public function equals(Context $that)
    {
        if ($this->getClientConfigId() !== $that->getClientConfigId()) {
            return false;
        }
        if ($this->getUserId() !== $that->getUserId()) {
            return false;
        }
        if ($this->getScope()->equals($that->getScope())) {
            return false;
        }

        return true;
    }
}
