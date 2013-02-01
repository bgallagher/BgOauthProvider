<?php
namespace BgOauthProvider\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{

    /**
     * @var bool
     */
    protected $disableLayoutOnAuthorisationPage = false;

    /**
     * @var array
     */
    protected $aclConfig = array();

    /**
     * @param boolean $disableLayoutOnAuthorisationPage
     */
    public function setDisableLayoutOnAuthorisationPage($disableLayoutOnAuthorisationPage)
    {
        $this->disableLayoutOnAuthorisationPage = $disableLayoutOnAuthorisationPage;
    }

    /**
     * @return boolean
     */
    public function getDisableLayoutOnAuthorisationPage()
    {
        return $this->disableLayoutOnAuthorisationPage;
    }

    /**
     * @param array $aclConfig
     */
    public function setAclConfig($aclConfig)
    {
        $this->aclConfig = $aclConfig;
    }

    /**
     * @return array
     */
    public function getAclConfig()
    {
        return $this->aclConfig;
    }

}
