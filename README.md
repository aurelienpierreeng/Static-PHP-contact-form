# Static PHP Contact form

__Static PHP Contact form__ provides a self-contained, stand-alone mini-framework allowing to put HTML contact forms on static websites (generated with CMS like [Hugo](https://gohugo.io/), [Jekyll](https://jekyllrb.com/), [Gatsby](https://www.gatsbyjs.com/), etc.). The front-end part (HTML form) will need to be added in a page on the static website, while the back-end part (PHP scripts and libs) will need to be installed on any [LAMP](https://en.wikipedia.org/wiki/LAMP_(software_bundle)) server you control, and is designed for basic shared hostings where limited admin options are offered (no access to `sudo`, `apt install`, Docker containers, etc.).

## Features

- User-agent detection (OS, browser, public IP, local/private IP),
- GeoIP sender detection through [Maxmind](https://www.maxmind.com/en/solutions/ip-geolocation-databases-api-services) (API key needed),
- Email templates (extendable),
- Bots blocking.
- ~~Spam detection~~ (needed ?)


## Why Static PHP Contact form ?

Many commercial companies provide the same kind of back-end service (SMTP sending server and POST endpoint for the HTML form). The great thing is they handle everything for you. The not-so-great thing is emails privacy and costs. The truth is, if you have any kind of (cheap) LAMP-based hosting, you don't need more.

Static PHP Contact form can be boostraped to create and extend your own email templates, by only editing `./src/config.php` and `./src/mail-templates.php` files.


## Prerequisites

- a dedicated sending email address (like `mailer@your-domain.com`) accessible through SMTPS,[^1]
- (optional) have SPF and DKIM configured on your domain, to maximize email deliverability ([how to do it on CPanel hostings](https://docs.cpanel.net/whm/dns-functions/enable-dkim-spf-globally/)),
- PHP version 7 and above,
- the PHP [Phar](https://www.php.net/manual/en/book.phar.php) extension,
- the PHP [cUrl](https://www.php.net/manual/en/book.curl.php) extension,
- A MaxMind GeoIP2 Lite license key (free) ([get yours](https://support.maxmind.com/hc/en-us/articles/4407111582235-Generate-a-License-Key)),


[^1]: Sending emails through SMTP is the only way to have them authentified through [SPF](https://en.wikipedia.org/wiki/Sender_Policy_Framework) and [DKIM](https://en.wikipedia.org/wiki/DomainKeys_Identified_Mail), which prevents lots of spam false-positives. Most mailing systems use the `PHPMail` function, which is entirely unprotected.


## Install

You will need Git installed on your computer.


## Dev & Testing

Start `php -S localhost:8000` from the source directory.
