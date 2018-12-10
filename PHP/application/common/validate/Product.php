<?php

namespace app\common\validate;

use think\Validate;

class Product extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'product.brand'         => 'require|max:50',
        'product.brand_logo'    => 'require|max:255',
        'product.product_name'  => 'require|max:100',
        'product.conference_id' => 'require|number|>:0|check_conference_id',
        'product_media'         => 'check_product_media'


    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'product.brand.require' => '品牌不能为空',
        'product.brand.max'     => '品牌最多50个字符',
        'product.brand_logo'    => '品牌Logo不能为空',
        'product.brand_max'     => '品牌Logo最多255个字符',
        'product.product_name'  => '产品名称不能为空',
        'product.max'           => '产品名称最多100个字符',
        'product.conference_id' => '会议id不存在',
        'product.number'        => '会议id必须为数字',
        'product.>'             => '会议id大于0',
    ];

    protected $scene = [
        'create' => 'product.brand,product.brand_logo,product.product_name,product.conference_id,product_media',
        'update' => 'product.brand,product.brand_logo,product.product_name,product_media'
    ];


    /**
     * @description 检测产品媒体表字段
     * @param $product_media
     * @param $data
     * @return bool|string
     */
    public function check_product_media( $product_media,$data )
    {
        $flag = 0;

        foreach( $product_media as $v ){
            if( !empty( $v[ 'url' ] ) ){
                $flag++;
            }
        }
        if(!$flag){
            return '视频或图片至少传一个';
        }
        return true;
    }

    /**
     * @description 检测会议id是否存在
     * @param $conference_id
     * @param $data
     * @return bool|string
     */
    public function check_conference_id( $conference_id,$data )
    {
        $conference = \app\common\model\Conference::get($conference_id);
        if(!$conference){
            return '会议id不存在';
        }

        return true;
    }


}
