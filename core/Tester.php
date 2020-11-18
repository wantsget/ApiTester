<?php

declare(strict_types=1);

namespace ApiTester\Core;

use Inhere\Console\IO\Output;
use ReflectionClass;
use ReflectionException;

class Tester
{
    /**
     * @var $tasks array
     */
    protected $tasks;

    protected $dir;

    protected $output;

    public function __construct()
    {
        $this->output = new Output();
        $cases = include CONFIG_PATH . 'cases.php';
        foreach ($cases as $case){
            $this->load($case);
        }
    }

    protected function load(string $classname)
    {
        $name = $this->getTaskName($classname);
        $this->tasks[$name] = $classname;
    }

    protected function getTaskName(string $classname){
        // 通过反射注册别名到列表
        try {
            $instance = new ReflectionClass($classname);
            $property = $instance->getConstant('name');
            return $property ? $property : $instance->name;
        } catch (ReflectionException $e) {
            $this->output->error("getTaskName Error: Reflection {$classname} Error");
            exit();
        }
    }

    protected function getTaskMethods(string $classname){
        try {
            $methods = [];
            $instance = new ReflectionClass($classname);
            $results = $instance->getMethods();
            foreach ($results as $result){
                if($result->class === $classname && $result->name !== '__construct') {
                    $methods[] = $result->name;
                }
            }
            return $methods;
        } catch (ReflectionException $e) {
            $this->output->error("getTaskMethods Error: Error in {$classname}");
            exit();
        }
    }

    public function run()
    {
        foreach ($this->tasks as $key => $task) {
            $this->runCase($key);
        }
    }

    public function runCase(string $name = null)
    {
        if(!isset($this->tasks[$name])) {
            $this->output->error("Case {$name} Not Found");
            return false;
        }

        $methods = $this->getTaskMethods($this->tasks[$name]);

        if (count($methods) === 0) {
            return false;
        }

        $task = new $this->tasks[$name]();
        foreach ($methods as $method) {
            $this->output->info("Valid {$name}::{$method}");
            $task->$method();
        }

        return true;
    }
}