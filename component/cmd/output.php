<?php
namespace rephp\component\cmd;

/**
 * 控制台格式化输出
 */
class output
{
    /**
     * 加粗标题格式化字符串(黑色白底加粗)
     * @var string
     */
    protected $titleFormat = "\033[38;5;15m%s\033[0m";
    /**
     * 成功提示格式化字符串(绿色白底)
     * @var string
     */
    protected $successFormat = "\033[32m%s\033[0m";
    /**
     * 错误提示格式化字符串(白色红底)
     * @var string
     */
    protected $errorFormat = "\033[41;97m%s\033[0m";
    /**
     * 警告提示格式化字符串(红色白底)
     * @var string
     */
    protected $warningFormat = "\033[31m%s\033[0m";
    /**
     * 通知提示格式化字符串(棕色白底)
     * @var string
     */
    protected $noticeFormat = "\033[33m%s\033[0m";
    /**
     * 普通提示格式化字符串(黑色白底)
     * @var string
     */
    protected $infoFormat = "\033[0m%s\033[0m";
    /**
     * 要输出的内容
     * @var string
     */
    protected $content = '';

    /**
     * 获取实例化自身对象
     * @return static
     */
    public static function find()
    {
        return new static();
    }

    /**
     * 普通消息(黑色白底)
     * @param string  $msg  消息内容
     * @param boolean $isBr 是否换行
     * @return $this
     */
    public function info($msg, $isBr = false)
    {
        $this->content .= sprintf($this->infoFormat, $msg);
        return $isBr ? $this->br() : $this;
    }

    /**
     * 标题消息(黑色白底加粗)
     * @param string  $msg  消息内容
     * @param boolean $isBr 是否换行
     * @return $this
     */
    public function title($msg, $isBr = false)
    {
        $this->content .= sprintf($this->titleFormat, $msg);
        return $isBr ? $this->br() : $this;
    }

    /**
     * 通知消息(棕色白底)
     * @param string  $msg  消息内容
     * @param boolean $isBr 是否换行
     * @return $this
     */
    public function notice($msg, $isBr = false)
    {
        $this->content .= sprintf($this->noticeFormat, $msg);
        return $isBr ? $this->br() : $this;
    }

    /**
     * 警告消息(红色白底)
     * @param string  $msg  消息内容
     * @param boolean $isBr 是否换行
     * @return $this
     */
    public function warning($msg, $isBr = false)
    {
        $this->content .= sprintf($this->warningFormat, $msg);
        return $isBr ? $this->br() : $this;
    }

    /**
     * 成功消息(绿色白底)
     * @param string  $msg  消息内容
     * @param boolean $isBr 是否换行
     * @return $this
     */
    public function success($msg, $isBr = false)
    {
        $this->content .= sprintf($this->successFormat, $msg);
        return $isBr ? $this->br() : $this;
    }

    /**
     * 错误消息(白色红底)
     * @param string  $msg  消息内容
     * @param boolean $isBr 是否换行
     * @return $this
     */
    public function error($msg, $isBr = false)
    {
        $this->content .= sprintf($this->errorFormat, $msg);
        return $isBr ? $this->br() : $this;
    }

    /**
     * 拼接tab空字符
     * @return $this
     */
    public function tab()
    {
        $this->content .= '    ';
        return $this;
    }

    /**
     * 拼接回车换行
     * @return $this
     */
    public function br()
    {
        $this->content .= "\n";
        return $this;
    }

    /**
     * 获取格式化后的数据内容
     * @return string
     */
    public function get()
    {
        return $this->br()->content;
    }

}