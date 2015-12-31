<?php

namespace Mike\GitProtocol\Vcs;


class GitWrapper
{
    protected $baseDir;

    public function __construct($baseDir = null)
    {
        if (null == $baseDir) {
            $baseDir = system('git rev-parse --show-toplevel');
        }

        $this->baseDir = $baseDir;
    }

    /**
     * @return null|string
     */
    public function getBaseDir()
    {
        return $this->baseDir;
    }

    public function getCommitMessages($first, $last)
    {
        $commitMessages = [];

        if ($first == $last) {
            return $commitMessages;
        }

        $commitMessages[$this->getHash($first)] = $this->getCommitMessage(
            $first
        );

        $command = "git rev-list ".$first.'..'.$last.'^';
        exec($command, $between);

        foreach ($between as $current) {
            if ( ! $current) {
                continue;
            }
            $current = $this->getHash($current);

            if ( ! $current) {
                continue;
            }

            $commitMessages[$current] = $this->getCommitMessage($current);
        }


        $commitMessages[$this->getHash($last)] = $this->getCommitMessage($last);

        return $commitMessages;
    }

    public function getHash($first)
    {
        $output = [];

        exec('git rev-list -1 '.escapeshellarg($first), $output);

        return current($output);
    }

    public function getCommitMessage($hash)
    {
        $output = [];
        exec('git log -1 --pretty=%B '.escapeshellarg($hash), $output);

        return trim(implode(PHP_EOL, $output));
    }
}