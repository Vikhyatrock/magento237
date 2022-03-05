<?php
/**
 * @category Mageants FreeShippingBar
 * @package Mageants_FreeShippingBar
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */
 
namespace Mageants\FreeShippingBar\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Session\SessionManagerInterface as CoreSession;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Cache\Frontend\Pool;

class SetBlockPosition implements \Magento\Framework\Event\ObserverInterface
{
    protected $_helper;

    public function __construct(
        \Mageants\FreeShippingBar\Block\Frontend\FreeShippingBar $freeshippingbar,
        \Magento\Framework\UrlInterface $urlInterface,
        CoreSession $coreSession,
        TypeListInterface $cacheTypeList,
        Pool $cacheFrontendPool
    ) {
        $this->_freeshippingbar = $freeshippingbar;
        $this->_urlInterface = $urlInterface;
        $this->_coreSession = $coreSession;
        $this->cacheTypeList = $cacheTypeList;
        $this->cacheFrontendPool = $cacheFrontendPool;
    }
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        // $types = array('config','layout','block_html','collections','reflection','db_ddl','eav','config_integration','config_integration_api','full_page','translate','config_webservice');
        // foreach ($types as $type) {
        //     $this->_cacheTypeList->cleanType($type);
        // }
        // foreach ($this->_cacheFrontendPool as $cacheFrontend) {
        //     $cacheFrontend->getBackend()->clean();
        // }
        $pos = $this->_freeshippingbar->getCollections()->getData();
        if (!empty($pos)) {
            if (!empty($pos[0]['id'])) {
                if ($pos[0]['id'] == 1) {
                    $this->setValue(0, $pos[0]['id']);
                } else {
                    $this->setValue(0, $pos[0]['id']);
                }
            }
            static $counter = 0;
            $action = $observer->getEvent();
            $fullActionName = $action->getFullActionName();
            // echo $fullActionName;
            $currentUrl = $this->_urlInterface->getCurrentUrl();
            foreach ($this->_freeshippingbar->getCollections() as $collection) {
                $position = $collection->getPosition();
                $excludepage = $collection->getExcludePage();
                $excludepageUrl = $collection->getExcludePageUrl();
                $specificPage = $collection->getSpecificPage();
                $specificPageUrl = $collection->getSpecificPageUrl();
                if ($collection->getAllowedPage()) {
                    if (!empty($specificPage) || !empty($specificPageUrl)) {
                        $specificPages = explode(",", $specificPage);
                        // echo $specificPages;
                        $specificPagesUrl = explode(",", $specificPageUrl);
                        foreach ($specificPages as $actionName) {
                            if ($fullActionName == $actionName) {
                                if ($position == 0) {
                                    // echo "1";
                                    $createXml = '<block class="Mageants\FreeShippingBar\Block\Frontend\FreeShippingBar"
                                        template="Mageants_FreeShippingBar::freeshippingbar.phtml"
                                        name="freeshippingbar_tab_show'.$collection->getId().'"
                                        ifconfig="freeshippingbar/general/enable" />';
                                    $myXml = '<move element="freeshippingbar_tab_show'.$collection->getId().'" destination="after.body.start" />';
                                    $layout = $observer->getEvent()->getLayout();
                                    $layout->getUpdate()->addUpdate($createXml);
                                    $layout->getUpdate()->addUpdate($myXml);
                                    $layout->generateXml();
                                    // $this->cacheTypeList->cleanType('block_html');
                                    // $this->cacheTypeList->cleanType('layout');
                                    // $this->cacheTypeList->cleanType('collections');
                                    // $this->cacheTypeList->cleanType('config');
                                } elseif ($position == 1) {
                                    // echo "2";
                                    $createXml = '<block class="Mageants\FreeShippingBar\Block\Frontend\FreeShippingBar"
                                        template="Mageants_FreeShippingBar::freeshippingbar.phtml"
                                        name="freeshippingbar_tab_show'.$collection->getId().'"
                                        ifconfig="freeshippingbar/general/enable" />';
                                    $myXml = '<move element="freeshippingbar_tab_show'.$collection->getId().'" destination="after.body.start" />';
                                    $layout = $observer->getEvent()->getLayout();
                                    $layout->getUpdate()->addUpdate($createXml);
                                    $layout->getUpdate()->addUpdate($myXml);
                                    $layout->generateXml();
                                    // $this->cacheTypeList->cleanType('block_html');
                                    // $this->cacheTypeList->cleanType('layout');
                                    // $this->cacheTypeList->cleanType('collections');
                                    // $this->cacheTypeList->cleanType('config');
                                } elseif ($position == 2) {
                                    // echo "3";
                                    $createXml = '<block class="Mageants\FreeShippingBar\Block\Frontend\FreeShippingBar"
                                        template="Mageants_FreeShippingBar::freeshippingbar.phtml"
                                        name="freeshippingbar_tab_show'.$collection->getId().'"
                                        ifconfig="freeshippingbar/general/enable" />';
                                    $myXml = '<move element="freeshippingbar_tab_show'.$collection->getId().'" destination="content.top" before="-"/>';
                                    $layout = $observer->getEvent()->getLayout();
                                    $layout->getUpdate()->addUpdate($createXml);
                                    $layout->getUpdate()->addUpdate($myXml);
                                    $layout->generateXml();
                                    // $this->cacheTypeList->cleanType('block_html');
                                    // $this->cacheTypeList->cleanType('layout');
                                    // $this->cacheTypeList->cleanType('collections');
                                    // $this->cacheTypeList->cleanType('config');
                                } elseif ($position == 3) {
                                    // echo "4";
                                    $createXml = '<block class="Mageants\FreeShippingBar\Block\Frontend\FreeShippingBar"
                                        template="Mageants_FreeShippingBar::freeshippingbar.phtml"
                                        name="freeshippingbar_tab_show'.$collection->getId().'"
                                        ifconfig="freeshippingbar/general/enable" />';
                                    $myXml = '<move element="freeshippingbar_tab_show'.$collection->getId().'" destination="before.body.end" />';
                                    $layout = $observer->getEvent()->getLayout();
                                    $layout->getUpdate()->addUpdate($createXml);
                                    $layout->getUpdate()->addUpdate($myXml);
                                    $layout->generateXml();
                                    // $this->cacheTypeList->cleanType('block_html');
                                    // $this->cacheTypeList->cleanType('layout');
                                    // $this->cacheTypeList->cleanType('collections');
                                    // $this->cacheTypeList->cleanType('config');
                                }
                            } else {
                                // echo "5";
                                $createXml = '';
                                $myXml = '';
                                $layout = $observer->getEvent()->getLayout();
                                $layout->getUpdate()->addUpdate($createXml);
                                $layout->getUpdate()->addUpdate($myXml);
                                $layout->generateXml();
                            }
                        }
                        foreach ($specificPagesUrl as $actionUrl) {
                            if ($currentUrl == $actionUrl) {
                                if ($position == 0) {
                                    // echo "6";
                                    $createXml = '<block class="Mageants\FreeShippingBar\Block\Frontend\FreeShippingBar"
                                        template="Mageants_FreeShippingBar::freeshippingbar.phtml"
                                        name="freeshippingbar_tab_show'.$collection->getId().'"
                                        ifconfig="freeshippingbar/general/enable" />';
                                    $myXml = '<move element="freeshippingbar_tab_show'.$collection->getId().'" destination="after.body.start" />';
                                    $layout = $observer->getEvent()->getLayout();
                                    $layout->getUpdate()->addUpdate($createXml);
                                    $layout->getUpdate()->addUpdate($myXml);
                                    $layout->generateXml();
                                    // $this->cacheTypeList->cleanType('block_html');
                                    // $this->cacheTypeList->cleanType('layout');
                                    // $this->cacheTypeList->cleanType('collections');
                                    // $this->cacheTypeList->cleanType('config');
                                } elseif ($position == 1) {
                                    // echo "7";
                                    $createXml = '<block class="Mageants\FreeShippingBar\Block\Frontend\FreeShippingBar"
                                        template="Mageants_FreeShippingBar::freeshippingbar.phtml"
                                        name="freeshippingbar_tab_show'.$collection->getId().'"
                                        ifconfig="freeshippingbar/general/enable" />';
                                    $myXml = '<move element="freeshippingbar_tab_show'.$collection->getId().'" destination="after.body.start" />';
                                    $layout = $observer->getEvent()->getLayout();
                                    $layout->getUpdate()->addUpdate($createXml);
                                    $layout->getUpdate()->addUpdate($myXml);
                                    $layout->generateXml();
                                    // $this->cacheTypeList->cleanType('block_html');
                                    // $this->cacheTypeList->cleanType('layout');
                                    // $this->cacheTypeList->cleanType('collections');
                                    // $this->cacheTypeList->cleanType('config');
                                } elseif ($position == 2) {
                                    // echo "8";
                                    $createXml = '<block class="Mageants\FreeShippingBar\Block\Frontend\FreeShippingBar"
                                        template="Mageants_FreeShippingBar::freeshippingbar.phtml"
                                        name="freeshippingbar_tab_show'.$collection->getId().'"
                                        ifconfig="freeshippingbar/general/enable" />';
                                    $myXml = '<move element="freeshippingbar_tab_show'.$collection->getId().'" destination="content.top" before="-"/>';
                                    $layout = $observer->getEvent()->getLayout();
                                    $layout->getUpdate()->addUpdate($createXml);
                                    $layout->getUpdate()->addUpdate($myXml);
                                    $layout->generateXml();
                                    // $this->cacheTypeList->cleanType('block_html');
                                    // $this->cacheTypeList->cleanType('layout');
                                    // $this->cacheTypeList->cleanType('collections');
                                    // $this->cacheTypeList->cleanType('config');
                                } elseif ($position == 3) {
                                    // echo "9";
                                    $createXml = '<block class="Mageants\FreeShippingBar\Block\Frontend\FreeShippingBar"
                                        template="Mageants_FreeShippingBar::freeshippingbar.phtml"
                                        name="freeshippingbar_tab_show'.$collection->getId().'"
                                        ifconfig="freeshippingbar/general/enable" />';
                                    $myXml = '<move element="freeshippingbar_tab_show'.$collection->getId().'" destination="before.body.end" />';
                                    $layout = $observer->getEvent()->getLayout();
                                    $layout->getUpdate()->addUpdate($createXml);
                                    $layout->getUpdate()->addUpdate($myXml);
                                    $layout->generateXml();
                                    // $this->cacheTypeList->cleanType('block_html');
                                    // $this->cacheTypeList->cleanType('layout');
                                    // $this->cacheTypeList->cleanType('collections');
                                    // $this->cacheTypeList->cleanType('config');
                                }
                            } else {
                                // echo "10";
                                $createXml = '';
                                $myXml = '';
                                $layout = $observer->getEvent()->getLayout();
                                $layout->getUpdate()->addUpdate($createXml);
                                $layout->getUpdate()->addUpdate($myXml);
                                $layout->generateXml();
                            }
                        }
                    }
                } else {
                    if (!empty($excludepage) || !empty($excludepageUrl)) {
                        $excludepages = explode(",", $excludepage);
                        $excludepagesUrl = explode(",", $excludepageUrl);
                        foreach ($excludepages as $actionName) {
                            if ($fullActionName == $actionName) {
                                // echo "11";
                                $createXml = '<block class="Mageants\FreeShippingBar\Block\Frontend\FreeShippingBar"
                                    template="Mageants_FreeShippingBar::freeshippingbar.phtml"
                                    name="freeshippingbar_tab_show'.$collection->getId().'"
                                    ifconfig="freeshippingbar/general/enable" />';
                                $myXml = '<referenceBlock name="freeshippingbar_tab_show'.$collection->getId().'" remove="true"/>';
                                $layout = $observer->getEvent()->getLayout();
                                $layout->getUpdate()->addUpdate($createXml);
                                $layout->getUpdate()->addUpdate($myXml);
                                $layout->generateXml();
                                // $this->cacheTypeList->cleanType('block_html');
                                // $this->cacheTypeList->cleanType('layout');
                                // $this->cacheTypeList->cleanType('collections');
                                // $this->cacheTypeList->cleanType('config');
                                $counter++;
                            }
                        }
                        foreach ($excludepagesUrl as $actionUrl) {
                            if ($currentUrl == $actionUrl) {
                                // echo "12";
                                $createXml = '<block class="Mageants\FreeShippingBar\Block\Frontend\FreeShippingBar"
                                    template="Mageants_FreeShippingBar::freeshippingbar.phtml"
                                    name="freeshippingbar_tab_show'.$collection->getId().'"
                                    ifconfig="freeshippingbar/general/enable" />';
                                $myXml = '<referenceBlock name="freeshippingbar_tab_show'.$collection->getId().'" remove="true"/>';
                                $layout = $observer->getEvent()->getLayout();
                                $layout->getUpdate()->addUpdate($createXml);
                                $layout->getUpdate()->addUpdate($myXml);
                                $layout->generateXml();
                                // $this->cacheTypeList->cleanType('block_html');
                                // $this->cacheTypeList->cleanType('layout');
                                // $this->cacheTypeList->cleanType('collections');
                                // $this->cacheTypeList->cleanType('config');
                                $counter++;
                            }
                        }
                    }
                    if ($counter == 0) {
                        $createXml = '';
                        if ($position == 0) {
                            // echo "13";
                            // echo '<script type="text/javascript">alert("'.$collection->getId().'");</script>';
                            $createXml = '<block class="Mageants\FreeShippingBar\Block\Frontend\FreeShippingBar"
                                    template="Mageants_FreeShippingBar::freeshippingbar.phtml"
                                    name="freeshippingbar_tab_show'.$collection->getId().'"
                                    ifconfig="freeshippingbar/general/enable" />';
                            $myXml = '<move element="freeshippingbar_tab_show'.$collection->getId().'" destination="after.body.start" />';
                            $layout = $observer->getEvent()->getLayout();
                            $layout->getUpdate()->addUpdate($createXml);
                            $layout->getUpdate()->addUpdate($myXml);
                            $layout->generateXml();
                            // $this->cacheTypeList->cleanType('block_html');
                            // $this->cacheTypeList->cleanType('layout');
                            // $this->cacheTypeList->cleanType('collections');
                            // $this->cacheTypeList->cleanType('config');
                        } elseif ($position == 1) {
                            // echo "14";
                            $createXml = '<block class="Mageants\FreeShippingBar\Block\Frontend\FreeShippingBar"
                                    template="Mageants_FreeShippingBar::freeshippingbar.phtml"
                                    name="freeshippingbar_tab_show'.$collection->getId().'"
                                    ifconfig="freeshippingbar/general/enable" />';
                            $myXml = '<move element="freeshippingbar_tab_show'.$collection->getId().'" destination="after.body.start"/>';
                            $layout = $observer->getEvent()->getLayout();
                            $layout->getUpdate()->addUpdate($createXml);
                            $layout->getUpdate()->addUpdate($myXml);
                            $layout->generateXml();
                            // $this->cacheTypeList->cleanType('block_html');
                            // $this->cacheTypeList->cleanType('layout');
                            // $this->cacheTypeList->cleanType('collections');
                            // $this->cacheTypeList->cleanType('config');
                        } elseif ($position == 2) {
                            // echo "15";
                            $createXml = '<block class="Mageants\FreeShippingBar\Block\Frontend\FreeShippingBar"
                                    template="Mageants_FreeShippingBar::freeshippingbar.phtml"
                                    name="freeshippingbar_tab_show'.$collection->getId().'"
                                    ifconfig="freeshippingbar/general/enable" />';
                            $myXml = '<move element="freeshippingbar_tab_show'.$collection->getId().'" destination="content.top" before="-" />';
                            $layout = $observer->getEvent()->getLayout();
                            $layout->getUpdate()->addUpdate($createXml);
                            $layout->getUpdate()->addUpdate($myXml);
                            $layout->generateXml();
                            // $this->cacheTypeList->cleanType('block_html');
                            // $this->cacheTypeList->cleanType('layout');
                            // $this->cacheTypeList->cleanType('collections');
                            // $this->cacheTypeList->cleanType('config');
                        } elseif ($position == 3) {
                            // echo "16";
                            $createXml = '<block class="Mageants\FreeShippingBar\Block\Frontend\FreeShippingBar"
                                    template="Mageants_FreeShippingBar::freeshippingbar.phtml"
                                    name="freeshippingbar_tab_show'.$collection->getId().'"
                                    ifconfig="freeshippingbar/general/enable" />';
                            $myXml = '<move element="freeshippingbar_tab_show'.$collection->getId().'" destination="before.body.end" />';
                            $layout = $observer->getEvent()->getLayout();
                            $layout->getUpdate()->addUpdate($createXml);
                            $layout->getUpdate()->addUpdate($myXml);
                            $layout->generateXml();
                            // $this->cacheTypeList->cleanType('block_html');
                            // $this->cacheTypeList->cleanType('layout');
                            // $this->cacheTypeList->cleanType('collections');
                            // $this->cacheTypeList->cleanType('config');
                        }
                    }
                }
            }
        }
    }
    public function setValue($pos, $fid)
    {
        $this->_coreSession->start();
        $this->_coreSession->setFreeShippingBarId($pos);
        $this->_coreSession->setFid($fid);
    }
}
