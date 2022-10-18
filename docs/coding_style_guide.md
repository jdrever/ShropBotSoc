---
layout: default
title: Style Guide
navigation_weight: 2
---
# Coding Style Guide

- [Coding Style Guide](#coding-style-guide)
	- [PHP](#php)
		- [1. Install PHP_CodeSniffer](#1-install-php_codesniffer)
		- [2. Install the CodeIgniter4-Standard](#2-install-the-codeigniter4-standard)
		- [3. Install PHP Sniffer extension in VS Code](#3-install-php-sniffer-extension-in-vs-code)
		- [4. Install Recommended Extensions](#4-install-recommended-extensions)
			- [Rationale for recommended extensions](#rationale-for-recommended-extensions)
	- [HTML, CSS, JavaScript, Markdown](#html-css-javascript-markdown)

## PHP

CodeIgniter4 defines a set of coding conventions in its [PHP Coding Style Guide](https://github.com/codeigniter4/CodeIgniter4/blob/develop/contributing/styleguide.rst), which is based on the PHP-FIG [PSR-1: Basic Coding Standard](https://www.php-fig.org/psr/psr-1/) and [PSR-2: Coding Style Guide](https://www.php-fig.org/psr/psr-2/), but with some differences. While there is no requirement to follow these conventions in application code, the CodeIgniter community recommends it.

The main difference between the CodeIgniter4 Coding Style Guide, PSR-1, PSR-2, and many other contemporary style guides is that they recommend using tabs instead of spaces for indentation. This is rather uncommon these days, with spaces being the [far more popular](https://hoffa.medium.com/400-000-github-repositories-1-billion-files-14-terabytes-of-code-spaces-or-tabs-7cfe0b5dd7fd#.fswbmzt2l) choice.

To remove the burden of choice, we can lint and format our PHP code against the CodeIgniter standard (including HTML within `<?php` tags) using the [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) library.

PHP_CodeSniffer analyses PHP files and detects violations of defined **coding standards** and can, in some cases, fix them.

CodeIgniter4 provides its style guide as a coding standard for use with PHP_CodeSniffer in the [CodeIgniter4-Standard](https://github.com/codeigniter4/coding-standard) repo.

To get automatic linting and code formatting working in VS Code, several installation and set-up steps must be carried out. Unfortunately, it is a bit of a pain to install, but worth the effort to help ensure coding consistency.

*Note: we can script these steps relatively easily as part of the Ansible playbook or a simple bash script*.

### 1. Install PHP_CodeSniffer

Install the library globally within WSL using `composer`:

```bash
composer global require "squizlabs/php_codesniffer=*"
```

This will install the two required executable scripts (`phpcs` and `phpcbf`) into the `~/.config/composer/vendor/bin` directory.

We need to add this `bin` directory to our `PATH` in WSL. Do this by adding the following to your `~/.profile`:

```bash
if [ -d "$HOME/.config/composer/vendor/bin" ] ; then
    PATH="$HOME/.config/composer/vendor/bin:$PATH"
fi
```

Reload the profile by issuing the following command:

```bash
. ~/.profile
```

Finally, check `phpcs` is working by issuing the `phpcs -i` command. You should see something like the following:

```bash
$ phpcs -i
The installed coding standards are PSR2, PEAR, PSR12, MySource, Squiz, Zend and PSR1
```

### 2. Install the CodeIgniter4-Standard

Install the standard globally using `composer`:

```bash
composer global require codeigniter4/codeigniter4-standard
```

Create a symbolic link to the newly installed `CodeIgniter4` standard from the PHP_CodeSniffer `Standards` directory:

```bash
ln -s ~/.config/composer/vendor/codeigniter4/codeigniter4-standard/CodeIgniter4/ \
~/.config/composer/vendor/squizlabs/php_codesniffer/src/Standards/CodeIgniter4
```

Check to see if the new `CodeIgniter4` standard is available to `phpcs`:

```bash
$ phpcs -i
The installed coding standards are PSR2, PEAR, PSR12, MySource, Squiz, CodeIgniter4, Zend and PSR1
```

### 3. Install PHP Sniffer extension in VS Code

VS Code has several PHP_CodeSniffer extensions of varying quality. I have tried a few and **PHP Sniffer** by wongjn appears to offer a good balance of reliability and features.

First, install [PHP Sniffer](https://marketplace.visualstudio.com/items?itemName=wongjn.php-sniffer) into VS Code.

Next, configure the extension to reference the CodeIgniter4 standard we installed. Do this by opening VS Code settings (hit <kbd>Ctrl</kbd>+<kbd>,</kbd>) then search for `phpSniffer.standard`. 

Select the **Remote** tab to apply the setting to the WSL instance rather than globally as a User setting or as a Workspace setting (*note, we can add this setting to Workspace settings once we have scripted installation of PHP_CodeSniffer; until we do that, we don't want to commit the setting to git, therefore we add the setting to Remote rather than Workspace*).

> Tip: hover over the settings tabs to see exactly where a given VS Code settings file is stored.

Set the **PHP Sniffer: Standard** setting to `CodeIgniter4`.

![PHP Sniffer settings](images/php-sniffer-settings.png)

Check the Remote settings JSON file by hitting <kbd>Ctrl</kbd>+<kbd>Shift</kbd>+<kbd>P</kbd> and searching for **Preferences: Open Remote Settings**. It should have the following in there:

```json
{
  "phpSniffer.standard": "CodeIgniter4"
}
```

You can tell whether VS Code is using the CodeIgniter4 standard by opening a PHP file and checking the problems pane. When PHP Sniffer detects problems, it will provide details in this pane along with a reference to which standard the problem is defined in.

![PHP Sniffer problems](images/php-sniffer-problems.png)

### 4. Install Recommended Extensions

VS Code can advise developers of recommended extensions for a loaded workspace by specifying them in the `[workspace]/.vscode/extensions.json` file.

You can inspect and install these recommended extensions by opening the command palette (<kbd>Ctrl</kbd>+<kbd>Shift</kbd>+<kbd>P</kbd>) and searching for **Show Recommended Extensions**. The list will appear in the extensions pane.

![recommended extensions](images/recommended-extensions.png)

#### Rationale for recommended extensions

- **Better Comments**: provides syntax highlighting for comments, including DocBlocks. Makes working with these comments clearer and easier.
- **EditorConfig for VS Code**: overrides user/workspace settings with settings found in the `.editorconfig` file which brings consistency to code. Works in many code editors, not just VS Code.
- **GitLens**: Greatly enhanced Git capabilities in VS Code negating (to a certain extent) the need for a separate GUI and/or CLI Git commands.
- **PHP DocBlocker**: CodeIgniter4 makes extensive use of structured PHP DocBlocks, and the coding standard requires them. This extension helps with writing and structuring them correctly.
- **PHP Intelephense**: High performance PHP code intellisense. A great improvement over the older PHP IntelliSense extension.
- **PHP Sniffer**: Implementation of PHP_CodeSniffer in VS Code. This is essential for linting and formatting against CodeIgniter4 Coding Style Guide.
- **Prettier**: The de facto standard code formatter and style guide for JavaScript. It is highly opinionated but extremely popular. In addition to JavaScript it includes a formatter for JSON, CSS, SCSS, HTML, Markdown, and YAML (among others).
- **Rewrap**: Reformats comments, comment blocks, and text (e.g., Markdown) to wrap at a configured column (typically column 80). It can also automatically hard-wrap comment lines at the configured column by setting `"rewrap.autoWrap.enabled": true`. See the [Rewrap website](https://stkb.github.io/Rewrap/#/) for an explanation and demo of features and auto-wrap. It is *extremely* useful for composing and reformatting comment blocks and Markdown.

## HTML, CSS, JavaScript, Markdown

HTML in a CodeIgniter4 application is primarily embedded into PHP files, which are linted and formatted by PHP_CodeSniffer. 

Standalone HTML, CSS, SCSS, JavaScript (including JSON), and Markdown files are formatted by the default Prettier settings (as long as the Prettier extension is installed). 

Prettier respects `.editorconfig` settings and, in this project, these are the only settings that deviate from the Prettier defaults.

[Why Prettier? Great question](https://prettier.io/docs/en/why-prettier.html).
