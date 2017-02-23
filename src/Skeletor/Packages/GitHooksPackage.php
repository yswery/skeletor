<?php
namespace Skeletor\Packages;

use League\Flysystem\Filesystem;
use Skeletor\Manager\ComposerManager;

class GitHooksPackage extends Package
{
    public function __construct(ComposerManager $composerManager, Filesystem $filesystem)
    {
        parent::__construct($composerManager, $filesystem);
        $this->setPackage('pixelfusion/git-hooks');
        $this->setName("PixelFusion Git Hooks");
    }

    public function tidyUp()
    {
        $this->filesystem->put('PixelFusion.txt', '©PIXELFUSION');
    }
}