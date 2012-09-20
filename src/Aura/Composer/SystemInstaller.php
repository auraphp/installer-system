<?php
namespace Aura\Composer;

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

class SystemInstaller extends LibraryInstaller
{
   /**
    * {@inheritDoc}
    */
    public function getInstallPath(PackageInterface $package)
    {
        list($vendor, $name) = explode('/', $package->getPrettyName());
        $vendor = $this->ucSnakeWords($vendor);
        $name   = $this->ucSnakeWords($name);
        return "package/{$vendor}.{$name}";
    }

    /**
     * {@inheritDoc}
     */
    public function supports($packageType)
    {
        return $packageType == 'aura-package';
    }
    
    private function ucSnakeWords($text)
    {
        $text = str_replace('_', ' ', $text);
        $text = ucwords($text);
        return str_replace(' ', '_', $text);
    }
}
