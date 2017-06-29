<?php
namespace DIW\Magento\Command\CMS;

use N98\Magento\Command\AbstractMagentoCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractDumpCommand extends AbstractMagentoCommand
{
    /**
     * @return string
     */
    protected abstract function _getEntryType();

    protected function configure()
    {
      $this
        ->setName('cms:'.$this->_getEntryType().':dump')
        ->addArgument('entry_id', InputArgument::OPTIONAL, 'ID of '.ucfirst($this->_getEntryType()).' to output (omit for "all")')
        ->setDescription('Output a CMS '.ucfirst($this->_getEntryType()).' in a machine-readable format')
      ;
    }

    /**
     * @return \Mage_Cms_Model
     */
    protected abstract function _getCmsModel();

   /**
    * @param \Symfony\Component\Console\Input\InputInterface $input
    * @param \Symfony\Component\Console\Output\OutputInterface $output
    * @return int|void
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
      $this->detectMagento($output);
      if ($this->initMagento()) {
        $entryId = $input->getArgument('entry_id');
        if( $entryId !== NULL ){
          $entry = $this->_getCmsModel()->load($entryId);
          $output->writeln(json_encode($entry->getData()));
        } else {
          $entries = $this->_getCmsModel()->getCollection()->addFieldToSelect('*');
          $output->writeln('[');
          $previous = NULL;
          foreach( $entries as $entry ){
            if( $previous !== NULL ) $output->writeln($previous.',');
            $previous = json_encode($entry->getData());
          }
          if( $previous !== NULL ) $output->writeln($previous);
          $output->writeln(']');
        }
      }
    }
}
