<?php
namespace DIW\Magento\Command\CMS;

use N98\Magento\Command\AbstractMagentoCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractListCommand extends AbstractMagentoCommand
{
    protected function configure()
    {
      $this
        ->setName('cms:'.$this->_getEntryType().':list')
        ->addOption('format', '', InputOption::VALUE_REQUIRED, 'Output format, one of "table" (the default) or "json"')
        ->setDescription('List CMS '.ucfirst($this->_getEntryType()).'s')
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
      $format = $input->getOption('format');
      if ($format === NULL) $format = 'table';
      if (!in_array($format, array('table', 'json'))) {
        throw new \InvalidArgumentException(sprintf('Error unknown output format: "%s"', $format));
      }

      $id_attribute_name = $this->_getIdAttributeName();

      if ($this->initMagento()) {
        if ($format === 'table') $rows = array();
        foreach ($this->_getCmsModel()->getCollection() as $page) {
          switch ($format) {
            case 'json':
              $output->writeln(json_encode(array(
                $id_attribute_name => $page->getData($id_attribute_name),
                'identifier' => $page->getData('identifier'),
                'title' => $page->getData('title')
              )));
              break;

            default:
              $rows[] = array(
                $page->getData($id_attribute_name),
                $page->getData('identifier'),
                $page->getData('title')
              );
              break;
          }
        }

        if ($format === 'table') {
          $table = $this->getHelper('table');
          $table
            ->setHeaders(array($id_attribute_name, 'identifier', 'title'))
            ->setRows($rows)
          ;
          $table->render($output);
        }
      }
    }
}
