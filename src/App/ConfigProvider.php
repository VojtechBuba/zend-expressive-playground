<?php

declare(strict_types=1);

namespace App;

/**
 * The configuration provider for the App module
 *
 * @see https://docs.zendframework.com/zend-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     *
     */
    public function __invoke() : array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
			'authentication' => $this->getAuthenticationConfig(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies() : array
    {
        return [
            'invokables' => [
                Handler\PingHandler::class => Handler\PingHandler::class,
            ],
            'factories'  => [
                Handler\HomePageHandler::class => Handler\HomePageHandlerFactory::class,
				\Zend\Expressive\Authentication\UserRepositoryInterface::class => \Zend\Expressive\Authentication\UserRepository\PdoDatabaseFactory::class,
				\Zend\Expressive\Authentication\AuthenticationInterface::class => \Zend\Expressive\Authentication\Basic\BasicAccessFactory::class,
				\Zend\Expressive\Authentication\AuthenticationMiddleware::class => \Zend\Expressive\Authentication\AuthenticationMiddlewareFactory::class,

			],
			'abstract_factories' => [
				\Zend\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory::class,
			]
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates() : array
    {
        return [
            'paths' => [
                'app'    => ['templates/app'],
                'error'  => ['templates/error'],
                'layout' => ['templates/layout'],
            ],
        ];
    }


	public function getAuthenticationConfig() : array
	{
		return [
			'pdo' => [
				'dsn' => 'mysql:host=localhost;dbname=zend-expressive',
				'username' => 'root',
				'password' => 'root',
				'table' => 'users',
				'field' => [
					'identity' => 'email',
					'password' => 'password',
				],
			]
			/* Values will depend on user repository and/or adapter.
			 *
			 * Example: using htpasswd UserRepositoryInterface implementation:
			 *
			 * [
			 *     'htpasswd' => 'insert the path to htpasswd file',
			 *     'pdo' => [
			 *         'dsn' => 'DSN for connection',
			 *         'username' => 'username for database connection, if needed',
			 *         'password' => 'password for database connection, if needed',
			 *         'table' => 'user table name',
			 *         'field' => [
			 *             'identity' => 'identity field name',
			 *             'password' => 'password field name',
			 *         ],
			 *         'sql_get_roles' => 'SQL to retrieve roles by :identity',
			 *     ],
			 * ]
			 */
		];
	}
}
