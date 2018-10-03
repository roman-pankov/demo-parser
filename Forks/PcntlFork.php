<?php

namespace App\Forks;

class PcntlFork
{
    /**
     * @var int Количество форков
     */
    private $countForks = 1;

    public function __construct(int $countForks = null)
    {
        if ($countForks !== null && $this->isUseForks()) {
            $this->countForks = $countForks;
        }
    }

    /**
     * Выполнение заданий
     *
     * @param array    $jobs
     * @param \Closure $callback
     */
    public function run(array $jobs, \Closure $callback): void
    {
        $forks = $this->countForks;
        $processNum = 0;
        $pid = 0;
        if ($forks > 1) {
            for ($processNum = 0; $processNum < $forks; $processNum++) {
                $pid = pcntl_fork();
                if ($pid < 0) {
                    echo 'Ошибка создания форка!' . PHP_EOL;
                    die();
                }
                if ($pid === 0) {
                    // Мы в форке, форкаться не нужно
                    break;
                }
            }
        }

        if ($pid && $forks > 1) {
            // В родителе, ждём завершения
            while (pcntl_waitpid(0, $status) !== -1) {
                $status = pcntl_wexitstatus($status);
            }
        } else {
            // Основной код
            $j = 0;
            foreach ($jobs as $job) {
                if (($j % $forks) === $processNum) {
                    $callback($job);
                }
                $j++;
            }
        }
    }

    /**
     * Использовать ли форки
     *
     * @return bool
     */
    public function isUseForks(): bool
    {
        return \function_exists('pcntl_fork');
    }

}