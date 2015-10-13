<?php
namespace DIW\Magento\Command\CMS\Block;

use DIW\Magento\Command\CMS\AbstractLoadCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadCommand extends AbstractLoadCommand
{
    protected function _getEntryType(){ return 'block'; }

    /**
     * @return \Mage_Cms_Model_Block
     */
    protected function _getCmsModel()
    {
      return $this->_getModel('cms/block', 'Mage_Cms_Model_Block');
    }

    protected function _getIdAttributeName(){ return 'block_id'; }
}
