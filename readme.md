## Profiles: A Research Profile System

A web application for research profiles, providing user-editable information and web pages to aesthetically promote and highlight researcher activities and achievements.

![Example Screenshot](/public/img/screenshot.png "Example Screenshot")

![Example Index Screenshot](/public/img/screenshot-index.png "Example Index Screenshot")

![Example Profile Screenshot](/public/img/screenshot-banner.png "Example Profile Screenshot")

![Example Information Screenshot](/public/img/screenshot-info.png "Example Information Screenshot")


#### Basic Functionality

* User ability to create, read, update, and delete profiles
* General and tags search on profile data
* Delegation to other users to maintain profile information

#### Optional Features

* Student engagement
    * Learn about researchers/labs based on interest
    * Connect and facilitate placement in labs and research opportunities
* Export profiles to PDF

#### Minimum Requirements

* PHP 8.0
* MySQL 5.7
* Apache 2.4 / Nginx
* [Composer](https://getcomposer.org/)

#### Optional Feature Requirements

* Memory caching: Redis / PHP-redis extension
* PDF exports: Node 16 / NPM 8 / Chromium

#### Installation

```
git clone git@github.com:utdallasresearch/profiles.git
composer install --ignore-platform-reqs
cp .env.example .env
```

Edit the .env file for your environment.

```
php artisan key:generate
php artisan storage:link
php artisan migrate --seed
```

First user to login will have the administrator role, which can then be granted to other users.

#### Feature Installation

* Enable profile PDF exports (on a server)
    * Install Node & NPM
    * Install Puppeteer: `sudo npm install puppeteer --location=global`
    * Install Chromium CLI:
        * install it with a package manager, OR
        * install it with Puppeteer, then copy it to an accessible location (replace the paths below with your global node_modules path):
            * `node /usr/lib/node_modules/puppeteer/install.js`
            * `sudo cp -R ~/.cache/puppeteer/chrome /usr/lib/node_modules/puppeteer/.local-chromium`
            * `sudo chmod -R go+rx /usr/lib/node_modules/puppeteer/.local-chromium`
    * Edit the .env file to set `ENABLE_PDF=true`, uncomment and edit the paths to your Node, NPM, node_modules, and chromium CLI path

#### License
This project is licensed under the terms of the MIT license.