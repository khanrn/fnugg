# Fnugg
A Gutenberg block for rendering Fnugg.no API data with autocomplete support from Fnugg API.

## Table Of Contents

* [Installation](#installation)
* [Usage](#usage)
* [Coding styles and technique](#coding-styles-and-technique)
* [System Architecture and Solution Design](#System-Architecture-and-Solution-Design)
* [License](#license)
* [Contributing](#contributing)

## Installation

The best way to use this package is through Composer:

1. Clone the repo in your WordPress `wp-content/plugins` directory-
```BASH
$ git clone git@github.com:codemascot/fnugg.git
```

2. Enter the directory-
```BASH
$ cd /PATH_TO_YOUR_WP_SETUP/wp-content/plugins/fnugg
```

3. Run below command there-

```BASH
$ composer install
$ npm run build
```

4. For watching your assets(CSS/JS) changes you can use the below command-

```BASH
$ npm start
```

> **Note that you need `composer` and `npm` installed in your system to successfully execute these above commands.**

## Usage

### Backend or Admin
Usage is pretty simple. You'll get a block named **Fnugg** out of the box in the blocks list in Gutenberg. You just need to bring this block to your page. Then typing any resort name prefixed with `~`(tilda) you'll be presented an auto-complete list. Choose your resort name and save it. For verifying it the exact resort you have choose you can cross checked with the site path just below the field.

### Frontend
Well, this plugin is designed keeping modularity in mind. So, instead of providing a solid frontend it leverages the WordPress hooking system for this. There is an action hook name `fnugg_frontend_render_html` where you can hook your HTML template. You can modify the search parameters as well with `fnugg_frontend_self_api_search_params` filter hook. For more details have a look at the `render()` method of the [`Block\Block.php`](https://github.com/codemascot/fnugg/blob/main/inc/Block/Block.php) class. Just FYI, our native API's are designed in such a way that it can handle any number of GET parameters it get.

## Coding styles and technique
* All input data escaped and validated.
* Developed as *Composer* package.
* **YODA** condition check applied.
* Used `true`, `false` and `null` in stead of `TRUE`, `FALSE` and `NULL`.
* **INDENTATION:** *SPACES* has been used in stead of *TABS*.
* *PHP Codesniffer* checked.
* *PSR2* coding standard followed mostly.
* Modern PHP code, tested with PHP 7.4
* Designed totally *OOP* way.
* Strictly typed, all methods, properties and returns.
* All the classes, methods, properties, parameters, hooks etc. are 100% documented with *PHPDoc*.
* Basic unit testing scaffolding initiated.

## System Architecture and Solution Design

This project is designed modular way, means you can swap it's most of the parts without modifying it's core code. And for achieving this, mostly below techniques and strategies has been used-

### WordPress's Hooking API
In this codebase you'll find several hooks to manipulate this plugin's data. Though, in some cases some hook may seem redundant, still I think those hooks should be there. Because, in my opinion keeping an hook doesn't hurt, but not having a hook sometime make a whole lot difference. Therefore, to me having some extra hooks is a fair trade off.

### Dependency Injection
For most of dependency for the modules are injected, which helps them to be testeable. Though, no unit test has been written for this codebase, a basic scaffolding for unit testing is there. Also, having dependency injection in the codebase makes the modules easily swapable as well as loosely coupled.

### Data Abstraction Layer
Data abstraction layer is used here to separate the data source from the main modules. For this the `Fnugg\Data\Fetch` module has been used implementing `Fnugg\Data\Data` interface. Anyone can swap the `$fetch` object by passing another object of type `\Fnugg\Data\Data` using the `fnugg_fetch_object` filter hook and the other modules will never know that happened. Because they only recognize an object of `\Fnugg\Data\Data` type.

## License
Copyright (c) 2021 [CodeMascot](https://www.codemascot.com/) AKA [Khan Mohammad R.](https://www.codemascot.com/)

Good news, this plugin is free for everyone! Since it's released under the [GPL-2.0 License](LICENSE) you can use it free of charge on your personal or commercial website.

## Contributing

All feedback / bug reports / pull requests are welcome.
