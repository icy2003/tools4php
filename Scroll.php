<?php

/**
 * 滚屏输出文字
 *
 * @filename Scroll.php
 * @encoding UTF-8
 * @author forsona <2317216477@qq.com>
 * @link https://github.com/forsona
 * @datetime 2016-9-18 13:40:01
 * @php 5.3+
 * @version $Id$
 */
class Scroll
{

    public $config = array();

    public function __construct($config = array())
    {
        $this->config = $config + $this->defaultConfig();
        if (true === $this->config['clean']) {
            ob_end_clean(); //把之前的输出清除
        }
        header("Content-type: text/html; charset=utf-8");
        $this->flushMessage(array($this->config['message']));
        sleep($this->config['sleep']);
    }

    private function defaultConfig()
    {
        return array(
            'clean' => false,
            'message' => '正在初始化……',
            'x' => 0,
            'y' => 999999,
            'sleep' => 1,
            'interval' => 0,
        );
    }

    public function flushMessage($data)
    {
        if (empty($data)) {
            return;
        }
        $x = $this->config['x'];
        $y = $this->config['y'];

        foreach ($data as $row) {
            //部分浏览器要达到一定字节才会显示内容，输出一段无用字符确保浏览器实时显示
            echo str_pad(" ", 256);
            echo $row, "<br/><script>scrollBy( {$x}, {$y} )</script>";
            if (ob_get_length()) {
                ob_flush();
                flush();
            }
            sleep($this->config['interval']);
        }
    }

}
