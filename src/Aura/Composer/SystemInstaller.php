<?php
namespace Aura\Composer;

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

class SystemInstaller extends LibraryInstaller
{
    /**
     * {@inheritDoc}
     */
    public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        // do the installation
        parent::install($repo, $package);
        list($vendor, $name) = $this->getVendorAndName($package);
        $new = "{$vendor}.{$name}";
        
        // is the package already listed?
        $packages = $this->readConfigPackages();
        foreach ($packages as $old) {
            if ($old == $new) {
                // already listed, so we're done
                return;
            }
        }
        
        // package was not listed; append to the list and write to disk
        $packages[] = $new;
        $this->writeConfigPackages($packages);
    }
    
    /**
     * {@inheritDoc}
     */
    public function uninstall(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        // do the installation
        parent::uninstall($repo, $package);
        list($vendor, $name) = $this->getVendorAndName($package);
        
        // remove the package from the file listing
        $packages = $this->readConfigPackages();
        foreach ($packages as $key => $val) {
            if ($val == "{$vendor}.{$name}") {
                unset($packages[$key]);
                break;
            }
        }
        
        // write the list back to disk
        $this->writeConfigPackages($packages);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getInstallPath(PackageInterface $package)
    {
        list($vendor, $name) = $this->getVendorAndName($package);
        return "package/{$vendor}.{$name}";
    }

    /**
     * {@inheritDoc}
     */
    public function supports($packageType)
    {
        return $packageType == 'aura-package';
    }
    
    /**
     * 
     * Returns the path to the "config/_packages" file.
     * 
     * @return string
     * 
     */
    private function getConfigPackagesFile()
    {
        // system/vendor/aura/installer-system/src/Aura/Composer/SystemInstaller.php
        $root = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
        return $root . DIRECTORY_SEPARATOR 
             . 'config' . DIRECTORY_SEPARATOR
             . '_packages';
    }
    
    /**
     * 
     * Reads the "config/_packages" file.
     * 
     * @return array An array of packages named in the file.
     * 
     */
    private function readConfigPackages()
    {
        $packages = file($this->getConfigPackagesFilePath());
        array_walk($packages, 'trim');
        return $packages;
    }
    
    /**
     * 
     * Writes an array back to "config/_packages".
     * 
     * @param array $packages A list of installed packages.
     * 
     * @return void
     * 
     */
    private function writeConfigPackages(array $packages)
    {
        $text = implode(PHP_EOL, $packages) . PHP_EOL;
        file_put_contents($this->getConfigPackagesFilePath(), $text);
    }
    
    /**
     * 
     * Given a PackageInterface, returns the package vendor and name.
     * 
     * @param PackageInterface $package The package to work with.
     * 
     * @return array An array where element 0 is the package vendor and
     * element 1 is the package name.
     * 
     */
    private function getVendorAndName(PackageInterface $package)
    {
        list($vendor, $name) = explode('/', $package->getPrettyName());
        $vendor = $this->ucSnakeWords($vendor);
        $name = $this->ucSnakeWords($name);
        return array($vendor, $name);
    }
    
    /**
     * 
     * Converts "this_text" to "ThisText".
     * 
     * @param string $text The string to convert.
     * 
     * @return string The converted string.
     * 
     */
    private function ucSnakeWords($text)
    {
        $text = str_replace('_', ' ', $text);
        $text = ucwords($text);
        return str_replace(' ', '_', $text);
    }
}
