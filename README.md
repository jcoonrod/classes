# classes

* This is the set of classes (as of 12/2022) converted into a Composer Package
* To use it, First 
```
composer require jcoonrod/classes
```
* This will load the classes inside vendor, using the namespace thpglobal/classes
* With luck, this will create an entire app with just two includes - thpsecurity and menu
* Second - copy the files from the static folder here into your app static folder

## Note - Static Files!
* The first time you install this package, you need to create symbolic links to four files from your static area into the static folder inside this package, eg:
$ cd ./static
ln -sf module.js ../vendor/jcoonrod/static/module.js
* and ditto for classes.css, font-awesome.css and fontawesome-webfont.woff
