<?php
namespace rephp\component\cmd;

/**
 * 控制台格式化输出
 * echo output::find()
 * ->title('这是一个测试', 1, 1)
 * ->success('测试缩进', 0)->tab(5)->info('info类型语句', 1)->info('要换行啦', 1)->br()
 * ->success('success类型语句:create-permission', 1, 0)->warning('warning类型语句', 1,  true)
 * ->br()
 * ->error('error类型语句', 1, 0)->info('info类型语句', 1)
 * ->get();
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
     * 临时行内容
     * @var array
     */
    protected $tr = [];
    /**
     * 临时单元格内容
     * @var string
     */
    protected $td = '';
    /**
     * 每列填充宽度
     * @var int
     */
    protected $padWidthArr = [];

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
     * @param string  $msg       消息内容
     * @param boolean $isCloseTd 是否关闭当前td
     * @param boolean $isBr      是否换行
     * @return $this
     */
    public function info($msg, $isCloseTd = false, $isBr = false)
    {
        $this->td .= sprintf($this->infoFormat, $msg);
        return $this->closeTd($isCloseTd)->br($isBr, PHP_EOL);
    }

    /**
     * 标题消息(黑色白底加粗)
     * @param string  $msg       消息内容
     * @param boolean $isCloseTd 是否关闭当前td
     * @param boolean $isBr      是否换行
     * @return $this
     */
    public function title($msg, $isCloseTd = false, $isBr = false)
    {
        $this->td .= sprintf($this->titleFormat, $msg);
        return $this->closeTd($isCloseTd)->br($isBr, PHP_EOL);
    }

    /**
     * 通知消息(棕色白底)
     * @param string  $msg       消息内容
     * @param boolean $isCloseTd 是否关闭当前td
     * @param boolean $isBr      是否换行
     * @return $this
     */
    public function notice($msg, $isCloseTd = false, $isBr = false)
    {
        $this->td .= sprintf($this->noticeFormat, $msg);
        return $this->closeTd($isCloseTd)->br($isBr, PHP_EOL);
    }

    /**
     * 警告消息(红色白底)
     * @param string  $msg       消息内容
     * @param boolean $isCloseTd 是否关闭当前td
     * @param boolean $isBr      是否换行
     * @return $this
     */
    public function warning($msg, $isCloseTd = false, $isBr = false)
    {
        $this->td .= sprintf($this->warningFormat, $msg);
        return $this->closeTd($isCloseTd)->br($isBr, PHP_EOL);
    }

    /**
     * 成功消息(绿色白底)
     * @param string  $msg       消息内容
     * @param boolean $isCloseTd 是否关闭当前td
     * @param boolean $isBr      是否换行
     * @return $this
     */
    public function success($msg, $isCloseTd = false, $isBr = false)
    {
        $this->td .= sprintf($this->successFormat, $msg);
        return $this->closeTd($isCloseTd)->br($isBr, PHP_EOL);
    }

    /**
     * 错误消息(白色红底)
     * @param string  $msg       消息内容
     * @param boolean $isCloseTd 是否关闭当前td
     * @param boolean $isBr      是否换行
     * @return $this
     */
    public function error($msg, $isCloseTd = false, $isBr = false)
    {
        $this->td .= sprintf($this->errorFormat, $msg);
        return $this->closeTd($isCloseTd)->br($isBr, PHP_EOL);
    }

    /**
     * 更新tr最后一个td的填充字段长度
     * @return string
     */
    protected function updatePadWidth()
    {
        if(empty($this->tr)){
            return $this;
        }
        foreach($this->tr as $index=>$td){
            $td = preg_replace("/\033\[[^m]*m/", '', $td);
            $length = strlen(iconv('UTF-8', 'GB2312', $td));
            if(isset($this->padWidthArr[$index])){
                ($this->padWidthArr[$index] < $length) && $this->padWidthArr[$index] = $length;
            }else{
                $this->padWidthArr[$index] = $length;
            }
        }

        return $this;
    }



    /**
     * 拼接tab空字符
     * @return $this
     */
    public function tab($num = 4, $isCloseTd = false, $isBr = false)
    {
        $this->td .= str_repeat(' ', $num);
        return $this->closeTd($isCloseTd)->br($isBr, PHP_EOL);
    }

    /**
     * 关闭td
     * @param bool $isCloseTd 是否关闭
     * @return $this
     */
    public function closeTd($isCloseTd = false)
    {
        if (!$isCloseTd) {
            return $this;
        }
        $this->tr[] = $this->td;
        $this->td   = '';
        //更新列宽
        $this->updatePadWidth();

        return $this;

    }

    /**
     * 拼接回车换行
     * @param bool $isBr 是否换行（结束行信息录入）
     * @return $this
     */
    public function br($isBr = true, $addonStr=PHP_EOL)
    {
        if (!$isBr) {
            return $this;
        }
        $this->tr[]      = $this->td;
        $this->td        = '';
        $this->content[] = $this->tr;
        $this->tr        = [];
        //更新列宽
        $this->updatePadWidth();

        return $this;
    }

    /**
     * 计算要填充的空内容
     * @param string $tempTd  单元格内容
     * @param $length 预期单元格最大宽度
     * @return string
     */
    public function getRepeat($tempTd, $length)
    {
        $strLength =  strlen($tempTd);
        if($strLength>=$length){
            return '';
        }

        return str_repeat(' ', ($length-$strLength));
    }

    /**
     * 获取格式化后的数据内容
     * @return string
     */
    public function get()
    {
        if(!empty($this->td) || !empty($this->tr)){
            $this->br(true, '');
        }
        $content = '';
        foreach ($this->content as $tr) {
            foreach($tr as $index=>$td){
                $length   = isset($this->padWidthArr[$index]) ? $this->padWidthArr[$index] : 20;
                $tempTd   = iconv('UTF-8', 'GB2312', preg_replace("/\033\[[^m]*m/", '', $td));
                $content .= $td.$this->getRepeat($tempTd, $length).'    ';
            }
            $content .= PHP_EOL;
        }

        return $content;
    }

}