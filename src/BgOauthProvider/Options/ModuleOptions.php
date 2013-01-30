<?php
namespace BgOauthProvider\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{

    /**
     * @var bool
     */
    protected $disableLayoutOnAuthorisationPage = false;

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

    public function setAclConfig(array $aclConfig)
    {
        $this->aclConfig = $aclConfig;
    }

    public function getAclConfig()
    {
        return $this->aclConfig;
    }

}
