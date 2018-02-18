<h1 align="center">slimo</h1>
<p align="center">A skelton for your slim app</p>

## Installation
1. install with [composer](https://getcomposer.org/). Your PHP package manager.
```
composer create-project the94air/slimo [Project Name]
```
2. And for using webpack (laravel-mix) just run
```
npm install
```
3. Have fun!

## Learn how to use
You can browse these links to find out more about each part of this skeleton
1. [Slim framework](https://www.slimframework.com/docs)
2. [Laravel ORM (Eloquent)](https://laravel.com/docs/5.6/eloquent) for communicating with the database.
3. [Dotenv](https://github.com/vlucas/phpdotenv) for loading environment variables (.env).
4. [Twig](https://twig.symfony.com/) for PHP template engine.
5. [Slim-Csrf](https://github.com/slimphp/Slim-Csrf) for CSRF protection.
6. [SlimValidation](https://github.com/awurth/SlimValidation) & [Respect\Validation](https://github.com/Respect/Validation)  for forms validation.
7. [Laravel Mix](https://laravel.com/docs/5.6/mix) for assets compiling with wepack.
8. [swiftmailer](https://swiftmailer.symfony.com/docs/introduction.html) for sending emails.

## How to send mail
For that you will be able to add your configurations to the `.env` file.
```
# Using SMTP
MAIL_DRIVER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=example@gmail.com
MAIL_PASSWORD=secret
MAIL_ENCRYPTION=tls

# Or using Sendmail server
MAIL_DRIVER=sendmail
MAIL_SENDMAIL_PATH='/usr/sbin/sendmail -bs'
```
And after that you will be able to access the `$mailer` from your Routers and Controllers.
```php
class MailController extends Controller
{
    public function index(Request $request, Response $response, $args)
    {
        $mailer = $this->mailer;

        // Create a message
        $message = (new \Swift_Message('Wonderful Subject'))
            ->setFrom(['john@doe.com' => 'John Doe'])
            ->setTo(['receiver@domain.org', 'other@domain.org' => 'A name'])
            ->setBody('Here is the message itself');

        // Send the message
        $result = $mailer->send($message);

        // returns `int(2)` on success
        var_dump($result);
    }
}
```

## Attention
This package is forked from [slender](https://github.com/codecourse/slender) package thanks to [Alex Garrett](https://twitter.com/alexjgarrett) with more additional and useful packages. Feel free to add your own feature from the open source universe (Make a [pull request](https://github.com/the94air/slimo/pull/new/master)).

This software does not have a license. All used packages has it's own license.
And BTW... Laravel is a trademark of Taylor Otwell.
