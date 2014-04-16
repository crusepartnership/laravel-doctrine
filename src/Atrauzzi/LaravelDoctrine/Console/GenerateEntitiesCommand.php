<?php namespace Atrauzzi\LaravelDoctrine\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Doctrine\ORM\Tools\DisconnectedClassMetadataFactory;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class GenerateEntitiesCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'doctrine:entities:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates entities from mapping metadata.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $update = $this->option('update');
        $regenerate = $this->option('regenerate');
        $generateMethods = $this->option('generate-methods');
        $backupExisting = ! $this->option('no-backup');

        $this->comment('ATTENTION: This operation should not be executed in a production environment.');

        $this->info('Obtaining metadata from your models...');
        $entityManager = App::make('doctrine');
        $disconnectedMetadataFactory = new DisconnectedClassMetadataFactory();
        $disconnectedMetadataFactory->setEntityManager($entityManager);
        $metadatas = $disconnectedMetadataFactory->getAllMetadata();

        $entityGenerator = App::make('doctrine.entity-generator');
        $entityGenerator->setUpdateEntityIfExists($update);
        $entityGenerator->setRegenerateEntityIfExists($regenerate);
        $entityGenerator->setGenerateStubMethods($generateMethods);
        $entityGenerator->setBackupExisting($backupExisting);

        $this->info('Generating database entities...');
        $entityPath = Config::get('laravel-doctrine::doctrine.entity_generator.directory');
        $entityGenerator->generate($metadatas, $entityPath);
        $this->info('Database entities generated successfully!');

    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('update', null, InputOption::VALUE_NONE, 'Entities are updated from metadata mapping.'),
            array('regenerate', null, InputOption::VALUE_NONE, 'Entities are regenerated from metadata mapping'),
            array('generate-methods', null, InputOption::VALUE_NONE, 'Entities generated with getter and setter methods'),
            array('no-backup', null, InputOption::VALUE_NONE, 'Existing entities are not backed up when updated or regenerated')
        );
    }

}
