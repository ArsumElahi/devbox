<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Controller\DumpfileController;

class DumpLogFileCommand extends Command
{

    private $dumpfileController;

    public function __construct(DumpfileController $dumpfileController)
    {
        $this->dumpfileController = $dumpfileController;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('log:dump-log-file')
            ->setDescription('Parse text to file')
            ->setHelp('split file and dump to db');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        
        try {
            $file_path = getcwd() . '/public/inputfile.txt';
            $file_array = file($file_path);

            $line_no = $this->dumpfileController->checkFileStatus($file_path);

            $start_line_no = ($line_no != null) ? $line_no : 0;

            for ($i = $start_line_no; $i < count($file_array); $i++) {
                $output->writeln($this->dumpfileController->createLog(
                    [
                        "services_name" => $this->getServiceName($file_array[$i]),
                        "timestamp" => $this->getDateTimeStamp($file_array[$i]),
                        "route_details" => $this->getRouteDetail($file_array[$i]),
                        "response_code" => (int)$this->getResponseCode($file_array)
                    ]
                ));
            }

            if($line_no)
                $this->dumpfileController->purgeError($file_path);

            dump("SUCCESS");
            return self::SUCCESS;
        } catch (\Exception $exception) {

            $this->dumpfileController->createORUpdateErrorLog($file_path, count($file_array), $i);
            dump("Error Found, please try again! " . ' - ' . $exception->getMessage() . ' - LINE ' . $exception->getLine() . ' - ' . $exception->getFile() . ' - ' . ($i ?? null));
            return self::FAILURE;

        }
    }

    protected function getDateTimeStamp($str)
    {
        preg_match_all("/\[(.*?)\]/", $str, $matches);
        return str_replace(",", "", $matches[1][0] . ", ");
    }

    protected function getServiceName($str)
    {
        return str_replace(",", "", strtok($str, " ") . ", ");
    }

    protected function getRouteDetail($str)
    {
        if (preg_match('/"([^"]+)"/', $str, $m)) {
            return $m[1];
        }
    }

    protected function getResponseCode($str)
    {
        return $str[count($str) - 1];
    }
}