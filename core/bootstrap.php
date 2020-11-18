<?php

use ApiTester\Core\Tester;
use Inhere\Console\Application;
use Inhere\Console\IO\Input;
use Inhere\Console\IO\Output;

$meta = [
    'name' => 'Api Test Tool',
    'version' => '0.0.1',
];
$input = new Input;
$output = new Output;

$app = new Application($meta, $input, $output);

$app->command('run', function (Input $in, Output $out) {
    $commands = $in->getArguments();
    $test = new Tester();
    if (count($commands) === 0) {
        $test->run();
    } else {
        foreach ($commands as $command) {
            $test->runCase($command);
        }
    }
    $out->success('Valid Success');
});

$app->command('create', function (Input $in, Output $out) {
    $class = $in->getArguments();
    if(count($class) === 0) {
        $out->error("Create Failed: unknown class name");
        return false;
    }
    $class = ucfirst($class[0]);
    $file = BASE_PATH . '/cases/' . $class . '.php';
    if(file_exists($file)){
        $out->error("Create Failed: Class {$class} is already existed");
        return false;
    }

    $context = <<<EOF
<?php

declare(strict_types=1);

namespace ApiTester\Cases;

class {$class} extends HttpTestCase
{
    const name = '{$class}';
    
    public function testDemo()
    {
        \$this->assertTrue(true);
    }
    
}
EOF;

    $handle = fopen($file, 'w');
    fwrite($handle, $context);
    fclose($handle);
    $out->success("Create {$class} Success");
    return true;
});

// run
$app->run();