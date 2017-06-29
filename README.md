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

What It Does
------------

The `cms:*:dump` commands dump either a single model, or each of a
collection of models, and directly output the result of
`$model->getData()` as a JSON object to STDOUT.

The `cms:*:load` commands read a JSON object (or each object from a JSON
array of objects). If a relevant `*_id` attribute is set within the JSON
object, that object is `->load()`ed; otherwise, an empty model is
created. In either case, the decoded JSON object is passed to
`$model->setData()` directly, and the model is saved. ie: if the `*_id`
field is not set, a new page/block will be created.

As should be obvious, this method of dumping / loading CMS data is very
basic, and there are many situations in which these methods would not be
appropriate or safe. No gaurantees are made. You should perform both
dumps and loads only with a complete understanding of the limitations of
this implementation.

Examples
--------

**You can dump a specific page or block as a single json object to STDOUT, by identifier**

    $ magerun.phar cms:page:dump home
    {"page_id":"2","title":"My eCommerce Site","root_template":"homepage",...

 **...or by numeric id**

    $ magerun.phar cms:page:dump 2
    {"page_id":"2","title":"My eCommerce Site","root_template":"homepage",...

**You can also dump *all* pages / blocks, as a json array of json objects, by omitting the id/identifier**

    $ magerun.phar cms:page:dump
    [
    {"page_id":"1","title":"404 Not Found","root_template":"one_column",...},
    {"page_id":"2","title":"My eCommerce Site","root_template":"homepage",...},
    ...

**The same format can also be used to re-import a dumped page or block**

    $ magerun.phar cms:page:dump home > home.json
    ... edit home.json ...
    $ magerun.phar cms:page:load < home.json

**...or a list of multiple pages or blocks**

    $ magerun.phar cms:page:dump > pages.json
    ... edit pages.json ...
    $ magerun.phar cms:page:load < pages.json

History
-------
This tool was originally written as part of a system to track and audit
changes to CMS data using an external tool (not included here). The
"load" function was later added when the afformentioned tool was found
to be a useful backup copy of CMS data. Since then, the "dump" function
has been often used when searching for specific CMS data, or when
debugging issues with CMS pages or blocks. Due to its generic
usefulness, proprietary code was removed from the project, and the
utility was re-uploaded as an independent module for MageRun.
