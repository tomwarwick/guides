<?php

declare(strict_types=1);

namespace phpDocumentor\Guides\Cli;

use phpDocumentor\Guides\Cli\DependencyInjection\ApplicationExtension;
use phpDocumentor\Guides\DependencyInjection\GuidesExtension;
use phpDocumentor\Guides\DependencyInjection\ReStructuredTextExtension;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\Extension\Extension;

final class Application extends BaseApplication
{
    /** @param iterable<Command> $commands */
    public function __construct(iterable $commands, string $defaultCommand = 'run')
    {
        parent::__construct('phpDocumentor guides', '1.0.0');

        foreach ($commands as $command) {
            $this->add($command);
        }

        $this->setDefaultCommand($defaultCommand, true);
    }

    /** @return Extension[] */
    public static function getDefaultExtensions(): array
    {
        return [
            new GuidesExtension(),
            new ReStructuredTextExtension(),
            new ApplicationExtension(),
        ];
    }
}
