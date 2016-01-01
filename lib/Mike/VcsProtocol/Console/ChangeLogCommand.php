<?php

namespace Mike\VcsProtocol\Console;


use Mike\VcsProtocol\ABNF\CommitMessage;
use Mike\VcsProtocol\Vcs\GitWrapper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Print change log based on commit messages.
 *
 * @package Mike\VcsProtocol\Console
 */
class ChangeLogCommand extends Command
{
    protected function configure()
    {
        $this->addArgument(
            'start-hash',
            InputArgument::OPTIONAL,
            'Start reading commits at this version',
            null
        );

        $this->addArgument(
            'end-hash',
            InputArgument::OPTIONAL,
            'Stop reading commits at this version',
            'HEAD'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $wrapper = new GitWrapper();

        $first = $input->getArgument('start-hash');
        $last  = $input->getArgument('end-hash');

        if (null == $first) {
            $first = $wrapper->getFirstCommit();
        }

        $commitMessages = $wrapper->getCommitMessages($first, $last);

        foreach ($commitMessages as $message) {
            $message = new CommitMessage($message);
            $output->writeln($message->getTitle());
        }

    }


}