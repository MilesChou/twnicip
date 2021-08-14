<?php

namespace App\Commands;

use DateTime;
use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

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
 * NOTE: THIS SOURCE CODE IS GENERATED VIA "App\Commands\Twnic" COMMAND
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
    private $templateLine = "        ['%s', '%s', '%s', '%s', '%s'],";

    protected function configure()
    {
        $this->setName('update')
            ->setDescription('Update IP database');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = new Client();
        $response = $client->get('https://www.twnic.tw/download/IP/main_f3.htm');

        $crawler = new Crawler((string)$response->getBody());

        $title = $crawler->filter(
            'body > table > tr:nth-of-type(4) > td:nth-of-type(2) > table > tr > td:nth-of-type(1)'
        );

        $ips = $crawler->filter(
            'body > table > tr:nth-of-type(4) > td:nth-of-type(2) > table > tr > td:nth-of-type(3)'
        );

        $data = [];

        foreach ($title as $key => $node) {
            // Header
            if (0 === $key) {
                continue;
            }

            // Title 有的會有換行或奇怪的空白字元，全部移除，IP 欄也做一樣的事
            $title = str_replace(["\r", "\n", " "], '', trim($node->textContent));
            $range = str_replace(["\r", "\n", " "], '', $ips->getNode($key)->textContent);

            // IP 的連字號有的用不一樣的符號，把它 replace 成一樣的
            $range = str_replace('–', '-', $range);

            [$start, $end] = explode('-', $range);

            $start = trim($start);
            $end = trim($end);

            $startLong = ip2long($start);
            $endLong = ip2long($end);

            $data[] = [$startLong, $endLong, $start, $end, $title];

        }

        $this->generateCode($data);

        return 0;
    }

    private function generateCode(array $data): void
    {
        $templateCode = '';

        foreach ($data as $item) {
            $code = sprintf(
                $this->templateLine,
                $item[0],
                $item[1],
                $item[2],
                $item[3],
                $item[4]
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
}
