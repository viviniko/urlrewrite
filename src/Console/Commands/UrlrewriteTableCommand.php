<?php

namespace Viviniko\Urlrewrite\Console\Commands;

use Viviniko\Support\Console\CreateMigrationCommand;

class UrlrewriteTableCommand extends CreateMigrationCommand
{
    /**
     * @var string
     */
    protected $name = 'urlrewrite:table';

    /**
     * @var string
     */
    protected $description = 'Create a migration for the url rewrite service table';

    /**
     * @var string
     */
    protected $stub = __DIR__.'/stubs/urlrewrite.stub';

    /**
     * @var string
     */
    protected $migration = 'create_urlrewrite_table';
}
