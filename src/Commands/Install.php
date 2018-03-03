<?php

namespace Silvanite\Agencms\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Filesystem\Filesystem;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agencms:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create and register a new Agencms install';

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
     * @return mixed
     */
    public function handle()
    {
        static::ensureHandlerDirectoryExists();
        static::createHandler();
        static::createMiddleware();

        $this->comment('Nice, Agencms is now installed!');
        $this->info('You can customise your Agencms inside \\App\\Handlers\\AgencmsHandler.php');
        $this->info('Don\'t forget to call \\App\\Handlers\\AgencmsHandler::register() from your Service Provider');
    }

    private static function ensureHandlerDirectoryExists()
    {
        $filesystem = new Filesystem;

        if (!$filesystem->isDirectory($directory = app_path('Handlers'))) {
            $filesystem->makeDirectory($directory, 0755, true);
        }
    }

    private static function createHandler()
    {
        if (File::exists($output = app_path('Handlers/AgencmsHandler.php'))) {
            return;
        }

        copy(__DIR__.'/../stubs/AgencmsHandler.stub', $output);
    }

    private static function createMiddleware()
    {
        if (File::exists($output = app_path('Http/Middleware/AgencmsConfig.php'))) {
            return;
        }

        copy(__DIR__.'/../stubs/AgencmsMiddleware.stub', $output);
    }
}
