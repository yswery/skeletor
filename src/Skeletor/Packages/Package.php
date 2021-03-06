<?php
namespace Skeletor\Packages;

use League\Flysystem\Filesystem;
use League\Flysystem\MountManager;
use Skeletor\Manager\ComposerManager;
use Skeletor\Manager\RunManager;
use Skeletor\Packages\Interfaces\PackageInterface;

abstract class Package implements PackageInterface
{
    protected $projectFilesystem;
    protected $composerManager;
    protected $packageOptions = "";
    protected $mountManager;
    protected $runManager;
    protected $installSlug;
    protected $provider;
    protected $version = "";
    protected $options;
    protected $facade;
    protected $envVariables = [];
    protected $name;

    /**
     * Package constructor.
     * @param ComposerManager $composerManager
     * @param Filesystem $projectFilesystem
     * @param MountManager $mountManager
     * @param array $options
     */
    public function __construct(ComposerManager $composerManager, Filesystem $projectFilesystem, MountManager $mountManager, RunManager $runManager, array $options)
    {
        $this->projectFilesystem = $projectFilesystem;
        $this->composerManager = $composerManager;
        $this->mountManager = $mountManager;
        $this->runManager = $runManager;
        $this->options = $options;
        $this->setup();
    }

    /**
     * @return mixed
     */
    public function getInstallSlug()
    {
        return $this->installSlug;
    }

    /**
     * @param string $installSlug
     */
    public function setInstallSlug(string $installSlug)
    {
        $this->installSlug = $installSlug;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param bool $allowEmpty
     * @return string
     */
    public function getVersion(bool $allowEmpty = true)
    {
        if ($allowEmpty === false) {
            return empty($this->version) === true ? 'latest' : $this->version;
        }

        return $this->version;
    }

    /**
     * @param string $version
     */
    public function setVersion(string $version)
    {
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getPackageOptions()
    {
        return $this->packageOptions;
    }

    /**
     * @param string $packageOptions
     */
    public function setPackageOptions(string $packageOptions)
    {
        $this->packageOptions = $packageOptions;
    }

    /**
     * @return mixed
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @param string $provider
     */
    public function setProvider(string $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @return mixed
     */
    public function getFacade()
    {
        return $this->facade;
    }

    /**
     * @param string $facade
     */
    public function setFacade(string $facade)
    {
        $this->facade = $facade;
    }

    /**
     * @return array
     */
    public function getEnvironmentVariables()
    {
        return $this->envVariables;
    }

    /**
     * @param array $vars
     */
    public function setEnvironmentVariables(array $envVariables)
    {
        $this->envVariables = $envVariables;
    }

    /**
     * @return boolean
     */
    public function hasEnvironmentVariables()
    {
        return count($this->envVariables) > 0;
    }

    /**
     * Update the composer.json file of the project. The supplied array will be
     * merged with the existing composer file.
     *
     * @param array $updates
     */
    public function setComposerFileUpdates(array $updates)
    {
        $this->composerManager->updateComposerFile($updates);
    }

    /**
     * Install the composer package
     */
    public function install()
    {
        $command = $this->composerManager->preparePackageCommand($this->getInstallSlug(), $this->getVersion(), $this->getPackageOptions());
        $this->runManager->runCommand($command);
    }

    /**
     * Publish the configuration of the package
     *
     * @return string
     */
    public function publishConfig()
    {
        return shell_exec(sprintf('php artisan vendor:publish --provider="%s"', $this->getProvider()));
    }
}
