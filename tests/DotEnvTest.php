<?php


namespace Clarence\DotEnv;



class DotEnvTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     * @dataProvider env_data_provider
     */
    public function env_should_match($name, $value)
    {
        // 环境变量不支持数字类型... 会默认转换为字符串
        if (is_int($value) or is_float($value) or is_double($value)) {
            $this->assertNotSame($value, env($name));
            $this->assertEquals($value, env($name));
        } else {
            $this->assertSame($value, env($name));
        }

        $this->assertSame($value, $_ENV[$name]);
        $this->assertSame($value, $_SERVER[$name]);

    }

    public function env_data_provider()
    {
        (new \Clarence\DotEnv\DotEnv(__DIR__))->load();

        $varList = require(__DIR__ . DIRECTORY_SEPARATOR . '.env.php');
        foreach ($varList as $key => &$value) {
            $value = [$key, $value];
        }

        return $varList;
    }
}