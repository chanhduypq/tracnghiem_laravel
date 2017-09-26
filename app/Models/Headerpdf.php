<?php 
namespace JP_COMMUNITY\Models;

class Headerpdf
{

    public function save($data) 
    {
        try {
            $data['json']=json_encode($data['text']);
            unset($data['text']);
            unset($data['content']);
            DB::table('header_pdf')->update($data);
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    public function getContent() 
    {

        try {
            $ret=DB::table('header_pdf')->first();
        } catch (Exception $e) {
            return array();
        }
        return $ret;
    }
    
    public static function getHeader(){
        try {
            $ret=DB::table('header_pdf')->first();
            return $ret['json'];
        } catch (Exception $e) {
            return '';
        }
    }

    

}
