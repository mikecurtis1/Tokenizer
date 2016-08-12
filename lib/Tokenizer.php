<?php 

class Tokenizer 
{
	private $op_escape;
	private $op_prefix;
	private $op_index_separator;
	private $op_phrase_quote;
	private $op_token_delimiter;
	private $token_delimiter_replacement;
	private $tokens;

	public function __construct()
    {
		//TODO: these properties should come from a config file
        $this->op_escape = '\\';
		$this->op_prefix = array('+', '-', '|');
		$this->op_index_separator = ':';
		$this->op_phrase_quote = '"';
		$this->op_token_delimiter = ' ';
		//NOTE: chr(31) non-print ascii "unit separator" will never be user input
		$this->token_delimiter_replacement = chr(31);
		//NOTE: see http://en.wikipedia.org/wiki/Tokenization, 
		//NOTE: see http://nlp.stanford.edu/IR-book/html/htmledition/tokenization-1.html
		$this->tokens = array();
	}
	
	public function tokenize($query='')
    {
		$normalized = $this->normalizeWhiteSpace($query);
		$tokenized = $this->tokenizeQuotedPhrases($normalized);
		$this->setTokens($tokenized);
		$this->parseTokens();
		$this->cleanTokensIndex();
		$this->cleanTokensText();
		
		return $this->tokens;
	}
	
	private function normalizeWhiteSpace($string='')
    {
		$string = preg_replace('/\s{2,}/', ' ', $string);
		$string = trim($string);
		
		return $string;
	}
	
	private function isEscaped($i=0, $array=array())
    {
		$escaped = false;
		$c = $this->countEscapeChars($i, $array);
		if ($c % 2 !== 0) {
			$escaped = true;
		}
		
		return $escaped;
	}
	
	private function countEscapeChars($i=0, $array=array())
    {
		$pi = $i-1;
		$prev = null;
		$c = 0;
		while ($pi >= 0) {
			if (isset($array[$pi])) {
				$prev = $array[$pi];
			}
			if ($prev !== $this->op_escape) {
				break;
			}
			$pi--;
			$c++;
		}
		
		return $c;
	}
	
	private function tokenizeQuotedPhrases($string)
    {
		$array = str_split($string);
		$quoted = false;
		foreach ($array as $i => $char) {
			if (($char === $this->op_phrase_quote) && ($quoted === false) && ($this->isEscaped($i, $array) === false)) {
				$quoted = true;
			} elseif (($char === $this->op_phrase_quote) && ($quoted === true) && ($this->isEscaped($i, $array) === false)) {
				$quoted = false;
			}
			if (($quoted === true) && ($char == $this->op_token_delimiter)) {
				$array[$i] = $this->token_delimiter_replacement;
			}
		}
		
		return implode('',$array);
	}
	
	private function setTokens($string)
    {
		$temp = explode($this->op_token_delimiter,$string);
		foreach ($temp as $i => $v) {
			$this->tokens[] = new Token('', '', $v, false);
		}
		
		return;
	}
	
	private function updateToken($i=0, $prefix=null, $index=null, $text=null, $phrase=false)
    {
		if (isset($this->tokens{$i})) {
			$this->tokens{$i}->setPrefix($prefix);
			$this->tokens{$i}->setIndex($index);
			$this->tokens{$i}->setText($text);
			$this->tokens{$i}->setPhrase($phrase);
		}
		
		return;
	}
	
	private function parseTokens()
    {
		foreach ($this->tokens as $i => $e) {
			list($prefix, $index, $text, $phrase) = $this->parseToken($e);
			$this->updateToken($i, $prefix, $index, $text, $phrase);
		}
		
		return $this->tokens;
	}
	
	private function parseToken($e)
    {
		$string = $e->getText();
		$prefix = $this->getTokenPrefix($string);
		$index = $this->getTokenIndex($string);
		$text = $this->getTokenText($string);
		$phrase = $this->getTokenPhrase($text);
		
		return array($prefix, $index, $text, $phrase);
	}
	
	private function getTokenPrefix($string)
    {
		$prefix = substr($string, 0, 1);
		if ($this->isPrefixOperator($prefix) === false) {
			$prefix = null;
		}
		
		return $prefix;
	}
	
	private function getTokenIndex($string)
    {
		list($index, $text) = $this->splitOnIndexOp($string);
		return $index;
	}
	
	private function getTokenText($string)
    {
		list($index, $text) = $this->splitOnIndexOp($string);
		return $text;
	}
	
	private function getTokenPhrase($text)
    {
		$phrase = $this->isQuotedPhrase($text);
		return $phrase;
	}
	
	private function isPrefixOperator($string='')
    {
		$prefix = false;
		foreach ($this->op_prefix as $i => $p) {
			if ($string === $p) {
				$prefix = true;
			}
		}
		
		return $prefix;
	}
	
	private function isQuotedPhrase($string='')
    {
		$quoted = false;
		if ((substr($string, 0, 1) === $this->op_phrase_quote) && (substr($string, -1) === $this->op_phrase_quote)) {
			$quoted = true;
		}
		
		return $quoted;
	}
	
	private function splitOnIndexOp($string='')
    {
		$index = '';
		$text = $string;
		$array = str_split($string);
		foreach ($array as $i => $char) {
			if ($char === $this->op_index_separator && $this->isEscaped($i, $array) === false) {
				$index = substr($string, 0, $i);
				$text = substr($string, $i+1);
				break;
			}
		}
		
		return array($index,$text);
	}
	
	private function cleanTokensIndex()
    {
		foreach ($this->tokens as $i => $e) {
			$index = $e->getIndex();
			$index = $this->removePrefixOperators($index);
			$index = $this->removeEscapeChars($index);
			$this->updateToken($i, $e->getPrefix(), $index, $e->getText(), $e->getPhrase());
		}
		
		return;
	}
	
	private function cleanTokensText()
    {
		foreach ($this->tokens as $i => $e) {
			$text = $e->getText();
			$text = $this->removePrefixOperators($text);
			$text = $this->removeTokenDelimiterReplacement($text);
			$text = $this->removePhraseQuotes($text);
			$text = $this->removeEscapeChars($text);
			$this->updateToken($i, $e->getPrefix(), $e->getIndex(), $text, $e->getPhrase());
		}
		
		return;
	}
	
	private function removeTokenDelimiterReplacement($string='')
    {
		$string = str_replace($this->token_delimiter_replacement, $this->op_token_delimiter, $string);
		
		return $string;
	}
	
	private function removePrefixOperators($string='')
    {
		if ($this->isPrefixOperator(substr($string, 0, 1)) === true) {
			return substr($string, 1);
		} else {
			return $string;
		}
	}
	
	private function removePhraseQuotes($string='')
    {
		if ($this->isQuotedPhrase($string) === true) {
			$string = substr($string, 1, -1);
		}
		
		return trim($string);
	}
	
	private function removeEscapeChars($string='')
    {
		$array = str_split($string);
		$escape_pos = array();
		foreach ($array as $i => $char) {
			if (($char === $this->op_escape) && ($this->isEscaped($i, $array) === false)) {
				$escape_pos[] = $i;
			}
		}
		foreach ($escape_pos as $pos) {
			$array[$pos] = '';
		}
		
		return implode('', $array);
	}
}
