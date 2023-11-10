<?php

namespace App\Form;
use Symfony\Component\Form\AbstractType;

class AppType extends AbstractType{
    /**
     * Undocumented function
     *
     * @param string $label
     * @param string $placeholder
     * @param array $options
     * @param string $class
     * @return void
     */
    protected function conf($label,$placeholder,$options=[]){
        return array_merge([
                "label"=>$label,
                "attr"=>[
                    'placeholder'=>$placeholder
                ]     
            ],$options);
    }
}