## Profiles: A Research Profile System

A simple CRUD/search profile system for research profiles, providing user-editable information and basic pages to aesthetically promote and highlight researcher activities and achievements.

![Example Screenshot](/public/img/screenshot.png "Example Screenshot")

![Example Index Screenshot](/public/img/screenshot-index.png "Example Index Screenshot")

![Example Profile Screenshot](/public/img/screenshot-banner.png "Example Profile Screenshot")

![Example Information Screenshot](/public/img/screenshot-info.png "Example Information Screenshot")


#### Basic Functionality to Include

* User ability to create, read, update, and delete profiles
* General and tags search on profile data
* Delegation to other users to maintain profile information
* As much automation as possible in updating
* Student engagement
	* Learn about researchers/labs based on interest
	* Connect and facilitate placement in labs and research opportunities

#### Future Goals

* Provide FAR (Faculty Annual Report) functionality by consolidating all researcher activity
* Additional imports, feeds, etc.

#### Minimum Requirements

* PHP 7.4
* MySQL 5.7
* Apache 2.4 / Nginx
* [Composer](https://getcomposer.org/)

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

#### License
This project is licensed under the terms of the MIT license.