<?php
namespace AliAbdalla\Tafqeet\Core;
use AliAbdalla\Tafqeet\Helper\Calculators;
use AliAbdalla\Tafqeet\Helper\Handler;
use AliAbdalla\Tafqeet\Helper\Validation;
use AliAbdalla\Tafqeet\Helper\App;
class Tafqeet
{
    use Calculators,Handler,Validation,App;

    public $config = [
        'connection_tool' => ' و',
        'default_currency' => 'sar',
        'starter'=>'فقط',
        'end'=>'لاغير',
        'currencies' => [
            'sar' => [
                'main1'=>'ريال',
                'main2'=>'ريالاً',
                'single'=>'هللة',
                'multi'=>'هللات'
            ],

            'sdg' => [
                'main1'=>'قرش',
                'main2'=>'قرشاً',
                'single'=>'قرش',
                'multi'=>'قروش'
            ],

            'usd' => [
                 'main1'=>'دولار',
                'main2'=>'دولاراً',
                'single'=>'سنت',
                'multi'=>'سنت'
            ],


        ],

    ];
    /*
     *  parsed number
     * */
    private $parsed_number;

    /*
     * array of numbers after split process
     * */
    private $parsed_number_array = [];


    /*
     * all number array
     * all array count
     * */
    private $all_numbers_len;
    private $all_numbers_array;



    /*
     * before comma number array
     * before comma array count
     * */
    private $before_comma_len;
    private $before_comma_array;



    /*
     * after comma number array
     * after comma array count
     * */
    private $after_comma_len;
    private $after_comma_array;
    public $after_comma_sum;




    /*
     * result before and after comma
     *
     * */
    private $result_before_comma;
    private $result_after_comma;






    private $is_main1_currency = true;



    public static function arablic($amount = 0,$currency = 'sar')
    {

       return (new self)->setAmount($amount)->initValidation()->prepare()->run()->result($currency);
    }


    public function run()
    {
        $this->result_before_comma = $this->runBeforeComma();
        $this->result_after_comma = $this->runAfterComma();
        return $this;
    }

    public function result($currency = 'sar')
    {
        $result = $this->config['starter'] . ' ';

        if($this->is_main1_currency){
            $result.= $this->result_before_comma . ' ' . $this->config['currencies'][$currency]['main1'];

        }else
        {
            $result.= $this->result_before_comma . ' ' . $this->config['currencies'][$currency]['main2'];

        }
        if($this->after_comma_len>=1)
        {
            if(in_array($this->after_comma_sum,[
                3,4,5,6,7,8,9,10
            ]))
            {
                $result.=$this->config['connection_tool']. $this->result_after_comma . ' ' .
                    $this->config['currencies'][$currency]['multi'];
            }else
            {
                $result.=$this->config['connection_tool']. $this->result_after_comma . ' ' .
                    $this->config['currencies'][$currency]['single'];
            }

        }

        $result.=  ' ' . $this->config['end'];

        return str_replace('  ',' ',$result);
    }

    public function prepare()
    {
        $this->split_parsed_number_to_two_number_depend_on_comma()->split_numbers_before_comma_to_array()->split_numbers_after_comma_to_array();
        return $this;
    }

}