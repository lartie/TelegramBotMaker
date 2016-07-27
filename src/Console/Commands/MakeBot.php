<?php
/**
 * Created by PhpStorm.
 * User: macbookpro
 * Date: 16.06.16
 * Time: 12:35
 */

namespace LArtie\TelegramBotMaker\Console\Commands;


use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use LArtie\TelegramBotMaker\Core\Maker;

class MakeBot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:telegram-bot {name : The name of your bot }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new telegram bot';
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * Create a new command instance.
     *
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();
        $this->filesystem = $filesystem;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');

        //$pathToBotsDir = trim($this->ask('Path to your bot?', '/Bots'), '/');

        $pathToBot = '/bots' . "/" . $name;
        $pathToBotSrc = $pathToBot . "/src";

        if (!$this->filesystem->exists(base_path(trim($pathToBot, '/')))) {

            $this->filesystem->makeDirectory(base_path($pathToBotSrc), 0777, true);

        } else {
            $this->error('Ошибка. Путь занят.');

            exit;
        }

        $maker = new Maker($pathToBotSrc, $name);

        $maker->makeServiceProvider();

        $config = $this->ask('Введите имя для файла конфигурации (Если файл существует, он будет перезаписан.):', strtolower($name));

        $token = $this->ask('Введите токен данного бота:', 'SomeString');

        $maker->makeConfig($config, $token);

        $maker->makeTelegramController();

        $maker->makeBaseCommands($config);

        $maker->makeRoutes($config);

        $this->successInstall($maker->getNamespace(), $pathToBotSrc);
    }

    /**
     * @param $namespace
     * @param $srcPath
     */
    private function successInstall($namespace, $srcPath)
    {
        $this->line("Каркас бота успешно установлен. Вы должны добавить путь к боту в файле comsoper.json");

        $namespace = str_replace('\\', '\\\\', $namespace) . '\\\\';

        $this->info("Вставьте в psr-4 блок данную запись: \n \"{$namespace}\" : \"{$srcPath}\"");
    }
}