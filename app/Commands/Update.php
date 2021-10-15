<?php

namespace App\Commands;

use DateTime;
use MilesChou\TwnicIp\TwnicIp;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @see https://www.twnic.tw/download/IP/main_f3.htm
 */
class Update extends Command
{
    /**
     * @var string
     */
    private $template = <<< 'EOF'
<?php

namespace MilesChou\TwnicIp;

/**
 * NOTE: THIS SOURCE CODE IS GENERATED VIA "App\Commands\Update" COMMAND
 *
 * PLEASE DO NOT EDIT IT DIRECTLY.
 */
class Database
{
    public const UPDATED_AT = '%s';

    /**
     * @array
     */
    private static $raw = [
%s
    ];

    public static function all(): array
    {
        return static::$raw;
    }
}

EOF;

    /**
     * @var string
     */
    private $templateLine = "        [%s, %s],";

    protected function configure()
    {
        $this->setName('update')
            ->setDescription('Update IP database')
            ->addArgument('csv', InputArgument::OPTIONAL, 'CSV from IPLOCATION-LITE', 'IP2LOCATION-LITE-DB11.CSV');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $csv = $this->getGenerator($input->getArgument('csv'));

        $data = [];

        foreach ($csv as $key => $item) {
            if ($output->isDebug()) {
                $output->writeln('Key: ' . $key . ' Item: ' . implode(',', $item));
            }

            $stageCheck = ($key + 1) % 100000 === 0;

            if ($stageCheck && $output->isVerbose()) {
                $output->writeln('Finish ' . ($key + 1) . ' row');
            }

            if ('TW' !== $item[2]) {
                continue;
            }

            $startLong = (int)$item[0];
            $endLong = (int)$item[1];

            $arr = TwnicIp::buildRangeByLong($startLong, $endLong);
            $data[] = $arr;

            if ($output->isVeryVerbose()) {
                $output->writeln(
                    'Data: ' . implode(',', $arr) . ' Memory usage: ' . memory_get_usage()
                );
            }
        }

        $this->generateCode($this->refine($data));

        return 0;
    }

    private function getGenerator(string $file): iterable
    {
        if (($handle = fopen($file, 'rb')) === false) {
            throw new RuntimeException('Cannot load file: ' . $file);
        }

        while (($line = fgetcsv($handle)) !== false) {
            yield $line;
        }

        fclose($handle);
    }

    private function generateCode(array $data): void
    {
        $templateCode = '';

        foreach ($data as $item) {
            $code = sprintf(
                $this->templateLine,
                $item[0],
                $item[1]
            );

            $templateCode .= $code . PHP_EOL;
        }

        $newContent = sprintf(
            $this->template,
            (new DateTime())->format('Y-m-d H:i:s'),
            rtrim($templateCode)
        );

        file_put_contents(__DIR__ . '/../../src/Database.php', $newContent);
    }

    /**
     * Combine array item
     *
     * Example:
     * $last  = ['10000', '20000'],
     * $value = ['20001', '30000'],
     * Combine to a new array: ['10000', '30000']
     *
     * @param array $data
     * @return array
     */
    private function refine(array $data): array
    {
        return array_reduce($data, static function (array $carry, array $value) {
            if (empty($carry)) {
                $carry[] = $value;

                return $carry;
            }

            $last = end($carry);

            if ($last[1] + 1 === (int)$value[0]) {
                array_pop($carry);

                $carry[] = [$last[0], $value[1]];
            } else {
                $carry[] = $value;
            }

            return $carry;
        }, []);
    }
}
