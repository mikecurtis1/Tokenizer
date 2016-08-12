<?php 

class Token
{
    private $prefix;
    private $index;
    private $text;
    private $phrase;

    public function __construct($prefix='', $index='', $text='', $phrase=false)
    {
        $this->setPrefix($prefix);
        $this->setIndex($index);
        $this->setText($text);
        $this->setPhrase($phrase);
    }
    
    public function setPrefix($str)
    {
        if (is_string($str)) {
            $this->prefix = $str;
        } else {
            $this->prefix = '';
        }
    }
    
    public function setIndex($str)
    {
        if (is_string($str)) {
            $this->index = $str;
        } else {
            $this->index = '';
        }
    }
    
    public function setText($str)
    {
        if (is_string($str)) {
            $this->text = $str;
        } else {
            $this->text = '';
        }
    }
    
    public function setPhrase($bool)
    {
        if (is_bool($bool)) {
            $this->phrase = $bool;
        } else {
            $this->phrase = false;
        }
    }
    
    public function getPrefix()
    {
        return $this->prefix;
    }
    
    public function getIndex()
    {
        return $this->index;
    }
    
    public function getText()
    {
        return $this->text;
    }
    
    public function getPhrase()
    {
        return $this->phrase;
    }
}
