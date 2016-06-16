<?php

namespace LArtie\TelegramBotMaker\Core;
use Illuminate\Filesystem\Filesystem;

/**
 * Class Maker
 * @package LArtie\TelegramBotMaker\Core
 */
class Maker
{
    /**
     * Bot name
     * @var string
     */
    private $botName;

    /**
     * Path to bot src directory
     * @var string
     */
    private $path;

    /**
     * Maker constructor.
     * @param $path
     * @param $botName
     */
    public function __construct($path, $botName)
    {
        $this->files = new Filesystem();
        $this->botName = $botName;
        $this->path = base_path(trim($path, '/'));
    }

    /**
     * @return mixed|string
     */
    public function makeServiceProvider()
    {
        $class = ucfirst($this->botName) . 'ServiceProvider';

        $stub = $this->getStub('provider');

        $stub = str_replace(
            'DummyNamespace', $this->getNamespace(), $stub
        );

        $stub = str_replace(
            'DummyClass', $class, $stub
        );

        $stub = str_replace(
            'DummyTelegramController', $this->getNamespace('Controllers') . '\\TelegramController', $stub
        );

        $file = $this->getPathToFile($class . '.php');

        return $this->files->put($file, $stub);
    }

    /**
     * @param $name
     * @param string $token
     * @return int
     */
    public function makeConfig($name, $token = null)
    {
        $stub = $this->getStub('config');

        $stub = str_replace(
            'DummyToken', $token, $stub
        );

        $file = config_path($name . '.php');

        return $this->files->put($file, $stub);

    }

    /**
     * @return int
     */
    public function makeTelegramController()
    {
        $class = 'TelegramController';

        $stub = $this->getStub('telegram_controller');

        $stub = str_replace(
            'DummyNamespace', $this->getNamespace('Controllers'), $stub
        );

        $dir = $this->getPathToFile('Controllers');

        $this->files->makeDirectory($dir);

        $file = $dir . '/' . $class . '.php';

        return $this->files->put($file, $stub);
    }

    /**
     * @param $config
     * @return int
     */
    public function makeBaseCommands($config)
    {
        $class = 'StartCommand';

        $stub = $this->getStub('command');

        $stub = str_replace(
            'DummyNamespace', $this->getNamespace('Commands'), $stub
        );

        $stub = str_replace(
            'DummyClass', $class, $stub
        );

        $stub = str_replace(
            'DummyConfig', $config, $stub
        );

        $dir = $this->getPathToFile('Commands');

        $this->files->makeDirectory($dir);

        $file = $dir . '/' . $class . '.php';

        return $this->files->put($file, $stub);
    }

    /**
     * @param $config
     * @return int
     */
    public function makeRoutes($config)
    {
        $stub = $this->getStub('routes');

        $stub = str_replace(
            'DummyNameTelegramBot', strtolower($this->botName), $stub
        );

        $stub = str_replace(
            'DummyConfig', $config, $stub
        );

        $stub = str_replace(
            'DummyTelegramController', $this->getNamespace('Controllers') . '\\TelegramController', $stub
        );

        $file = $this->getPathToFile('/../routes.php');

        return $this->files->put($file, $stub);
    }

    /**
     * @param null $name
     * @return string
     */
    public function getNamespace($name = null)
    {
        if ($name) {
            $name = '\\' . trim($name, '\\');
        }

        return 'Bots\\' . ucfirst($this->botName) . $name;
    }

    /**
     * @param $name
     * @return string
     */
    private function getPathToFile($name)
    {
        return $this->path . '/' . $name;
    }

    /**
     * @param $name
     * @return string
     */
    private function getStub($name)
    {
        return $this->files->get(__DIR__ . '/../../stubs/' . $name . '.stub');
    }
}