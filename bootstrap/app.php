<?php

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

try {
    (new \Dotenv\Dotenv(__DIR__ . '/../'))->load();
} catch (\Dotenv\Exception\InvalidPathException $e) {
    //
}

$app = new Slim\App([
    'settings' => [
        'displayErrorDetails' => env('APP_DEBUG') === true,

        'app' => [
            'name' => env('APP_NAME')
        ],

        'views' => [
            'cache' => env('VIEW_CACHE_DISABLED') === true ? false : __DIR__ . '/../storage/views'
        ],

        'db' => [
            'driver' => env('DB_CONNECTION'),
            'host' => env('DB_HOST'),
            'port' => env('DB_PORT'),
            'database' => env('DB_DATABASE'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => env('DB_CHARSET', 'utf8'),
            'collation' => env('DB_COLLATION', 'utf8_general_ci'),
            'prefix' => env('DB_PREFIX', '')
        ],

        'mail' => [
            'driver' => env('MAIL_DRIVER', 'smtp'),
            'host' => env('MAIL_HOST', 'smtp.gmail.com'),
            'port' => env('MAIL_PORT', 465),
            'username' => env('MAIL_USERNAME', null),
            'password' => env('MAIL_PASSWORD', null),
            'encryption' => env('MAIL_ENCRYPTION', null),
            'sendmailPath' => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail'),
        ]
    ],
]);

$container = $app->getContainer();

$capsule = new \Illuminate\Database\Capsule\Manager;

$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function ($container) use ($capsule){
    return $capsule;
};

$app->add(new \Slim\Csrf\Guard);

$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};

$container['validator'] = function () {
    return new \Awurth\SlimValidation\Validator(false, require __DIR__ . '/../config/validation.php');
};

$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../resources/views', [
        'cache' => $container->settings['views']['cache']
    ]);

    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');

    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    $view->addExtension(
        new \Awurth\SlimValidation\ValidatorExtension($container['validator'])
    );

    $view->offsetSet('app_local', env('APP_LOCAL', 'en'));

    return $view;
};

$container['mailer'] = function ($container) {
    if($container->settings['mail']['driver'] == 'smtp')
    {
        $transport = (new \Swift_SmtpTransport($container->settings['mail']['host'], $container->settings['mail']['port'], $container->settings['mail']['encryption']))
                ->setUsername($container->settings['mail']['username'])
                ->setPassword($container->settings['mail']['password']);
    }

    if($container->settings['mail']['driver'] == 'sendmail')
    {
        $transport = new \Swift_SendmailTransport($container->settings['mail']['sendmailPath']);
    }

    $mailer = new \Swift_Mailer($transport);

    return $mailer;
};

require_once __DIR__ . '/../routes/web.php';
