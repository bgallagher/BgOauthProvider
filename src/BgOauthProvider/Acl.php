<?php

namespace BgOauthProvider;

use Zend\Permissions\Acl\Acl as ZendAcl;

class Acl extends ZendAcl
{

    const TWO_LEGGED = '2l';
    const THREE_LEGGED = '3l';

    public function __construct(array $permissions = array())
    {
        $this->addRole(self::TWO_LEGGED);
        $this->addRole(self::THREE_LEGGED);

        foreach ($permissions as $permission) {
            $resourceName = $permission['routeName'];
            $privileges = $permission['method'];
            $role = $permission['role'];

            if (!$this->hasResource($resourceName)) {
                $this->addResource($permission['routeName']);
            }

            $this->allow($role, $resourceName, $privileges);
        }
    }
}