<?php
namespace Mageants\InstagramIntegration\Plugin;

use Magento\Framework\HTTP\Client\Curl;

class ConfigPlugin
{
    protected $curlClient;

    public function __construct(
        \Magento\Backend\Model\View\Result\Redirect $redirect,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        Curl $curl
    ) {
        $this->curlClient = $curl;
        $this->redirect = $redirect;
        $this->messageManager = $messageManager;
    }

    public function aroundSave(
        \Magento\Config\Model\Config $subject,
        \Closure $proceed
    ) {
        $configData = $subject->getData();
        $updatedBy = "";
        $accessToken = "";
        $hashTag = "";

        if (isset($configData['groups']["instagram_integration"]["fields"]["updateimageby"]["value"])) {
            $updatedBy = $configData['groups']["instagram_integration"]["fields"]["updateimageby"]["value"];
        }

        if (isset($configData['groups']["instagram_integration"]["fields"]["instagram_accesstoken"]["value"])) {
            $accessToken = $configData['groups']["instagram_integration"]["fields"]["instagram_accesstoken"]["value"];
        }
            
        if (isset($configData['groups']["instagram_integration"]["fields"]["update_name"]["value"])) {
            $hashTag = $configData['groups']["instagram_integration"]["fields"]["update_name"]["value"];
        }

        if ($updatedBy == 'user') {
            if ($accessToken) {
                $apiURL= "https://api.instagram.com/v1/users/self/media/recent/?access_token=".$accessToken;
                $this->curlClient->get($apiURL);
                $result = $this->curlClient->getBody();
                
                $data=json_decode($result);
                $data=(array)$data;
                $dt = [];
                if (isset($data["data"])) {
                    foreach ($data["data"] as $test) {
                        $dt[] = $test;
                    }
                    return $proceed();
                } else {
                    $this->messageManager->addError(__('Please Enter Valid Access Token into Configuration Setting.'));
                }
            } else {
                $this->messageManager->addError(__('Please Enter Valid Access Token into Configuration Setting.'));
            }
        } elseif ($hashTag) {
            if (preg_match('/[\'[:;.!`^£$%&*()}{@#~?><>,|=+¬-]/', $hashTag)) {
                $this->messageManager->addError(__('Special character and space not allow in hashTag. Only Underscore allow.'));
                return;
            } elseif (preg_match('/\s/', $hashTag)) {
                $this->messageManager->addError(__('Special character and space not allow in hashTag. Only Underscore allow.'));
                return;
            } else {
                return $proceed();
            }
        } else {
            return $proceed();
        }
    }
}
