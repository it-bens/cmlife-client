# cmlife API Client

![Maintenance Status](https://img.shields.io/badge/Maintained%3F-yes-green.svg)
[![PHPUnit](https://github.com/it-bens/cmlife-client/actions/workflows/phpunit.yml/badge.svg?branch=master)](https://github.com/it-bens/cmlife-client/actions/workflows/phpunit.yml)
[![Test Coverage](https://codecov.io/gh/it-bens/cmlife-client/branch/master/graph/badge.svg?token=66IPB6T9WQ)](https://codecov.io/gh/it-bens/cmlife-client)
[![PHPUnit](https://github.com/it-bens/cmlife-client/actions/workflows/static-analysis.yml/badge.svg?branch=master)](https://github.com/it-bens/cmlife-client/actions/workflows/static-analysis.yml)
[![Type coverage](https://shepherd.dev/github/{username}/{repo}/coverage.svg)]

## What is this about?

![cmlife client logo](/docs/images/cmlife-client-logo.png)

cmlife is a software, extending the CAMPUSOnline platform for campus management. It provides API(s) for access (read and/or write) to the data collected and processed by CAMPUSOnline. The cmlife software and API are developed by [indibit](https://indibit.eu/). 
The frontend provided by indibit utilizes the cmlife API to (partly) replace the user experience of CAMPUSOnline.

However, cmlife does not provide all the functions required or requested by students and staff. Therefor this client acts as a software development kit (SDK) to access the data from the cmlife API.

## Installation

The package can be installed via Composer:
```bash
composer require it-bens/cmlife-client
```

It requires at least PHP 8.1. Because requests to the API are done asynchronously, s PSR-7 implementation is required. Currently, Nyholms is used. Besides this package require doctrine to manage the data returned from the API.

## How to use the client?

First you should be aware, that all actions done by this client (read and write) requires authentication. This package provides an `UsernamePasswordAuthenticator` which uses username and password.
The client only requires an implementation of the `AuthenticatorInterface`. 

As an alternative a `CookieValueAuthenticator` is provided by this package. It requires the session id and the xsrf token cookie values used by a browser to authenticate at cmlife. The value can be extracted from a browser like Firefox, Chrome or Safari. 

### Client creation

The client requires implementations of the `DataClientInterface` and the `DataStorageInterface`.
```php
# via constructor
$cmlifeClient = new \ITB\CmlifeClient\CmlifeClient($someDataClient, $someDataStorage);
```

After creation, no other method than `fetchDataFromCmlife` can be used. Only after that method was executed, the data from cmlife can be used.

### Client initialization

To work properly, the client requires some data like from the cmlife API like the user and the semester. To prevent issues with incomplete initialization the client provides a single function to fetch all required data from cmlife.
```php
/** @var \ITB\CmlifeClient\CmlifeClientInterface $cmlifeClient */
$cmlifeClient->fetchDataFromCmlife();
```

The execution of this method can take several seconds. The fetched data are: current semester, previous semester, next semester, user/person, all courses of the fetched semesters, the personal studies and their curriculums.
Because this will put load on the API, a cached implementation is planned.

### Client usage

Like shown by the `CmlifeClientInterface` the client provides access to the current user/person, the current semester, courses (of the semesters like mentioned) and the personal studies (one or more).
The studies also contain the complete curriculum of the study. 

## What's a curriculum?

The curriculum is the structured representation of a study. It contains information about modules, courses, credit points, rules, offers and more. The general curriculum structure was created by CAMPUSOnline.
It's an acyclic tree structure of nodes with different types. They can differ very much from study to study (e.g. social science and a lot more flexible than Jura or life science). That's why the node types of this client maybe incomplete.

Most of the tree leafs (or maybe all) are link nodes. They "point" to a course. One would think that each course exists in the campus management only once. 
But actually a course is more like an instance of a type representing what we would normally call a "course". It's the same with studies: two students, registered for the same "study" (like computer science), have two different studies in the system.
That's why a study cannot simply "point" to a course like "machine learning". A link node points to a "course equivalence uri" and every course in the system is registered with a course equivalence uri. 
Several courses (in one semester or different ones) can share the same equivalence uri.

Luckily, the client provides both: the curriculum and the courses of one or more semesters. The combined data can be used to get a list of courses that are "linked" to a study and take place in a specified semester.
```php
$study = $cmlifeClient->getMyStudies()[0]; # getMyStudies returns an array of Study objects
$semester = $cmlifeClient->getCurrentSemester();

$courses = $cmlifeClient->getCoursesForStudyAndSemester($study, $semester);
```

Because the data is stored via doctrine with relations, more complex queries are possible and will be implemented.

## What's next?

I will test the client with more data to find incomplete implementations. Currently, this client can only read data from cmlife. It is planned to provide write functionality like assigning to lectures.

## Legal

This code is in no way affiliated with, authorized, maintained, sponsored or endorsed by indibit or any of its affiliates or subsidiaries. This is an independent and unofficial API/SDK. Use at your own risk.

## Contributing
I am really happy that the software developer community loves Open Source, like I do! ♥

That's why I appreciate every issue that is opened (preferably constructive)
and every pull request that provides other or even better code to this package.

You are all breathtaking!