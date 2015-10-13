<?php
namespace DIW\Magento\Command\CMS\Page;

use DIW\Magento\Command\CMS\AbstractListCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListCommand extends AbstractListCommand
{
    protected function _getEntryType(){ return 'page'; }

    /**
     * @return \Mage_Cms_Model_Page
     */
    protected function _getCmsModel()
    {
      return $this->_getModel('cms/page', 'Mage_Cms_Model_Page');
    }

    protected function _getIdAttributeName(){ return 'page_id'; }
}
