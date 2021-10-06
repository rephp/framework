<?php
namespace rephp\component\cmd\parse;

/**
 * 解析类内的公共方法名字及描述
 */
class methodParser
{
    /**
     * 解析类内的公共方法名字及描述
     * @param string $classFullName
     * @return array
     * @throws ReflectionException
     */
    public static function parse($classFullName)
    {
        $reflection = new \ReflectionClass($classFullName);
        $methods    = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
        $result     = [];
        foreach ($methods as $method) {
            $doc = $method->getDocComment();
            preg_match('/\*\*(.+)\*/s', $doc, $comment);
            $tempArr = explode('@', trim($comment [1]));
            preg_match('/\*(.+)\*/s', $tempArr[0], $comment);
            $result[] = [
                'method'  => $classFullName . '@' . $method->getName(),
                'comment' => trim($comment[1]),
            ];
        }

        return $result;
    }

}
