# Slate PHP

Slate PHP is a lightweight PHP starter kit that provides developers with a solid foundation to quickly set up and launch their projects. It is built with a modular approach using predefined functions and classes, along with popular packages configured for Composer to manage dependencies and Bootstrap to handle frontend styling and layout. With Slate PHP, developers can focus on building their applications rather than worrying about repetitive setup tasks, making it an excellent choice for rapid prototyping or small to medium-sized projects.

## Installation

Slate PHP can be installed from the [GitHub repository](https://github.com/bright-webb/slate_php.git) by cloning the repository into your project root directory and running the following commands: `git clone https://github.com/bright-webb/slate_php.git`  
```
composer install 
npm install
```



Please ensure that you have Composer and npm installed and that your PHP environment is configured properly.

Slate PHP uses webpack for compilation with Bootstrap, Lodash, and jQuery as default dependencies. You can also remove these dependencies and add your preferred choice before running `npm install` and `npm run dev` to compile your frontend code, which will be compiled to `assets/css/file.css`. You can change the output of the compiled code in `webpack.config.js`.

By default, Slate PHP uses `Slate` as a namespace, so to autoload your classes automatically, you need to use `Slate` as the namespace. However, you can change this in `composer.json` and then run the command `composer dump-autoload`. You can also add your own function in the `Helper` folder and add it in `composer.json`, making sure to run `composer dump-autoload`. Alternatively, you can add your own custom functions.

Composer searches for classes in the `src` folder, so your classes need to be in the `src` folder, but you can change this in `composer.json`.

## Usage

To use Slate PHP, you need to navigate to your server root directory, `htdocs`, or `www`, as it does not come with its own server. Once you have installed Slate PHP, you can start building your application by adding your code to the `src` folder and your frontend assets to the `assets` folder. You can also add your own functions to the `Helper` folder.

## Files

Slate PHP provides a useful collection of files for developing web applications in PHP.  

`helpers.php`: a file containing predefined functions that can be used to avoid repetitive tasks or to add your own functions.  

`App.php`: a class that provides methods that can be used throughout the entire project. These methods include connecting to the database, querying the database, and other useful methods.  

`Auth.php`: a class that provides methods for authenticating and authorizing users. It acts as a middleware.  

`Connection.php`: a class for database connection.  

`Exception.php`: a class for error handling and custom error handlers.

`Str.php`: a class containing methods for string manipulation.  

## Known Issues

- Slate PHP does not come with its own server and needs to be used from the server root directory, `htdocs`, or `www`.
- If you change the namespace in `composer.json`, you need to run `composer dump-autoload`.
- If you add your own functions to the `Helper` folder, you need to add them to `composer.json` and run `composer dump-autoload`.
- If you change the location of your classes, you need to update the `autoload` section in `composer.json`.

## Limitations

- Slate PHP is designed for rapid prototyping or small to medium-sized projects and may not be suitable for large-scale applications.
- The default dependencies for Slate PHP may not be suitable for all projects and may need to be customized.
- Slate PHP does not come with a built-in authentication system or database layer.


## Helper Functions Documentation
`helpers.php` file contains a collection of helper functions that can be used across your PHP projects. Here are the functions available in this file:  
### Functions List
`slugify($string)`  
Converts a string into a URL friendly slug.
### Parameters
`$string` (string) - The string to be converted into slug format.  
### Return Value:  

Returns a string in slug format.  
### Example Usage:   

```php
echo slugify("This is a test string"); // Output: "this-is-a-test-string"
```


