MageRun CMS Commands
==============

This repository holds an add-on module for "MageRun"

A set of basic CMS import/export commands, allowing CMS Block and CMS
Page data to be backed up or manipulated externally to Magento.

Installation
------------
Clone this repository to ~/.n98-magerun/modules/diw-cms

Verify installation by checking that commands such as cms:block:list are
now available as subcommands to magerun.phar

Commands
--------

 - **cms:block:list** list available CMS block IDs
 - **cms:block:dump** dump (as JSON) either all CMS block data, or (if
   specified) the CMS block data of a specific `block_id`
 - **cms:block:load** load (from a JSON object, or array of JSON objects), CMS block data
 - **cms:page:list** list available CMS page IDs
 - **cms:page:dump** dump (as JSON) either all CMS page data, or (if
   specified) the CMS page data of a specific `page_id`
 - **cms:page:load** load (from a JSON object, or array of JSON objects), CMS page data

