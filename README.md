Composer Installer for Aura System
==================================

Using this installer, all [Composer][] packages of `"type" : "aura-package"`
will be installed under `package/Vendor_Name.Package_Name` (note the
directory, capitalization, and punctuation). This is a layout specific to the
[Aura system][].

Aura packages **do not** need to be installed in a particular location when
used outside of an Aura system. If you install an `aura-package` without this
installer, the package will be installed to the default Composer location of
`vendor/vendor_name/package_name`. As such, if you are not building an Aura
system, you probably don't need this installer.

Note that this is not an Aura package proper; it is designed for use directly
with Composer, and as such will be placed in `vendor` not `package`.

[Composer]: http://getcomposer.org/
[Aura system]: https://github.com/auraphp/system

Running the Tests
-----------------

After you clone this repository ...

1.  Install PHPUnit and Composer.

2.  Run Composer to install the development requirements:

        $ composer install --dev
        
3.  Change to the `tests` directory and issue `phpunit`.

