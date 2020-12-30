<?php
/**
 * @description 日期 处理
 * @author Holyrisk
 * @date 2020/12/30 9:19
 * Class DateHandle
 */

class DateHandle
{

    /**
     * 一天 秒数
     * @var int
     */
    public $day_second = 86400;

    /**
     * @description 临时 数组 | 转换 返回正确的 开始时间 和 结束时间
     * @author Holyrisk
     * @date 2020/9/25 17:10
     * @param      $start
     * @param      $end
     * @param bool $date 是否是日期格式 true 默认  还是 时间戳格式 false
     * @return array
     */
    public function tempDay($start,$end,$date = true)
    {
        $dayStart = strtotime(date("Y-m-d",strtotime($start)));
        $dayEnd = strtotime(date("Y-m-d",strtotime($end)));
        if($dayStart > $dayEnd)
        {
            $temp = $dayStart;
            $dayStart = $dayEnd;
            $dayEnd = $temp;
        }
        if($date == true){
            return ['start'=> date("Y-m-d",$dayStart),'end'=> date("Y-m-d",$dayEnd)];
        }
        return ['start'=> $dayStart,'end'=> $dayEnd];
    }

    /**
     * @description 根据时间差 和 当前日期 获取对应的差额日期
     * @author Holyrisk
     * @date 2020/10/16 15:20
     * @param int  $diffNumber 相差天数  如今天 2020 12 30 -- 相差 1 天 则为 2020 12 31 --- 相差 -1 天 则为 2020 12 29
     * @param null $date
     * @return false|string
     */
    public function getDiffDate($diffNumber = 0,$date = null)
    {
        if(empty($date)){
            $date = date("Y-m-d",time());
        }
        $dayTime = strtotime(date("Y-m-d",strtotime($date)));
        $result = date("Y-m-d",$dayTime+$this->day_second*$diffNumber);
        return $result;
    }
    
    /**
     * @description 获取两个日期的 差额天数 = 结束时间  - 开始天数
     * @author Holyrisk
     * @date 2020/9/25 17:01
     * @param $start
     * @param $end
     * @return float|int
     */
    public function diffDay($start,$end)
    {
        $dayStart = strtotime(date("Y-m-d",strtotime($start)));
        $dayEnd = strtotime(date("Y-m-d",strtotime($end)));
        $diff = $dayEnd-$dayStart;
        if(empty($diff))
        {
            return 0;
        }
        $result = $diff/$this->day_second;
        return $result;
    }

    /**
     * @description 获取两个时间差额的 中间日期
     * @author Holyrisk
     * @date 2020/9/25 17:19
     * @param $start 开始日期
     * @param $end 结束日期
     * @param bool $orderModel 排序方式 默认时间顺序 false | 倒序 设置为 true
     * @return array
     */
    public function lists($start,$end,$orderModel = false)
    {
        $arr = $this->tempDay($start,$end);
        $start = $arr['start'];
        $end = $arr['end'];
        $diff = $this->diffDay($start,$end);
        if(empty($diff)){
            return [$start];
        }
        $diff ++;//相加 一天
        $list = [];
        for($i = 0;$i < $diff;$i++){
            $list[] = date("Y-m-d",strtotime(date("Y-m-d",strtotime($start)))+$this->day_second*$i);
        }
        if ($orderModel == true){
            $list = array_reverse($list);
        }
        return $list;
    }

    /**
     * @description 根据日期 和 时间格式 返回 时间格式的 日期 或者 时间戳
     * @author Holyrisk
     * @date 2020/10/19 11:00
     * @param        $date 转换的日期
     * @param string $format 时间格式
     * @param bool   $isDate 是否是日期格式 true 默认 或者 时间戳格式 false
     * @return false|int|string
     */
    public function trunDateFormat($date,$format = "Y-m-d",$isDate = true){
        $data = date($format,strtotime($date));
        if($isDate == false){
            return strtotime($data);
        }
        return $data;
    }

}