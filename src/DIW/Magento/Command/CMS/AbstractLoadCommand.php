<?php
namespace DIW\Magento\Command\CMS;

use N98\Magento\Command\AbstractMagentoCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractLoadCommand extends AbstractMagentoCommand
{
    protected function configure()
    {
      $this
        ->setName('cms:'.$this->_getEntryType().':load')
        ->addArgument('file', InputArgument::OPTIONAL, 'Input file to read (defaults to STDIN)')
        ->setDescription('Import a CMS '.ucfirst($this->_getEntryType().' from a JSON object, or JSON array of objects'))
      ;
    }

    /**
     * @return \Mage_Cms_Model
     */
    protected abstract function _getCmsModel();

    /**
     * @return string
     */
    protected abstract function _getIdAttributeName();

    /**
    * @param \Symfony\Component\Console\Input\InputInterface $input
    * @param \Symfony\Component\Console\Output\OutputInterface $output
    * @return int|void
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
      $this->detectMagento($output);

      if ($this->initMagento()) {
        $filename = $input->getArgument('file');
        if ($filename === NULL) {
          $encoded = file_get_contents('php://stdin');
        } else {
          $encoded = file_get_contents($filename);
        }

        $decoded = json_decode($encoded);
        if( is_array($decoded) ){
          $decoded = json_decode($encoded, TRUE);
          foreach( $decoded as $index => $data ){
            if( !is_array($data) ){
              throw new \InvalidArgumentException("Error: input file's described array did not contain objects");
            }

            $this->_storeOne($data);
          }
        } else {
          $decoded = json_decode($encoded, TRUE);
          if (!is_array($decoded)) {
            throw new \InvalidArgumentException("Error: input file did not describe an object or array of objects");
          }

          $this->_storeOne($decoded);
        }
      }
    }
    
    protected function _storeOne($data){
      $id_attribute_name = $this->_getIdAttributeName();
      $entry = $this->_getCmsModel();

      if( isset($data[$id_attribute_name]) ) $entry->load($data[$id_attribute_name]);

      foreach ($data as $key => $value) {
        if ($key === $id_attribute_name) continue;
        $entry->setData($key, $value);
      }

      $entry->save();
    }
}
