<?php
namespace Rhef\Helper;

class I18n
{
    static function dashboard()
    {
        return [
            //modules
            "db" => esc_html__("Dashboard", "easyform"),
           
            //alert
            "scf" => esc_html__("Successfully", "easyform"),
            "aAdd" => esc_html__("Successfully Added", "easyform"),
            "aUpd" => esc_html__("Successfully Updated", "easyform"),
            "aDel" => esc_html__("Successfully Deleted", "easyform"),
            "aThankM" => esc_html__("Thanks for your message", "easyform"),
            "aThankR" => esc_html__("Thanks for payment request", "easyform"),
            "aMail" => esc_html__("Mail successfully sent", "easyform"),
            "aConf" => esc_html__("Are you sure to delete it?", "easyform"),
            "cp" => esc_html__("Copied", "easyform"),  
        ];
    }
}
