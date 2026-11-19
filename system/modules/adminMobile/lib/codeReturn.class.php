<?php

/**
 * 返回状态码
 */
class codeReturn
{
    /**
     * 成功
     * @var int
     */
    const SUCCESS = 200;

    /**
     * 正在揭晓
     * @var int
     */
    const ANNOUN = 220;

    /**
     * 正在正在进行中
     * @var int
     */
    const ONGOING = 221;

    /**
     * 失败
     * @var int
     */
    const ERR_SERVER = 500;

    /**
     * 暂无数据
     * @var int
     */
    const NO_DATA = 400;

    /**
     * 暂无数据
     * @var int
     */
    const NO_LOGIN = 401;

    /**
     * 参数类型有误
     * @var int
     */
    const DATA_TYPE_ERR = 402;

}