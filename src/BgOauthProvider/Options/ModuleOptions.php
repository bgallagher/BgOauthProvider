<?php
namespace BgOauthProvider\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{

    /**
     * @var bool
     */
    protected $disableLayoutOnAuthenticationPage = false;

    protected $aclConfig = array();

    /**
     * @param boolean $disableLayoutOnAuthenticationPage
     */
    public function setDisableLayoutOnAuthenticationPage($disableLayoutOnAuthenticationPage)
    {
        $this->disableLayoutOnAuthenticationPage = $disableLayoutOnAuthenticationPage;
    }

    /**
     * @return boolean
     */
    public function getDisableLayoutOnAuthenticationPage()
    {
        return $this->disableLayoutOnAuthenticationPage;
    }

    public function setAclConfig(array $aclConfig)
    {
        $this->aclConfig = $aclConfig;
    }

    public function getAclConfig()
    {
        return $this->aclConfig;
    }

}
