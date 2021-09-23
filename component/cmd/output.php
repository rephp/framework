<?php
namespace rephp\component\cmd;

/**
 * 控制台格式化输出
 * echo output::find()
 *         ->title('这是一个测试', 1)
 *         ->success('测试缩进', 0, 1)->info('info类型语句')->tab(1)->br()
 *         ->success('success类型语句:create-permission', 0, 1)->warning('warning类型语句', 1)
 *         ->error('error类型语句', 1)
 *         ->get();
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
    protected $content = [];
    /**
     * 每列填充宽度
     * @var int
     */
    protected $padWidth = 0;

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
     * @param string  $msg        消息内容
     * @param boolean $isBr       是否换行
     * @param boolean $isPadWidth 是否填充宽度
     * @return $this
     */
    public function info($msg, $isBr = false, $isPadWidth = false)
    {
        $isPadWidth && $this->updatePadWidth($msg);
        $this->content[] = ['msg'=>sprintf($this->infoFormat, $msg), 'is_pad'=>$isPadWidth];
        return $isBr ? $this->br() : $this;
    }

    /**
     * 标题消息(黑色白底加粗)
     * @param string  $msg        消息内容
     * @param boolean $isBr       是否换行
     * @param boolean $isPadWidth 是否填充宽度
     * @return $this
     */
    public function title($msg, $isBr = false, $isPadWidth = false)
    {
        $isPadWidth && $this->updatePadWidth($msg);
        $this->content[] = ['msg'=>sprintf($this->titleFormat, $msg), 'is_pad'=>$isPadWidth];
        return $isBr ? $this->br() : $this;
    }

    /**
     * 通知消息(棕色白底)
     * @param string  $msg        消息内容
     * @param boolean $isBr       是否换行
     * @param boolean $isPadWidth 是否填充宽度
     * @return $this
     */
    public function notice($msg, $isBr = false, $isPadWidth = false)
    {
        $isPadWidth && $this->updatePadWidth($msg);
        $this->content[] = ['msg'=>sprintf($this->noticeFormat, $msg), 'is_pad'=>$isPadWidth];
        return $isBr ? $this->br() : $this;
    }

    /**
     * 警告消息(红色白底)
     * @param string  $msg        消息内容
     * @param boolean $isBr       是否换行
     * @param boolean $isPadWidth 是否填充宽度
     * @return $this
     */
    public function warning($msg, $isBr = false, $isPadWidth = false)
    {
        $isPadWidth && $this->updatePadWidth($msg);
        $this->content[] = ['msg'=>sprintf($this->warningFormat, $msg), 'is_pad'=>$isPadWidth];
        return $isBr ? $this->br() : $this;
    }

    /**
     * 成功消息(绿色白底)
     * @param string  $msg        消息内容
     * @param boolean $isBr       是否换行
     * @param boolean $isPadWidth 是否填充宽度
     * @return $this
     */
    public function success($msg, $isBr = false, $isPadWidth = false)
    {
        $isPadWidth && $this->updatePadWidth($msg);
        $this->content[] = ['msg'=>sprintf($this->successFormat, $msg), 'is_pad'=>$isPadWidth];
        return $isBr ? $this->br() : $this;
    }

    /**
     * 错误消息(白色红底)
     * @param string  $msg        消息内容
     * @param boolean $isBr       是否换行
     * @param boolean $isPadWidth 是否填充宽度
     * @return $this
     */
    public function error($msg, $isBr = false, $isPadWidth = false)
    {
        $isPadWidth && $this->updatePadWidth($msg);
        $this->content[] = ['msg'=>sprintf($this->errorFormat, $msg), 'is_pad'=>$isPadWidth];
        return $isBr ? $this->br() : $this;
    }

    /**
     * 更新填充字段长度
     * @param string $msg 消息内容
     * @return string
     */
    protected function updatePadWidth($msg)
    {
        $length = mb_strlen($msg) + 20;
        ($this->padWidth < $length) && $this->padWidth = $length;
        return $this;
    }

    /**
     * 拼接tab空字符
     * @return $this
     */
    public function tab($num=4)
    {
        $this->content[] = ['msg'=>str_repeat(' ', $num), 'is_pad'=>false];
        return $this;
    }

    /**
     * 拼接回车换行
     * @return $this
     */
    public function br()
    {
        $this->content[] = ['msg'=>"\n", 'is_pad'=>false];
        return $this;
    }

    /**
     * 获取格式化后的数据内容
     * @return string
     */
    public function get()
    {
        $content = '';
        foreach($this->content as $item){
            $content .= empty($item['is_pad']) ? $item['msg'] : str_pad($item['msg'], $this->padWidth, $pad = ' ', STR_PAD_RIGHT);
        }

        return $content;
    }

}