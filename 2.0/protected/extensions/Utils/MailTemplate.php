<?php

/**
 * Class MailTemplate
 * 使用方法:
 *       $hooks = array(
            "searchStrs" => array("#APPNAME#", "#USERNAME#"),
            "subjectStrs" => array($websiteName, $username)
         );
 */
class MailTemplate {
    public $contents = NULL;

    /**
     * @param $template
     * @param $additionalHooks
     * @return bool|mixed
     */
    public function getTemplate($template, $additionalHooks)
    {
        $this->contents = file_get_contents(YII::app()->basePath.'/'.Yii::app()->params['mailTemplatesDir']. $template);

        //Check to see we can access the file / it has some contents
        if(!$this->contents || empty($this->contents))
        {
            return false;
        }
        else
        {
            //Replace default hooks
            $this->contents = F::replaceDefaultHook($this->contents);

            //Replace defined / custom hooks
            $this->contents = str_replace($additionalHooks["searchStrs"], $additionalHooks["subjectStrs"], $this->contents);

            return $this->contents;
        }
    }
}

?>