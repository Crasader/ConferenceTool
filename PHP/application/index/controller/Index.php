<?php
namespace app\index\controller;



class Index
{
    public function index()
    {
//        return '你好呀';
        $order_array=array(
            5,4,3,6,7,1,2,10,8,9
        );

        $new_order_arr=$this->bubble_order($order_array);
        dump($new_order_arr);
    }


    function bubble_order($arr){
        //得到长度
        $count_num=count($arr);

        for($k=1;$k<$count_num;$k++){

            //对长度越来越少的一组数据 找出最大让其浮到最后

            for($i=0;$i<$count_num-$k;$i++){

                if($arr[$i]>$arr[$i+1]){//相邻比较
                    $tem=$arr[$i];
                    $arr[$i]=$arr[$i+1];
                    $arr[$i+1]=$tem;
                }

            }
        }
        return $arr;
    }

}
